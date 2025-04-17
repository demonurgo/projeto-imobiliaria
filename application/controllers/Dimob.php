<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dimob extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Dimob_model');
        $this->load->model('Nota_model');
        $this->load->model('Prestador_model');
        $this->load->model('Tomador_model');
        $this->load->model('Inquilino_model');
        $this->load->model('Imovel_model');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->library('session');
        
        // Verificar login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }
    
    /**
     * Página inicial do módulo DIMOB com seleção de ano
     */
    public function index() {
        $data['title'] = 'Geração de Arquivo DIMOB';
        $data['anos_disponiveis'] = $this->get_anos_disponiveis();
        
        // Carregar o template com a view
        $this->load->view('templates/header', $data);
        $this->load->view('dimob/index', $data);
        $this->load->view('templates/footer');
    }
    
    /**
     * Exibe a lista de notas fiscais do ano selecionado para geração do DIMOB
     */
    public function listar() {
        // Verificar tanto parâmetros POST quanto GET
        $ano = $this->input->post('ano') ?: $this->input->get('ano');
        
        if (empty($ano)) {
            $this->session->set_flashdata('error', 'Ano não informado');
            redirect('dimob');
        }
        
        // Carregar as notas fiscais do ano selecionado
        $notas = $this->Dimob_model->get_notas_por_ano($ano);
        
        // Validar os dados das notas
        $erros = $this->Dimob_model->validar_dados_dimob($notas);
        
        // Verificar inconsistências (CPF igual entre tomador e inquilino com nomes diferentes)
        $inconsistencias = $this->verificar_inconsistencias($notas);
        
        $data['title'] = 'Notas Fiscais para DIMOB - Ano ' . $ano;
        $data['ano'] = $ano;
        $data['notas'] = $notas;
        $data['erros'] = $erros;
        $data['inconsistencias'] = $inconsistencias;
        
        // Totais por mês para mostrar no resumo
        $totais_mensais = $this->calcular_totais_mensais($notas);
        $data['totais_mensais'] = $totais_mensais;
        
        // Carregar o template com a view
        $this->load->view('templates/header', $data);
        $this->load->view('dimob/listar', $data);
        $this->load->view('templates/footer');
    }
    
    /**
     * Gera o arquivo DIMOB e o disponibiliza para download
     */
    public function gerar() {
        $ano = $this->input->post('ano');
        
        if (empty($ano)) {
            $this->session->set_flashdata('error', 'Ano não informado');
            redirect('dimob');
        }
        
        // Carregar as notas fiscais do ano selecionado
        $notas = $this->Dimob_model->get_notas_por_ano($ano);
        
        // Verificar se há erros fatais que impedem a geração
        $erros = $this->Dimob_model->validar_dados_dimob($notas);
        if (!empty($erros) && $this->input->post('confirma') != '1') {
            $this->session->set_flashdata('error', 'Existem erros que impedem a geração do arquivo DIMOB. Corrija os erros e tente novamente.');
            redirect('dimob/listar?ano=' . $ano);
        }
        
        // Agrupar notas por prestador para gerar um arquivo por prestador
        $notas_por_prestador = [];
        foreach ($notas as $nota) {
            $prestador_id = $nota['prestador_id'];
            if (!isset($notas_por_prestador[$prestador_id])) {
                $notas_por_prestador[$prestador_id] = [];
            }
            $notas_por_prestador[$prestador_id][] = $nota;
        }
        
        // Se não há notas, exibir mensagem
        if (empty($notas_por_prestador)) {
            $this->session->set_flashdata('error', 'Não há notas fiscais para o ano selecionado.');
            redirect('dimob');
        }
        
        // Gerar um arquivo por prestador
        $arquivos_gerados = [];
        
        foreach ($notas_por_prestador as $prestador_id => $notas_prestador) {
            // Gerar o conteúdo do arquivo DIMOB
            $conteudo = $this->Dimob_model->gerar_conteudo_arquivo_dimob($notas_prestador, $ano);
            
            // Obter dados do prestador
            $prestador = $this->Prestador_model->get_by_id($prestador_id);
            
            // Criar nome do arquivo
            $nome_arquivo = 'DIMOB_' . $prestador_id . '_' . $ano . '_' . date('YmdHis') . '.txt';
            
            // Salvar o arquivo em disco
            $pasta_upload = FCPATH . 'uploads/dimob/';
            if (!is_dir($pasta_upload)) {
                mkdir($pasta_upload, 0755, true);
            }
            
            $caminho_arquivo = $pasta_upload . $nome_arquivo;
            file_put_contents($caminho_arquivo, $conteudo);
            
            // Salvar registro do arquivo gerado
            $dados_arquivo = [
                'nome_arquivo' => $nome_arquivo,
                'ano_referencia' => $ano,
                'prestador_id' => $prestador_id,
                'data_geracao' => date('Y-m-d H:i:s'),
                'gerado_por' => $this->session->userdata('user_id'),
                'numero_registros' => count($notas_prestador),
                'arquivo_path' => 'uploads/dimob/' . $nome_arquivo
            ];
            
            $id_arquivo = $this->Dimob_model->salvar_arquivo_dimob($dados_arquivo);
            
            // Marcar notas como incluídas no DIMOB
            $ids_notas = array_column($notas_prestador, 'id');
            $this->Dimob_model->marcar_notas_como_dimob_enviado($ids_notas);
            
            $arquivos_gerados[] = [
                'nome' => $nome_arquivo,
                'caminho' => $caminho_arquivo,
                'prestador' => $prestador['razao_social'],
                'total_notas' => count($notas_prestador)
            ];
        }
        
        // Se for apenas um arquivo, fazer download direto
        if (count($arquivos_gerados) == 1) {
            $arquivo = $arquivos_gerados[0];
            
            header('Content-Type: text/plain');
            header('Content-Disposition: attachment; filename="' . $arquivo['nome'] . '"');
            header('Content-Length: ' . filesize($arquivo['caminho']));
            
            readfile($arquivo['caminho']);
            exit();
        } else {
            // Se forem múltiplos arquivos, exibir lista para download
            $data['title'] = 'Arquivos DIMOB Gerados';
            $data['arquivos'] = $arquivos_gerados;
            $data['ano'] = $ano;
            
            $this->load->view('templates/header', $data);
            $this->load->view('dimob/arquivos_gerados', $data);
            $this->load->view('templates/footer');
        }
    }
    
    /**
     * Exibe o formulário para edição de uma nota fiscal
     */
    public function editar_nota($nota_id) {
        $data['title'] = 'Editar Nota Fiscal para DIMOB';
        $data['nota'] = $this->Nota_model->get_by_id($nota_id);
        
        if (empty($data['nota'])) {
            $this->session->set_flashdata('error', 'Nota fiscal não encontrada');
            redirect('dimob');
        }
        
        // Buscar dados relacionados para exibição no formulário
        if (!empty($data['nota']['inquilino_id'])) {
            $inquilino = $this->Inquilino_model->get_by_id($data['nota']['inquilino_id']);
            if ($inquilino) {
                $data['inquilino'] = $inquilino;
                $data['nota']['inquilino_cpf_cnpj'] = $inquilino['cpf_cnpj'] ?? '';
                $data['nota']['inquilino_nome'] = $inquilino['nome'] ?? '';
                $data['nota']['inquilino_email'] = $inquilino['email'] ?? '';
            }
        } else {
            // Garantir que não há dados de inquilino quando não há inquilino_id
            unset($data['nota']['inquilino_cpf_cnpj']);
            unset($data['nota']['inquilino_nome']);
            unset($data['inquilino']);
        }
        
        // Buscar dados do tomador
        if (!empty($data['nota']['tomador_id'])) {
            $tomador = $this->Tomador_model->get_by_id($data['nota']['tomador_id']);
            if ($tomador) {
                $data['tomador'] = $tomador;
                $data['nota']['tomador_cpf_cnpj'] = $tomador['cpf_cnpj'] ?? '';
                $data['nota']['tomador_nome'] = $tomador['razao_social'] ?? '';
            }
        }
        
        // Buscar dados do imóvel
        if (!empty($data['nota']['imovel_id'])) {
            $imovel = $this->Imovel_model->get_by_id($data['nota']['imovel_id']);
            if ($imovel) {
                $data['nota']['imovel_endereco'] = $imovel['endereco'];
                $data['nota']['tipo_imovel'] = $imovel['tipo_imovel'] ?? 'urbano';
                $data['nota']['valor_aluguel'] = $imovel['valor_aluguel'] ?? 0;
                // Adicionamos o imóvel completo para poder acessar outros dados
                $data['imovel_atual'] = $imovel;
            }
        }
        
        $data['prestadores'] = $this->Prestador_model->get_all();
        $data['tomadores'] = $this->Tomador_model->get_all();
        $data['inquilinos'] = $this->Inquilino_model->get_all();
        $data['imoveis'] = $this->Imovel_model->get_all();
        
        // Carregar o template com a view
        $this->load->view('templates/header', $data);
        $this->load->view('dimob/editar_nota', $data);
        $this->load->view('templates/footer');
    }
    
    /**
     * Processa a atualização de uma nota fiscal
     */
    public function atualizar_nota() {
        // Debug - registrar todos os campos do formulário
        log_message('debug', 'Dimob: DADOS DO FORMULÁRIO: ' . print_r($this->input->post(), true));
        
        $nota_id = $this->input->post('id');
        $ano = $this->input->post('ano');
        $prestador_id = $this->input->post('prestador_id');
        
        if (empty($nota_id)) {
            $this->session->set_flashdata('error', 'ID da nota não informado');
            redirect('dimob');
        }
        
        // Buscar a nota atual
        $nota = $this->Nota_model->get_by_id($nota_id);
        
        // Obter valores originais para comparação
        $valor_servicos_original = $nota['valor_servicos'];
        $valor_aluguel_original = $nota['valor_aluguel'] ?? 0;
        
        // Formatar valor monetário (preservando os decimais)
        // Primeiro verificar se foram enviados campos ocultos com os valores já formatados
        $valor_servicos = $this->input->post('valor_servicos_hidden') ?: $this->input->post('valor_servicos');
        $valor_aluguel = $this->input->post('valor_aluguel_hidden') ?: $this->input->post('valor_aluguel');
        
        // Função para converter valores monetários no formato brasileiro para float
        function convert_br_money_to_float($valor) {
            // Primeiro, limpar qualquer formatação não numérica exceto vírgula e ponto
            $valor = preg_replace('/[^0-9,.]/', '', $valor);
            
            // Se o valor contém vírgula (formato brasileiro), converter corretamente
            if (strpos($valor, ',') !== false) {
                // Se tem pontos (ex: 1.234,56) - remover os pontos e substituir vírgula por ponto
                if (strpos($valor, '.') !== false) {
                    $valor = str_replace('.', '', $valor); // Remove pontos
                }
                $valor = str_replace(',', '.', $valor); // Substitui vírgula por ponto
            }
            
            // Retornar como float com precisão de 2 casas decimais
            return (float)$valor;
        }
        
        // Usar os valores numéricos convertidos para salvar no banco
        $valor_servicos = convert_br_money_to_float($valor_servicos);
        $valor_aluguel = convert_br_money_to_float($valor_aluguel);
        
        // Log para debug
        log_message('debug', 'Dimob: valor_servicos=' . $valor_servicos . ', valor_aluguel=' . $valor_aluguel);
        
        // Se os valores são 0, verificar se houve problema na conversão
        if ($valor_servicos == 0 && $this->input->post('valor_servicos') != '0' && $this->input->post('valor_servicos') != '0,00') {
            log_message('error', 'Dimob: Erro na conversão do valor_servicos: ' . $this->input->post('valor_servicos'));
        }
        
        if ($valor_aluguel == 0 && $this->input->post('valor_aluguel') != '0' && $this->input->post('valor_aluguel') != '0,00') {
            log_message('error', 'Dimob: Erro na conversão do valor_aluguel: ' . $this->input->post('valor_aluguel'));
        }
        
        // Obter dados completos das entidades selecionadas
        $tomador = $this->Tomador_model->get_by_id($this->input->post('tomador_id'));
        
        // Verificar se há dados de inquilino preenchidos no formulário
        $inquilino_id = $this->input->post('inquilino_id');
        $inquilino_nome = $this->input->post('inquilino_nome');
        $inquilino_cpf_cnpj = preg_replace('/[^0-9]/', '', $this->input->post('inquilino_cpf_cnpj'));
        $inquilino_email = $this->input->post('inquilino_email');
        $inquilino_telefone = $this->input->post('inquilino_telefone');
        
        // Se temos CPF e nome do inquilino, mas não temos um inquilino_id ou este está vazio
        if (!empty($inquilino_nome) && !empty($inquilino_cpf_cnpj) && (empty($inquilino_id) || $inquilino_id === '')) {
            // Verificar se já existe um inquilino com este CPF/CNPJ
            $inquilino_existente = $this->Inquilino_model->get_by_cpf($inquilino_cpf_cnpj);
            
            if ($inquilino_existente) {
                // Se já existe, usar o ID existente
                $inquilino_id = $inquilino_existente['id'];
                $inquilino = $inquilino_existente;
            } else {
                // Se não existe, criar um novo inquilino
                $dados_inquilino = [
                    'nome' => $inquilino_nome,
                    'cpf_cnpj' => $inquilino_cpf_cnpj,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                
                if (!empty($inquilino_email)) {
                    $dados_inquilino['email'] = $inquilino_email;
                }
                
                if (!empty($inquilino_telefone)) {
                    $dados_inquilino['telefone'] = $inquilino_telefone;
                }
                
                // Salvar o novo inquilino e obter o ID
                $inquilino_id = $this->Inquilino_model->save($dados_inquilino);
                
                log_message('debug', 'Dimob: Novo inquilino criado com ID=' . $inquilino_id . ' durante edição da nota ID=' . $nota_id . '. Será vinculado ao imóvel ID=' . $this->input->post('imovel_id'));
                
                // Log de criação do inquilino
                if (method_exists($this, 'log_activity')) {
                    $this->log_activity(
                        'create',
                        'inquilinos',
                        $inquilino_id,
                        'Inquilino criado automaticamente durante edição de nota fiscal DIMOB'
                    );
                }
                
                // Buscar o inquilino recém-criado
                $inquilino = $this->Inquilino_model->get_by_id($inquilino_id);
            }
        } else {
            // Buscar o inquilino normalmente se o ID existe
            $inquilino = !empty($inquilino_id) ? $this->Inquilino_model->get_by_id($inquilino_id) : null;
        }
        
        // Atualizar dados do tomador na base se houve alteração nos campos manuais
        $tomador_nome_form = $this->input->post('tomador_nome');
        if (!empty($tomador_nome_form) && $tomador && $tomador['razao_social'] != $tomador_nome_form) {
            // O nome foi modificado no formulário, atualizar o registro do tomador
            $this->Tomador_model->update($tomador['id'], [
                'razao_social' => $tomador_nome_form,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            // Atualizar o objeto local para refletir a mudança
            $tomador['razao_social'] = $tomador_nome_form;
        }
        
        // Atualizar dados do inquilino na base se houve alteração nos campos manuais
        if (!empty($inquilino_nome) && $inquilino && $inquilino['nome'] != $inquilino_nome) {
            // O nome foi modificado no formulário, atualizar o registro do inquilino
            $this->Inquilino_model->update($inquilino['id'], [
                'nome' => $inquilino_nome,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            // Atualizar o objeto local para refletir a mudança
            $inquilino['nome'] = $inquilino_nome;
        }
        
        // Verificar se o CPF/CNPJ do tomador é igual ao do inquilino
        // Obter os valores atualizados dos formulários
        $tomador_cpf_form = preg_replace('/[^0-9]/', '', $this->input->post('tomador_cpf_cnpj'));
        
        // Status padrão após atualização
        $status = 'atualizado';
        $observacoes = '';
        
        // Verifica se os CPFs são iguais - regra simples
        if ($tomador_cpf_form === $inquilino_cpf_cnpj) {
            $status = 'revisar';
            $observacoes = 'ATENÇÃO: O CPF/CNPJ do tomador é igual ao do inquilino.';
        }
        
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
        
        // Dados a serem atualizados (apenas colunas que existem na tabela)
        $dados = [
            'numero' => $this->input->post('numero'),
            'codigo_verificacao' => $this->input->post('codigo_verificacao'),
            'data_emissao' => $this->input->post('data_emissao'),
            'competencia' => $competencia, // Usar a competência formatada corretamente
            'prestador_id' => $this->input->post('prestador_id'),
            'tomador_id' => $this->input->post('tomador_id'),
            'imovel_id' => $this->input->post('imovel_id'),
            'valor_servicos' => $valor_servicos,
            'valor_liquido' => $valor_servicos, // Garantir que valor_liquido seja sempre igual a valor_servicos
            'discriminacao' => $this->input->post('discriminacao'),
            'descricao_servico' => $this->input->post('descricao_servico'),
            'status' => $status,
            'observacoes' => $observacoes,
            'dimob_enviado' => $this->input->post('dimob_enviado'),
            'editado_manualmente' => 1, // Marcar como editado manualmente
            'atualizado_em' => date('Y-m-d H:i:s')
        ];
        
        // Debug do valor antes da inserção/atualização
        log_message('debug', 'Dimob: Valor do serviço EXATO a ser inserido no banco: ' . $valor_servicos);
        log_message('debug', 'Dimob: Valor do aluguel EXATO a ser inserido no banco: ' . $valor_aluguel);
        
        // Se temos um inquilino_id válido, adicionar à nota
        if (!empty($inquilino_id)) {
            $dados['inquilino_id'] = $inquilino_id;
        } else {
            // Se não temos inquilino, definir como NULL ao invés de string vazia
            $dados['inquilino_id'] = NULL;
        }
        
        // Adicionar o ID do usuário atual como atualizado_por apenas se estiver logado
        if ($this->session->userdata('user_id')) {
            $dados['atualizado_por'] = $this->session->userdata('user_id');
        } else {
            // Se não houver ID de usuário, definir como NULL para não violar chave estrangeira
            $dados['atualizado_por'] = NULL;
        }
        
        // Atualizar a nota - usar query direta para garantir precisão exata
        $nota_id_safe = (int)$nota_id; // Garantir que é um inteiro seguro
        $valor_servicos_safe = number_format($valor_servicos, 2, '.', ''); // Formato decimal SQL
        
        $query = "UPDATE notas SET 
                  valor_servicos = {$valor_servicos_safe}, 
                  valor_liquido = {$valor_servicos_safe} 
                  WHERE id = {$nota_id_safe}";
                  
        $this->db->query($query);
        
        // Continuar com o resto da atualização usando o método normal
        $dados_sem_valores = $dados;
        unset($dados_sem_valores['valor_servicos']); // Já atualizado diretamente
        unset($dados_sem_valores['valor_liquido']); // Já atualizado diretamente
        
        $sucesso = $this->Nota_model->update($nota_id, $dados_sem_valores);
        
        // Atualizar o imóvel com o valor do aluguel, tipo e inquilino_id
        $imovel_id = $this->input->post('imovel_id');
        
        if ($imovel_id) {
            // Verificar se o valor do aluguel está correto antes de atualizar
            $imovel_atual = $this->Imovel_model->get_by_id($imovel_id);
            
            // Usar query direta para garantir precisão exata dos valores decimais
            $imovel_id_safe = (int)$imovel_id; // Garantir que é um inteiro seguro
            $valor_aluguel_safe = number_format($valor_aluguel, 2, '.', ''); // Formato decimal SQL
            $tipo_imovel = $this->db->escape($this->input->post('tipo_imovel')); // Escape para string
            $data_atual = date('Y-m-d H:i:s');
            
            // Adicionar o inquilino_id na atualização do imóvel
            $inquilino_id_safe = !empty($inquilino_id) ? (int)$inquilino_id : 'NULL';
            
            $query = "UPDATE imoveis SET 
                      valor_aluguel = {$valor_aluguel_safe}, 
                      tipo_imovel = {$tipo_imovel},
                      inquilino_id = {$inquilino_id_safe},
                      updated_at = '{$data_atual}'
                      WHERE id = {$imovel_id_safe}";
                      
            // Debug - mostrar os valores que serão salvos no imóvel
            log_message('debug', 'Dimob: Atualizando imóvel #' . $imovel_id . ' com valor_aluguel=' . $valor_aluguel_safe . ' e inquilino_id=' . $inquilino_id_safe);
            log_message('debug', 'Dimob: Query de atualização do imóvel: ' . $query);
            
            $this->db->query($query);
        }
        
        // Atualizar os dados adicionais nas entidades relacionadas se o inquilino já existia
        // e os dados foram modificados no formulário
        if (!empty($inquilino_id) && $inquilino) {
            $dados_inquilino = [
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $atualizar_inquilino = false;
            
            // Só atualizar o CPF se foi modificado
            if (!empty($inquilino_cpf_cnpj) && $inquilino['cpf_cnpj'] != $inquilino_cpf_cnpj) {
                $dados_inquilino['cpf_cnpj'] = $inquilino_cpf_cnpj;
                $atualizar_inquilino = true;
            }
            
            // Atualizar telefone se foi fornecido e é diferente do atual
            if (!empty($inquilino_telefone) && $inquilino['telefone'] != $inquilino_telefone) {
                $dados_inquilino['telefone'] = $inquilino_telefone;
                $atualizar_inquilino = true;
            }
            
            // Atualizar email se foi fornecido e é diferente do atual
            if (!empty($inquilino_email) && $inquilino['email'] != $inquilino_email) {
                $dados_inquilino['email'] = $inquilino_email;
                $atualizar_inquilino = true;
            }
            
            // Só executar a atualização se algo mudou
            if ($atualizar_inquilino) {
                $this->Inquilino_model->update($inquilino_id, $dados_inquilino);
            }
        }
        
        // Atualizar dados do tomador, se necessário
        $tomador_id = $this->input->post('tomador_id');
        if (!empty($tomador_id) && $tomador) {
            $tomador_cpf_cnpj = preg_replace('/[^0-9]/', '', $this->input->post('tomador_cpf_cnpj'));
            
            if (!empty($tomador_cpf_cnpj) && $tomador['cpf_cnpj'] != $tomador_cpf_cnpj) {
                $dados_tomador = [
                    'cpf_cnpj' => $tomador_cpf_cnpj,
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                
                $this->Tomador_model->update($tomador_id, $dados_tomador);
            }
        }
        
        // Atualizar a nota
        $sucesso = $this->Nota_model->update($nota_id, $dados);
        
        // Verificar sucesso geral da operação
        if ($sucesso) {
            // Registrar log de atualização
            if (method_exists($this, 'log_activity')) {
                $this->log_activity(
                    'update',
                    'notas',
                    $nota_id,
                    'Nota fiscal #' . $nota['numero'] . ' atualizada pelo módulo DIMOB'
                );
            }
            
            // Criar mensagem de sucesso detalhada para ajudar no debug
            $msg_sucesso = 'Nota fiscal atualizada com sucesso' . 
                         (!empty($inquilino_id) && empty($nota['inquilino_id']) ? ' e inquilino cadastrado' : '');
                         
            // Adicionar informações dos valores para debug
            if (ENVIRONMENT === 'development') {
                $msg_sucesso .= '. Valores salvos: Serviço=' . number_format($valor_servicos, 2, ',', '.') . 
                              ', Aluguel=' . number_format($valor_aluguel, 2, ',', '.');
            }
            
            $this->session->set_flashdata('success', $msg_sucesso);
        } else {
            $this->session->set_flashdata('error', 'Erro ao atualizar nota fiscal');
        }
        
        // Redirecionar para a lista de notas usando site_url para garantir a URL completa
        redirect(site_url('dimob/listar?ano=' . $ano));
    }
    
    /**
     * Obtém os anos disponíveis para geração do DIMOB com base nas notas fiscais
     */
    private function get_anos_disponiveis() {
        $anos = [];
        $ano_atual = date('Y');
        
        // Adicionar os últimos 5 anos
        for ($i = 0; $i <= 5; $i++) {
            $anos[] = $ano_atual - $i;
        }
        
        return $anos;
    }
    
    /**
     * Calcula os totais mensais de aluguel e comissão para o resumo
     */
    /**
     * Verifica inconsistências nos dados, como CPF/CNPJ igual entre tomador e inquilino
     * 
     * @param array $notas Notas fiscais a serem verificadas
     * @return array Array com inconsistências encontradas
     */
    private function verificar_inconsistencias($notas) {
        $inconsistencias = [];
        
        foreach ($notas as $index => $nota) {
            // Verificar se o tomador e inquilino têm exatamente o mesmo CPF/CNPJ
            if (!empty($nota['inquilino_cpf_cnpj']) && !empty($nota['tomador_cpf_cnpj'])) {
                
                // Limpar CPF/CNPJ para comparação (apenas números)
                $inquilino_cpf_cnpj = preg_replace('/[^0-9]/', '', $nota['inquilino_cpf_cnpj']);
                $tomador_cpf_cnpj = preg_replace('/[^0-9]/', '', $nota['tomador_cpf_cnpj']);
                
                // Comparar CPFs/CNPJs - eles são exatamente iguais?
                if ($inquilino_cpf_cnpj === $tomador_cpf_cnpj) {
                    $inconsistencias[$nota['id']] = [
                        'tipo' => 'cpf_igual',
                        'mensagem' => 'O CPF/CNPJ do tomador é igual ao do inquilino.',
                        'inquilino_cpf_cnpj' => $nota['inquilino_cpf_cnpj'],
                        'tomador_cpf_cnpj' => $nota['tomador_cpf_cnpj'],
                        'inquilino_nome' => $nota['inquilino_nome'],
                        'tomador_nome' => $nota['tomador_nome']
                    ];
                }
            }
        }
        
        return $inconsistencias;
    }
    
    private function calcular_totais_mensais($notas) {
        $totais = [];
        
        // Inicializar array de totais para todos os meses
        for ($mes = 1; $mes <= 12; $mes++) {
            $totais[$mes] = [
                'aluguel' => 0,
                'comissao' => 0,
                'count' => 0
            ];
        }
        
        // Calcular totais
        foreach ($notas as $nota) {
            $mes = date('n', strtotime($nota['competencia']));
            $totais[$mes]['aluguel'] += floatval($nota['valor_aluguel']);
            $totais[$mes]['comissao'] += floatval($nota['valor_servicos']);
            $totais[$mes]['count']++;
        }
        
        return $totais;
    }
}
