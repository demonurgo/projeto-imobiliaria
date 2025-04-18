<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Xml extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Verificar se o usuário está logado
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }
        
        // Carregar modelos necessários
        $this->load->model('Nota_model');
        $this->load->model('Prestador_model');
        $this->load->model('Tomador_model');
        $this->load->model('Inquilino_model');
        $this->load->model('Imovel_model');
        $this->load->model('Log_model');
        
        // Carregar bibliotecas e helpers
        $this->load->helper(array('form', 'url', 'date'));
        $this->load->library(array('form_validation', 'session'));
    }

    public function index() {
        $data['title'] = 'Importação de XML';
        $data['active'] = 'importacao';
        
        $this->load->view('templates/header', $data);
        $this->load->view('xml/index', $data);
        $this->load->view('templates/footer');
    }

    public function upload() {
        $data['title'] = 'Importação de XML';
        $data['active'] = 'importacao';
        
        // Configurar regras de validação
        $upload_type = $this->input->post('upload_type');
        
        if ($upload_type == 'single') {
            $this->form_validation->set_rules('upload_type', 'Tipo de Upload', 'required');
        } else {
            $this->form_validation->set_rules('upload_type', 'Tipo de Upload', 'required');
        }
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('importacao');
            return;
        }
        
        // Configurar upload
        $config['upload_path'] = './uploads/xml/';
        $config['allowed_types'] = 'xml';
        $config['max_size'] = 5120; // 5MB
        
        // Verificar se o diretório uploads/xml existe, caso contrário, criá-lo
        $upload_dir = './uploads/xml/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, TRUE);
        }
        
        $this->load->library('upload', $config);
        
        if ($upload_type == 'single') {
            // Upload individual
            if (!$this->upload->do_upload('xmlfile')) {
                $error = array('error' => $this->upload->display_errors());
                $this->session->set_flashdata('error', $error['error']);
                redirect('importacao');
            } else {
                $upload_data = $this->upload->data();
                $file_path = $upload_data['full_path'];
                
                // Processar arquivo XML
                $result = $this->process_xml_file($file_path);
                
                if ($result['success']) {
                    // Registrar log de importação
                    $resumo = [
                        'notas_importadas' => $result['notas_importadas'],
                        'notas_ignoradas' => $result['notas_ignoradas'],
                        'notas_total' => $result['notas_importadas'] + $result['notas_ignoradas']
                    ];
                    $this->Log_model->add_log(
                        'import',
                        'notas',
                        null,
                        'Importação de XML - ' . $result['message'],
                        null,
                        [
                            'batch_id' => $result['batch_id'],
                            'resumo' => $resumo,
                            'arquivo' => $upload_data['file_name']
                        ]
                    );
                    
                    $this->session->set_flashdata('success', 'XML importado com sucesso. ' . $result['message']);
                    if ($result['notas_importadas'] > 0) {
                        redirect('importacao/revisar/' . $result['batch_id']);
                    } else {
                        // Todas as notas já existiam, não há nada para revisar
                        redirect('importacao');
                    }
                } else {
                    $this->session->set_flashdata('error', 'Erro ao processar XML: ' . $result['message']);
                    redirect('importacao');
                }
            }
        } else {
            // Upload múltiplo
            $files = $_FILES;
            $count = count($_FILES['xmlfiles']['name']);
            
            $batch_id = uniqid('batch_');
            $success_count = 0;
            $error_files = array();
            
            for ($i = 0; $i < $count; $i++) {
                $_FILES['xmlfile']['name'] = $files['xmlfiles']['name'][$i];
                $_FILES['xmlfile']['type'] = $files['xmlfiles']['type'][$i];
                $_FILES['xmlfile']['tmp_name'] = $files['xmlfiles']['tmp_name'][$i];
                $_FILES['xmlfile']['error'] = $files['xmlfiles']['error'][$i];
                $_FILES['xmlfile']['size'] = $files['xmlfiles']['size'][$i];
                
                if ($this->upload->do_upload('xmlfile')) {
                    $upload_data = $this->upload->data();
                    $file_path = $upload_data['full_path'];
                    
                    // Processar arquivo XML
                    $result = $this->process_xml_file($file_path, $batch_id);
                    
                    if ($result['success']) {
                        if ($result['notas_importadas'] > 0) {
                            $success_count++;
                        }
                        // Se todas as notas deste arquivo já existiam, não contabilizamos como sucesso
                    } else {
                        $error_files[] = $files['xmlfiles']['name'][$i] . ': ' . $result['message'];
                    }
                } else {
                    $error_files[] = $files['xmlfiles']['name'][$i] . ': ' . $this->upload->display_errors('', '');
                }
            }
            
            if ($success_count > 0) {
                $message = "$success_count arquivo(s) importado(s) com sucesso.";
                if (!empty($error_files)) {
                    $message .= " Erros em " . count($error_files) . " arquivo(s).";
                }
                
                // Registrar log de importação múltipla
                $resumo = [
                    'arquivos_processados' => $count,
                    'arquivos_com_sucesso' => $success_count,
                    'arquivos_com_erro' => count($error_files),
                    'erros' => $error_files
                ];
                $this->Log_model->add_log(
                    'import',
                    'notas',
                    null,
                    'Importação múltipla de XMLs - ' . $message,
                    null,
                    [
                        'batch_id' => $batch_id,
                        'resumo' => $resumo,
                        'arquivo' => 'multiple_files'
                    ]
                );
                
                $this->session->set_flashdata('success', $message);
                
                // Verificar se há notas para revisar
                $notas = $this->Nota_model->get_by_batch($batch_id);
                if (!empty($notas)) {
                    redirect('importacao/revisar/' . $batch_id);
                } else {
                    // Nenhuma nota foi importada (todas já existiam)
                    $this->session->set_flashdata('info', 'Todas as notas já existiam no sistema e foram ignoradas.');
                    redirect('importacao');
                }
            } else {
                $message = "Nenhum arquivo foi importado. Erros: " . implode(', ', $error_files);
                $this->session->set_flashdata('error', $message);
                redirect('importacao');
            }
        }
    }

    // Método auxiliar para converter código do município em nome da cidade
    private function get_cidade_by_codigo($codigo_municipio) {
        // Lista de códigos de municípios mais comuns em Pernambuco
        // Pode ser expandido conforme necessário ou substituído por uma consulta ao banco de dados
        $cidades = [
            '2611606' => 'Recife',
            '2609600' => 'Olinda',
            '2607901' => 'Jaboatão dos Guararapes',
            '2610707' => 'Paulista',
            '2603454' => 'Camaragibe',
            '2613701' => 'São Lourenço da Mata',
            '2607208' => 'Igarassu',
            '2607752' => 'Itapissuma',
            '2607604' => 'Ipojuca',
            '2606200' => 'Goiana',
            '2603900' => 'Carpina',
        ];
        
        // Retorna o nome da cidade se o código for encontrado, caso contrário retorna vazio
        return isset($cidades[$codigo_municipio]) ? $cidades[$codigo_municipio] : '';
    }
    
    private function process_xml_file($file_path, $batch_id = null) {
        if (!$batch_id) {
            $batch_id = uniqid('batch_');
        }
        
        // Registrar início do processamento
        $this->Log_model->add_log(
            'import',
            'notas',
            null,
            'Iniciando processamento do arquivo XML: ' . basename($file_path),
            null,
            ['batch_id' => $batch_id, 'arquivo' => basename($file_path), 'status' => 'inicio']
        );
        
        // Verificar se o arquivo existe
        if (!file_exists($file_path)) {
            return array('success' => false, 'message' => 'Arquivo não encontrado.');
        }
        
        // Carregar conteúdo do XML
        $xml_content = file_get_contents($file_path);
        
        // Verificar se o conteúdo é válido
        if (!$xml_content) {
            return array('success' => false, 'message' => 'Não foi possível ler o conteúdo do arquivo.');
        }
        
        // Converter XML para objeto
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($xml_content);
        
        if (!$xml) {
            $errors = libxml_get_errors();
            libxml_clear_errors();
            return array(
                'success' => false, 
                'message' => 'XML inválido: ' . (isset($errors[0]) ? $errors[0]->message : 'Formato desconhecido')
            );
        }
        
        // Registrar namespace
        if ($xml->getNamespaces(true)) {
            $namespace = $xml->getNamespaces(true);
            if (isset($namespace[''])) {
                $xml->registerXPathNamespace('ns', $namespace['']);
            }
        }
        
        // Verificar estrutura do XML
        if (!isset($xml->ListaNfse) || !isset($xml->ListaNfse->CompNfse)) {
            return array('success' => false, 'message' => 'Estrutura de XML não reconhecida. Verifique se é um XML de NFS-e válido.');
        }
        
        $notas = $xml->ListaNfse->CompNfse;
        
        if (!$notas || count($notas) == 0) {
            return array('success' => false, 'message' => 'Nenhuma nota fiscal encontrada no XML.');
        }
        
        $notas_importadas = 0;
        $notas_ignoradas = 0;
        $notas_total = count($notas);
        
        foreach ($notas as $comp_nfse) {
            $nfse = $comp_nfse->Nfse;
            $inf_nfse = $nfse->InfNfse;
            
            // Extrair dados do prestador
            $prestador_data = $this->extract_prestador_data($inf_nfse->PrestadorServico);
            $prestador_id = $this->Prestador_model->save($prestador_data);
            
            // Extrair dados do tomador e dados do imóvel a partir do mesmo bloco
            $tomador_data = $this->extract_tomador_data($inf_nfse->TomadorServico);
            $tomador_id = $this->Tomador_model->save($tomador_data);
            
            // Os dados do endereço do imóvel estão no tomador
            $imovel_data = $this->extract_imovel_data($inf_nfse->TomadorServico);
            
            // Definir o tomador como dono do imóvel
            $imovel_data['tomador_id'] = $tomador_id;
            
            // Extrair dados da discriminação
            $discriminacao = (string)$inf_nfse->Servico->Discriminacao;
            $discriminacao_data = $this->extract_discriminacao_data($discriminacao);
            
            // Extrair dados do inquilino da discriminação
            $inquilino_data = array();
            
            // Verifica se temos CPF ou CNPJ na discriminação
            if (isset($discriminacao_data['tipo_documento']) && isset($discriminacao_data['documento'])) {
                // Usar o campo cpf_cnpj para armazenar tanto CPF quanto CNPJ
                $inquilino_data['cpf_cnpj'] = $discriminacao_data['documento'];
            }
            
            // Adiciona o nome se disponível
            if (isset($discriminacao_data['nome_inquilino'])) {
                $inquilino_data['nome'] = $discriminacao_data['nome_inquilino'];
            }
            
            // Salva o inquilino se temos informações suficientes
            if (!empty($inquilino_data) && isset($inquilino_data['cpf_cnpj']) && isset($inquilino_data['nome'])) {
                $inquilino_id = $this->Inquilino_model->save($inquilino_data);
            } else {
                $inquilino_id = null;
            }
            
            // Complementar os dados do imóvel com informações extraídas da discriminação
            if (isset($discriminacao_data['valor_aluguel'])) {
                $imovel_data['valor_aluguel'] = $discriminacao_data['valor_aluguel'];
            }
            
            // Adicionar inquilino ao imóvel
            $imovel_data['inquilino_id'] = $inquilino_id;
            
            // Se houver endereço adicional na discriminação, adicionar como informação complementar
            if (isset($discriminacao_data['endereco_imovel']) && !empty($discriminacao_data['endereco_imovel'])) {
                $imovel_data['observacoes'] = 'Informação adicional da discriminação: ' . $discriminacao_data['endereco_imovel'];
            }
            
            // Salvar imóvel
            $imovel_id = $this->Imovel_model->save($imovel_data);
            
            // Formato da data: 2024-12-30T15:45:44 para 2024-12-30 15:45:44
            $data_emissao = str_replace('T', ' ', (string)$inf_nfse->DataEmissao);
            $competencia = str_replace('T', ' ', (string)$inf_nfse->Competencia);
            
            // Verificar se o CPF/CNPJ do tomador é igual ao do inquilino
            $tomador_cpf_cnpj = isset($tomador_data['cpf_cnpj']) ? $tomador_data['cpf_cnpj'] : '';
            $inquilino_cpf_cnpj = isset($inquilino_data['cpf_cnpj']) ? $inquilino_data['cpf_cnpj'] : '';
            $cpf_cnpj_duplicado = false;
            $observacoes = '';
            
            if (!empty($tomador_cpf_cnpj) && !empty($inquilino_cpf_cnpj) && $tomador_cpf_cnpj === $inquilino_cpf_cnpj) {
                $cpf_cnpj_duplicado = true;
                $observacoes = 'ATENÇÃO: O CPF/CNPJ do tomador é igual ao do inquilino. Verifique se os dados estão corretos.';
            }
            
            // Extrair dados da nota fiscal
            $nota_data = array(
                'numero' => (string)$inf_nfse->Numero,
                'codigo_verificacao' => (string)$inf_nfse->CodigoVerificacao,
                'data_emissao' => $data_emissao,
                'competencia' => $competencia,
                'valor_servicos' => (float)$inf_nfse->Servico->Valores->ValorServicos,
                'valor_iss' => (float)$inf_nfse->Servico->Valores->ValorIss,
                'base_calculo' => (float)$inf_nfse->Servico->Valores->BaseCalculo,
                'aliquota' => (float)$inf_nfse->Servico->Valores->Aliquota,
                'valor_liquido' => (float)$inf_nfse->Servico->Valores->ValorLiquidoNfse,
                'discriminacao' => $discriminacao,
                'prestador_id' => $prestador_id,
                'tomador_id' => $tomador_id,
                'inquilino_id' => $inquilino_id,
                'imovel_id' => $imovel_id,
                'batch_id' => $batch_id,
                'descricao_servico' => isset($discriminacao_data['descricao_servico']) ? $discriminacao_data['descricao_servico'] : '',
                'status' => $cpf_cnpj_duplicado ? 'revisar' : 'importado',
                'observacoes' => $observacoes
            );
            
            // Armazenar o valor do aluguel na própria nota, não apenas no imóvel
            if (isset($discriminacao_data['valor_aluguel'])) {
                $nota_data['valor_aluguel'] = $discriminacao_data['valor_aluguel'];
            }
            
            $nota_id = $this->Nota_model->save($nota_data);
            
            if ($nota_id) {
                $notas_importadas++;
                // Registrar log para cada nota importada
                $this->Log_model->add_log(
                    'import',
                    'notas',
                    $nota_id,
                    'Nota fiscal importada: ' . $nota_data['numero'],
                    null,
                    ['batch_id' => $batch_id, 'prestador' => $prestador_data['razao_social'], 'tipo' => 'nota_individual']
                );
            } else {
                // A nota já existia no banco de dados
                $notas_ignoradas++;
            }
        }
        
        $message = "$notas_importadas de $notas_total notas foram importadas.";
        if ($notas_ignoradas > 0) {
            $message .= " $notas_ignoradas notas já existiam no sistema e foram ignoradas.";
        }
        
        return array(
            'success' => true,
            'message' => $message,
            'batch_id' => $batch_id,
            'notas_importadas' => $notas_importadas,
            'notas_ignoradas' => $notas_ignoradas
        );
    }
    
    private function extract_discriminacao_data($discriminacao) {
        $data = array();
        
        // Padrão para extrair CPF/CNPJ, Nome do inquilino e Valor do aluguel
        // Aceita CPF (11 dígitos) ou CNPJ (14 dígitos)
        if (preg_match('/#(\d{11,14})#([^#]+)#([0-9.,]+)#/i', $discriminacao, $matches)) {
            // Armazenar o documento (CPF ou CNPJ) no mesmo campo
            $documento = $matches[1];
            $data['documento'] = $documento;
            $data['tipo_documento'] = (strlen($documento) == 11) ? 'cpf' : 'cnpj';
            
            $data['nome_inquilino'] = trim($matches[2]);
            $data['valor_aluguel'] = str_replace(',', '.', trim($matches[3])); // Converter para formato decimal
            
            // Remover a parte dos dados extraídos para obter o restante da discriminação
            $resto = preg_replace('/#\d{11,14}#[^#]+#[0-9.,]+#/i', '', $discriminacao);
        } else {
            $resto = $discriminacao;
        }
        
        // Separar descrição do serviço e endereço do imóvel
        $partes = explode("\n", $resto);
        
        if (count($partes) >= 1) {
            $data['descricao_servico'] = trim($partes[0]);
        }
        
        if (count($partes) >= 2) {
            $data['endereco_imovel'] = trim($partes[1]);
        }
        
        return $data;
    }
    
    private function extract_prestador_data($prestador_xml) {
        return array(
            'cnpj' => (string)$prestador_xml->IdentificacaoPrestador->Cnpj,
            'inscricao_municipal' => (string)$prestador_xml->IdentificacaoPrestador->InscricaoMunicipal,
            'razao_social' => (string)$prestador_xml->RazaoSocial,
            'endereco' => (string)$prestador_xml->Endereco->Endereco,
            'numero' => (string)$prestador_xml->Endereco->Numero,
            'complemento' => (string)$prestador_xml->Endereco->Complemento,
            'bairro' => (string)$prestador_xml->Endereco->Bairro,
            'codigo_municipio' => (string)$prestador_xml->Endereco->CodigoMunicipio,
            'uf' => (string)$prestador_xml->Endereco->Uf,
            'cep' => (string)$prestador_xml->Endereco->Cep,
            'telefone' => isset($prestador_xml->Contato->Telefone) ? (string)$prestador_xml->Contato->Telefone : '',
            'email' => isset($prestador_xml->Contato->Email) ? (string)$prestador_xml->Contato->Email : ''
        );
    }
    
    private function extract_tomador_data($tomador_xml) {
        // Extrair APENAS os dados do tomador (proprietário), não o endereço do imóvel
        $data = array(
            'razao_social' => (string)$tomador_xml->RazaoSocial,
            'email' => isset($tomador_xml->Contato->Email) ? (string)$tomador_xml->Contato->Email : '',
            'telefone' => isset($tomador_xml->Contato->Telefone) ? (string)$tomador_xml->Contato->Telefone : ''
        );
        
        // Verificar se é CPF ou CNPJ e colocar no campo cpf_cnpj
        if (isset($tomador_xml->IdentificacaoTomador->CpfCnpj->Cpf)) {
            $data['cpf_cnpj'] = (string)$tomador_xml->IdentificacaoTomador->CpfCnpj->Cpf;
            // Adicional para compatível com tabelas que têm campos separados
            $data['cpf'] = (string)$tomador_xml->IdentificacaoTomador->CpfCnpj->Cpf;
        } elseif (isset($tomador_xml->IdentificacaoTomador->CpfCnpj->Cnpj)) {
            $data['cpf_cnpj'] = (string)$tomador_xml->IdentificacaoTomador->CpfCnpj->Cnpj;
            // Adicional para compatível com tabelas que têm campos separados
            $data['cnpj'] = (string)$tomador_xml->IdentificacaoTomador->CpfCnpj->Cnpj;
        }
        
        return $data;
    }
    
    // Método para extrair dados do imóvel a partir do bloco TomadorServico
    private function extract_imovel_data($tomador_xml) {
        // O endereço no bloco TomadorServico é na verdade o endereço do IMÓVEL
        // e não o endereço do tomador (proprietário)
        
        // Código do município para cidade (se necessário, pode ser convertido usando uma tabela)
        $codigo_municipio = isset($tomador_xml->Endereco->CodigoMunicipio) ? (string)$tomador_xml->Endereco->CodigoMunicipio : '';
        
        // Obter informações do endereço
        $endereco = isset($tomador_xml->Endereco->Endereco) ? (string)$tomador_xml->Endereco->Endereco : '';
        $numero = isset($tomador_xml->Endereco->Numero) ? (string)$tomador_xml->Endereco->Numero : '';
        $complemento = isset($tomador_xml->Endereco->Complemento) ? (string)$tomador_xml->Endereco->Complemento : '';
        $bairro = isset($tomador_xml->Endereco->Bairro) ? (string)$tomador_xml->Endereco->Bairro : '';
        $cidade = $this->get_cidade_by_codigo($codigo_municipio);
        $uf = isset($tomador_xml->Endereco->Uf) ? (string)$tomador_xml->Endereco->Uf : '';
        $cep = isset($tomador_xml->Endereco->Cep) ? (string)$tomador_xml->Endereco->Cep : '';
        
        // Gerar um código de referência baseado no endereço para prevenir duplicações
        // Cria um hash baseado no endereço completo
        $endereco_completo = $endereco . $numero . $complemento . $bairro . $cidade . $uf;
        $codigo_referencia = 'IMOV-' . substr(md5($endereco_completo), 0, 6);
        
        // Montar dados completos do imóvel
        return array(
            'endereco' => $endereco,
            'numero' => $numero,
            'complemento' => $complemento,
            'bairro' => $bairro,
            'cidade' => $cidade,
            'uf' => $uf,
            'cep' => $cep,
            'codigo_referencia' => $codigo_referencia,
            // O dono do imóvel é o tomador
            // tomador_id será definido depois no método process_xml_file
        );
    }
    
    public function review($batch_id = null) {
        if (!$batch_id) {
            $this->session->set_flashdata('error', 'ID do lote não fornecido.');
            redirect('importacao');
        }
        
        $data['title'] = 'Revisão da Importação';
        $data['active'] = 'importacao';
        $data['notas'] = $this->Nota_model->get_by_batch($batch_id);
        $data['batch_id'] = $batch_id;
        
        $this->load->view('templates/header', $data);
        $this->load->view('xml/review', $data);
        $this->load->view('templates/footer');
    }
    
    public function edit($id) {
        $nota = $this->Nota_model->get_by_id($id);
        
        if (!$nota) {
            $this->session->set_flashdata('error', 'Nota fiscal não encontrada.');
            redirect('importacao');
        }
        
        // Recuperar dados do inquilino, se existir
        if (!empty($nota['inquilino_id'])) {
            $inquilino = $this->Inquilino_model->get_by_id($nota['inquilino_id']);
            if ($inquilino) {
                $nota['inquilino_nome'] = $inquilino['nome'];
                $nota['inquilino_cpf_cnpj'] = $inquilino['cpf_cnpj'];
            }
        }
        
        // Verificar status e observações
        if (!isset($nota['status'])) {
            $nota['status'] = 'importado';
        }
        
        if (!isset($nota['observacoes'])) {
            $nota['observacoes'] = '';
        }
        
        // Recuperar dados do imóvel, se existir
        if (!empty($nota['imovel_id'])) {
            $imovel = $this->Imovel_model->get_by_id($nota['imovel_id']);
            if ($imovel) {
                $nota['imovel_endereco'] = $imovel['endereco'];
                $nota['imovel_numero'] = $imovel['numero'];
                $nota['imovel_complemento'] = $imovel['complemento'];
                $nota['imovel_cidade'] = $imovel['cidade'];
                $nota['imovel_uf'] = $imovel['uf'];
                $nota['imovel_cep'] = $imovel['cep'];
                $nota['valor_aluguel'] = $imovel['valor_aluguel'];
            }
        }
        
        $data['title'] = 'Editar Nota Fiscal';
        $data['active'] = 'importacao';
        $data['nota'] = $nota;
        $data['prestadores'] = $this->Prestador_model->get_all();
        $data['tomadores'] = $this->Tomador_model->get_all();
        $data['inquilinos'] = $this->Inquilino_model->get_all();
        $data['imoveis'] = $this->Imovel_model->get_all();
        
        $this->form_validation->set_rules('numero', 'Número', 'required');
        $this->form_validation->set_rules('data_emissao', 'Data de Emissão', 'required');
        $this->form_validation->set_rules('valor_servicos', 'Valor Serviços', 'required|numeric');
        
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('xml/edit', $data);
            $this->load->view('templates/footer');
        } else {
            // Processamento do inquilino
            $inquilino_id = $this->input->post('inquilino_id');
            $inquilino_nome = $this->input->post('inquilino_nome');
            $inquilino_documento = $this->input->post('inquilino_documento');
            
            // Criar ou atualizar inquilino
            if (!empty($inquilino_nome) && !empty($inquilino_documento)) {
                $inquilino_data = array(
                    'nome' => $inquilino_nome,
                    'cpf_cnpj' => $inquilino_documento
                );
                
                // Se selecionou um inquilino existente, atualizar seus dados
                if (!empty($inquilino_id)) {
                    $this->Inquilino_model->update($inquilino_id, $inquilino_data);
                } else {
                    // Caso contrário, criar um novo
                    $inquilino_id = $this->Inquilino_model->save($inquilino_data);
                }
            }
            
            // Processamento do imóvel
            $imovel_id = $this->input->post('imovel_id');
            $imovel_endereco = $this->input->post('imovel_endereco');
            $imovel_numero = $this->input->post('imovel_numero');
            $imovel_complemento = $this->input->post('imovel_complemento');
            $imovel_cidade = $this->input->post('imovel_cidade');
            $imovel_uf = $this->input->post('imovel_uf');
            $imovel_cep = $this->input->post('imovel_cep');
            $valor_aluguel = $this->input->post('valor_aluguel');
            
            // Criar ou atualizar imóvel
            if (!empty($imovel_endereco)) {
                $imovel_data = array(
                    'endereco' => $imovel_endereco,
                    'inquilino_id' => $inquilino_id
                );
                
                // Adicionar o número e complemento se fornecidos
                if (!empty($imovel_numero)) {
                    $imovel_data['numero'] = $imovel_numero;
                }
                
                if (!empty($imovel_complemento)) {
                    $imovel_data['complemento'] = $imovel_complemento;
                }
                
                if (!empty($imovel_cidade)) {
                    $imovel_data['cidade'] = $imovel_cidade;
                }
                
                if (!empty($imovel_uf)) {
                    $imovel_data['uf'] = $imovel_uf;
                }
                
                if (!empty($imovel_cep)) {
                    $imovel_data['cep'] = $imovel_cep;
                }
                
                if (!empty($valor_aluguel)) {
                    $imovel_data['valor_aluguel'] = $valor_aluguel;
                }
                
                // Se selecionou um imóvel existente, atualizar seus dados
                if (!empty($imovel_id)) {
                    $this->Imovel_model->update($imovel_id, $imovel_data);
                } else {
                    // Caso contrário, criar um novo
                    $imovel_id = $this->Imovel_model->save($imovel_data);
                }
            }
            // Se selecionou um imóvel mas não preencheu o endereço, apenas atualizar os campos fornecidos
            elseif (!empty($imovel_id)) {
                $update_data = array(
                    'inquilino_id' => $inquilino_id
                );
                
                // Adicionar apenas os campos que foram preenchidos
                if (!empty($valor_aluguel)) {
                    $update_data['valor_aluguel'] = $valor_aluguel;
                }
                
                if (!empty($imovel_numero)) {
                    $update_data['numero'] = $imovel_numero;
                }
                
                if (!empty($imovel_complemento)) {
                    $update_data['complemento'] = $imovel_complemento;
                }
                
                if (!empty($imovel_cidade)) {
                    $update_data['cidade'] = $imovel_cidade;
                }
                
                if (!empty($imovel_uf)) {
                    $update_data['uf'] = $imovel_uf;
                }
                
                if (!empty($imovel_cep)) {
                    $update_data['cep'] = $imovel_cep;
                }
                
                $this->Imovel_model->update($imovel_id, $update_data);
            }
            
            // Verificar se o CPF/CNPJ do tomador é igual ao do inquilino
            $tomador_id = $this->input->post('tomador_id');
            $tomador = $this->Tomador_model->get_by_id($tomador_id);
            $tomador_cpf_cnpj = $tomador ? $tomador['cpf_cnpj'] : '';
            
            $cpf_cnpj_duplicado = false;
            $observacoes = '';
            
            if (!empty($inquilino_id) && !empty($tomador_cpf_cnpj) && !empty($inquilino_documento)) {
                if ($tomador_cpf_cnpj === $inquilino_documento) {
                    $cpf_cnpj_duplicado = true;
                    $observacoes = 'ATENÇÃO: O CPF/CNPJ do tomador é igual ao do inquilino. Verifique se os dados estão corretos.';
                }
            }
            
            // Atualizar a nota com os novos dados
            $valor_servicos = $this->input->post('valor_servicos');
            
            // Formatar competência corretamente (garantir formato YYYY-MM-DD)
            $competencia = $this->input->post('competencia');
            
            // Se a competência estiver no formato YYYY-MM (sem dia)
            if (preg_match('/^\d{4}-\d{2}$/', $competencia)) {
                $competencia = $competencia . '-01'; // Adicionar o dia 01
            }
            // Se a competência for apenas o mês/ano no formato MM/YYYY
            else if (preg_match('/^\d{2}\/\d{4}$/', $competencia)) {
                list($mes, $ano) = explode('/', $competencia);
                $competencia = $ano . '-' . $mes . '-01';
            }
            // Se estiver vazio, usar a data atual
            else if (empty($competencia)) {
                $competencia = date('Y-m-01'); // Primeiro dia do mês atual
            }
            
            $update_data = array(
                'numero' => $this->input->post('numero'),
                'codigo_verificacao' => $this->input->post('codigo_verificacao'),
                'data_emissao' => $this->input->post('data_emissao'),
                'competencia' => $competencia, // Usar a competência formatada
                'valor_servicos' => $valor_servicos,
                'valor_iss' => $this->input->post('valor_iss'),
                'base_calculo' => $this->input->post('base_calculo'),
                'aliquota' => $this->input->post('aliquota'),
                // Garante que valor_liquido sempre seja igual ao valor_servicos
                'valor_liquido' => $valor_servicos,
                'discriminacao' => $this->input->post('discriminacao'),
                'descricao_servico' => $this->input->post('descricao_servico'),
                'prestador_id' => $this->input->post('prestador_id'),
                'tomador_id' => $this->input->post('tomador_id'),
                'status' => $cpf_cnpj_duplicado ? 'revisar' : 'atualizado',
                'observacoes' => $cpf_cnpj_duplicado ? $observacoes : (isset($nota['observacoes']) ? $nota['observacoes'] : ''),
                'editado_manualmente' => 1,  // Marca como editado manualmente
                'valor_aluguel' => $this->input->post('valor_aluguel') // Armazena o valor do aluguel na nota
            );
            
            // Adicionar referências aos inquilinos e imóveis, se existirem
            if (!empty($inquilino_id)) {
                $update_data['inquilino_id'] = $inquilino_id;
            }
            
            if (!empty($imovel_id)) {
                $update_data['imovel_id'] = $imovel_id;
            }
            
            if ($this->Nota_model->update($id, $update_data)) {
                $this->session->set_flashdata('success', 'Nota fiscal atualizada com sucesso.');
                redirect('importacao/revisar/' . $nota['batch_id']);
            } else {
                $this->session->set_flashdata('error', 'Erro ao atualizar nota fiscal.');
                $this->load->view('templates/header', $data);
                $this->load->view('xml/edit', $data);
                $this->load->view('templates/footer');
            }
        }
    }
    
    public function delete($id) {
        $nota = $this->Nota_model->get_by_id($id);
        
        if (!$nota) {
            $this->session->set_flashdata('error', 'Nota fiscal não encontrada.');
            redirect('importacao');
        }
        
        $batch_id = $nota['batch_id'];
        
        if ($this->Nota_model->delete($id)) {
            // Registrar log de exclusão
            $this->Log_model->add_log(
                'delete',
                'notas',
                $id,
                'Nota fiscal excluída durante revisão de importação',
                ['numero' => $nota['numero'], 'batch_id' => $batch_id],
                null
            );
            $this->session->set_flashdata('success', 'Nota fiscal excluída com sucesso.');
        } else {
            $this->session->set_flashdata('error', 'Erro ao excluir nota fiscal.');
        }
        
        redirect('importacao/revisar/' . $batch_id);
    }
    
    public function complete_import($batch_id) {
        // Atualizar status das notas para 'processado'
        $notas = $this->Nota_model->get_by_batch($batch_id);
        $count = count($notas);
        
        foreach ($notas as $nota) {
            $this->Nota_model->update($nota['id'], array('status' => 'processado'));
        }
        
        // Registrar log de conclusão da importação
        $this->Log_model->add_log(
            'import',
            'notas',
            null,
            'Importação finalizada - ' . $count . ' notas processadas',
            null,
            ['batch_id' => $batch_id, 'notas_processadas' => $count, 'status' => 'finalizado']
        );
        
        $this->session->set_flashdata('success', 'Importação concluída com sucesso.');
        redirect('notas');
    }

    public function diagnostico() {
        $data['title'] = 'Diagnóstico da Importação';
        $data['active'] = 'importacao';
        
        // Verificar diretório de upload
        $upload_dir = './uploads/xml/';
        $data['upload_dir_exists'] = is_dir($upload_dir);
        $data['upload_dir_writable'] = is_writable($upload_dir);
        
        if (!$data['upload_dir_exists']) {
            // Tentar criar o diretório
            $dir_created = mkdir($upload_dir, 0777, TRUE);
            $data['dir_creation_attempt'] = $dir_created ? 'Sucesso' : 'Falha';
        }
        
        // Verificar configurações de upload
        $this->load->library('upload');
        $data['max_upload_size'] = ini_get('upload_max_filesize');
        $data['max_post_size'] = ini_get('post_max_size');
        
        // Verificar a biblioteca SimpleXML
        $data['simplexml_loaded'] = extension_loaded('simplexml');
        
        // Testar expressão regular
        $test_string = "#03100742431#José dos Santos#1500,50# IMÓVEL - TESTE\n\nRUA TESTE, 123";
        preg_match('/#(\d{11,14})#([^#]+)#([0-9.,]+)#/i', $test_string, $matches);
        $data['regex_test'] = !empty($matches) ? 'Sucesso' : 'Falha';
        $data['regex_matches'] = print_r($matches, true);
        
        $this->load->view('templates/header', $data);
        $this->load->view('xml/diagnostico', $data);
        $this->load->view('templates/footer');
    }
}
