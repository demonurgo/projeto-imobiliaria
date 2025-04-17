<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notas extends CI_Controller {
    
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
        
        // Carregar bibliotecas e helpers
        $this->load->helper(array('form', 'url', 'date'));
        $this->load->library(array('form_validation', 'session'));
    }
    
    public function index() {
        $data['title'] = 'Notas Fiscais';
        $data['active'] = 'notas';
        $data['notas'] = $this->Nota_model->get_all();
        
        $this->load->view('templates/header', $data);
        $this->load->view('notas/index', $data);
        $this->load->view('templates/footer');
    }
    
    public function view($id) {
        $nota = $this->Nota_model->get_by_id($id);
        
        if (!$nota) {
            $this->session->set_flashdata('error', 'Nota fiscal não encontrada.');
            redirect('notas');
        }
        
        $data['title'] = 'Detalhes da Nota Fiscal';
        $data['active'] = 'notas';
        $data['nota'] = $nota;
        
        $this->load->view('templates/header', $data);
        $this->load->view('notas/view', $data);
        $this->load->view('templates/footer');
    }
    
    public function edit($id) {
        $nota = $this->Nota_model->get_by_id($id);
        
        if (!$nota) {
            $this->session->set_flashdata('error', 'Nota fiscal não encontrada.');
            redirect('notas');
        }
        
        // Recuperar dados do inquilino, se existir
        if (!empty($nota['inquilino_id'])) {
            $inquilino = $this->Inquilino_model->get_by_id($nota['inquilino_id']);
            if ($inquilino) {
                $nota['inquilino_nome'] = $inquilino['nome'];
                $nota['inquilino_cpf_cnpj'] = isset($inquilino['cpf_cnpj']) ? $inquilino['cpf_cnpj'] : $inquilino['cpf'];
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
        $data['active'] = 'notas';
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
            $this->load->view('notas/edit', $data);
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
                'editado_manualmente' => 1  // Marca como editado manualmente
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
                redirect('notas');
            } else {
                $this->session->set_flashdata('error', 'Erro ao atualizar nota fiscal.');
                $this->load->view('templates/header', $data);
                $this->load->view('notas/edit', $data);
                $this->load->view('templates/footer');
            }
        }
    }
    
    public function visualizar($id) {
        // Alias para o método view
        return $this->view($id);
    }
    
    public function listar_por_batch($batch_id) {
        if (!$batch_id) {
            $this->session->set_flashdata('error', 'Batch ID não fornecido.');
            redirect('logs/importacao');
        }
        
        // Carregar notas do batch específico
        $notas = $this->Nota_model->get_by_batch($batch_id);
        
        $data['title'] = 'Notas do Lote #' . $batch_id;
        $data['active'] = 'notas';
        $data['notas'] = $notas;
        $data['batch_id'] = $batch_id;
        
        $this->load->view('templates/header', $data);
        $this->load->view('notas/batch', $data);
        $this->load->view('templates/footer');
    }
    
    public function delete($id) {
        $nota = $this->Nota_model->get_by_id($id);
        
        if (!$nota) {
            $this->session->set_flashdata('error', 'Nota fiscal não encontrada.');
            redirect('notas');
        }
        
        if ($this->Nota_model->delete($id)) {
            $this->session->set_flashdata('success', 'Nota fiscal excluída com sucesso.');
        } else {
            $this->session->set_flashdata('error', 'Erro ao excluir nota fiscal.');
        }
        
        redirect('notas');
    }
    
    public function filter() {
        // Verificar se é uma requisição AJAX
        // Comentando temporariamente para fins de debug
        // if (!$this->input->is_ajax_request()) {
        //     show_error('Acesso direto não permitido');
        //     return;
        // }
        
        // Receber parâmetros de filtro
        $filters = array();
        
        // Debug - salvar parametros recebidos
        log_message('debug', 'Filtro: Parametros recebidos - ' . json_encode($_POST));
        
        // Filtros por campo
        if ($this->input->post('numero')) {
            $filters['numero'] = $this->input->post('numero');
        }
        
        if ($this->input->post('data_emissao')) {
            $filters['data_emissao'] = $this->input->post('data_emissao');
        }
        
        if ($this->input->post('prestador')) {
            $filters['prestador'] = $this->input->post('prestador');
        }
        
        if ($this->input->post('tomador')) {
            $filters['tomador'] = $this->input->post('tomador');
        }
        
        if ($this->input->post('valor')) {
            $filters['valor_servicos'] = $this->input->post('valor');
        }
        
        if ($this->input->post('inquilino')) {
            $filters['inquilino'] = $this->input->post('inquilino');
        }
        
        if ($this->input->post('status')) {
            $status = $this->input->post('status');
            // Não utilizamos mais o status 'atualizado', utilizamos o campo editado_manualmente
            if ($status !== 'atualizado') {
                $filters['status'] = $status;
            } else {
                $filters['editado_manualmente'] = 1;
            }
        }
        
        // Debug - filtros construídos
        log_message('debug', 'Filtro: Filtros processados - ' . json_encode($filters));
        
        // Obter notas filtradas do modelo
        $notas = $this->Nota_model->get_filtered($filters);
        
        // Debug - resultados obtidos
        log_message('debug', 'Filtro: Total de resultados - ' . count($notas));
        
        // Verificar se é uma solicitação AJAX ou normal
        if ($this->input->is_ajax_request()) {
            // Retornar como JSON se for AJAX
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(array('data' => $notas)));
        } else {
            // Retornar como HTML se for acesso direto
            $data['title'] = 'Resultados do Filtro';
            $data['active'] = 'notas';
            $data['notas'] = $notas;
            $data['filtros'] = $filters;
            
            $this->load->view('templates/header', $data);
            
            // Exibir filtros aplicados
            echo '<div class="container mt-4">';
            echo '<div class="card mb-4">';
            echo '<div class="card-header bg-primary text-white">Filtros Aplicados</div>';
            echo '<div class="card-body">';
            echo '<ul>';
            foreach ($filters as $key => $value) {
                echo '<li><strong>' . ucfirst(str_replace('_', ' ', $key)) . ':</strong> ' . $value . '</li>';
            }
            echo '</ul>';
            echo '<a href="' . base_url('notas') . '" class="btn btn-outline-secondary">Voltar</a>';
            echo '</div></div>';
            
            // Exibir resultados
            echo '<div class="card">';
            echo '<div class="card-header bg-primary text-white">Resultados (' . count($notas) . ')</div>';
            echo '<div class="card-body">';
            
            if (empty($notas)) {
                echo '<div class="alert alert-info">Nenhuma nota fiscal encontrada com os filtros especificados.</div>';
            } else {
                echo '<div class="table-responsive">';
                echo '<table class="table table-striped table-hover" id="notasTable">';
                echo '<thead class="table-light">';
                echo '<tr>';
                echo '<th>Número</th>';
                echo '<th>Data Emissão</th>';
                echo '<th>Prestador</th>';
                echo '<th>Tomador</th>';
                echo '<th>Valor (R$)</th>';
                echo '<th>Inquilino</th>';
                echo '<th>DIMOB</th>';
                echo '<th>Status</th>';
                echo '<th>Ações</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                
                foreach ($notas as $nota) {
                    echo '<tr>';
                    echo '<td>' . $nota['numero'] . '</td>';
                    echo '<td>' . date('d/m/Y H:i', strtotime($nota['data_emissao'])) . '</td>';
                    echo '<td>' . $nota['prestador_nome'] . '</td>';
                    echo '<td>' . $nota['tomador_nome'] . '</td>';
                    echo '<td class="text-end">' . number_format($nota['valor_servicos'], 2, ',', '.') . '</td>';
                    
                    // Inquilino
                    echo '<td>';
                    if ($nota['inquilino_id'] && isset($nota['inquilino_nome'])) {
                        echo $nota['inquilino_nome'];
                    } else {
                        echo '<span class="badge bg-warning text-dark">Não identificado</span>';
                    }
                    echo '</td>';
                    
                    // DIMOB Status
                    echo '<td>';
                    if ($nota['dimob_enviado']) {
                        echo '<span class="badge bg-success">Incluído</span>';
                    } else {
                        echo '<span class="badge bg-secondary">Pendente</span>';
                    }
                    echo '</td>';
                    
                    // Status
                    echo '<td>';
                    switch ($nota['status']) {
                        case 'importado':
                            echo '<span class="badge bg-info">Importado</span>';
                            break;
                        case 'processado':
                            echo '<span class="badge bg-success">Processado</span>';
                            break;
                        case 'atualizado':
                            echo '<span class="badge bg-primary">Atualizado</span>';
                            break;
                        case 'cancelado':
                            echo '<span class="badge bg-danger">Cancelado</span>';
                            break;
                        default:
                            echo '<span class="badge bg-secondary">Desconhecido</span>';
                    }
                    echo '</td>';
                    
                    // Ações
                    echo '<td>';
                    echo '<div class="btn-group btn-group-sm" role="group">';
                    echo '<a href="' . base_url('notas/view/' . $nota['id']) . '" class="btn btn-info" title="Visualizar"><i class="fas fa-eye"></i></a>';
                    echo '<a href="' . base_url('notas/edit/' . $nota['id']) . '" class="btn btn-primary" title="Editar"><i class="fas fa-edit"></i></a>';
                    
                    // Botão DIMOB
                    if (!$nota['dimob_enviado']) {
                        echo '<a href="' . base_url('notas/dimob/' . $nota['id'] . '/1') . '" class="btn btn-success" title="Marcar para DIMOB"><i class="fas fa-check"></i></a>';
                    } else {
                        echo '<a href="' . base_url('notas/dimob/' . $nota['id'] . '/0') . '" class="btn btn-warning" title="Remover da DIMOB"><i class="fas fa-times"></i></a>';
                    }
                    
                    // Botão Exclusão
                    echo '<a href="' . base_url('notas/delete/' . $nota['id']) . '" class="btn btn-danger" title="Excluir" onclick="return confirm(\'Tem certeza que deseja excluir esta nota fiscal?\');"><i class="fas fa-trash"></i></a>';
                    echo '</div>';
                    echo '</td>';
                    
                    echo '</tr>';
                }
                
                echo '</tbody>';
                echo '</table>';
                echo '</div>';
            }
            
            echo '</div>';
            echo '</div>';
            echo '</div>';
            
            $this->load->view('templates/footer');
        }
    }
    
    public function teste_filtro() {
        // Método simplificado para teste de filtro
        // Este método é acessível diretamente pela URL
        
        // Saída em JSON para verificação
        echo '<pre>';
        echo 'Testando função de filtro diretamente:<br>';
        echo 'POST data: ';
        print_r($_POST);
        echo '<br><br>GET data: ';
        print_r($_GET);
        
        // Trazendo todas as notas como exemplo
        $notas = $this->Nota_model->get_all();
        echo '<br><br>Total de notas encontradas: ' . count($notas) . '<br>';
        echo 'Primeira nota: ';
        if (!empty($notas)) {
            print_r($notas[0]);
        } else {
            echo 'Nenhuma nota encontrada!';
        }
        
        echo '</pre>';
    }
    
    public function dimob($id, $status) {
        $nota = $this->Nota_model->get_by_id($id);
        
        if (!$nota) {
            $this->session->set_flashdata('error', 'Nota fiscal não encontrada.');
            redirect('notas');
        }
        
        // Atualizar status DIMOB
        if ($this->Nota_model->update($id, ['dimob_enviado' => $status])) {
            $this->session->set_flashdata('success', 'Status DIMOB atualizado com sucesso.');
        } else {
            $this->session->set_flashdata('error', 'Erro ao atualizar status DIMOB.');
        }
        
        // Redirecionar de volta para a lista
        redirect('notas');
    }
}
