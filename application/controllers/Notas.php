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
            // Verificar se foi feito cadastro manual de inquilino
            $inquilino_id = $this->input->post('inquilino_id');
            
            // Se não foi selecionado um inquilino existente, mas temos dados manuais, criar novo inquilino
            if (empty($inquilino_id) && !empty($this->input->post('inquilino_nome')) && !empty($this->input->post('inquilino_documento'))) {
                $inquilino_data = array(
                    'nome' => $this->input->post('inquilino_nome')
                );
                
                // Definir o tipo de documento (CPF ou CNPJ)
                if ($this->input->post('inquilino_tipo_documento') == 'cpf') {
                    $inquilino_data['cpf'] = $this->input->post('inquilino_documento');
                } else {
                    $inquilino_data['cnpj_inquilino'] = $this->input->post('inquilino_documento');
                }
                
                // Salvar o novo inquilino
                $inquilino_id = $this->Inquilino_model->save($inquilino_data);
            }
            
            // Verificar se precisamos criar ou atualizar um imóvel com valor de aluguel
            $imovel_id = $this->input->post('imovel_id');
            $valor_aluguel = $this->input->post('valor_aluguel');
            
            // Se temos um imóvel selecionado e um valor de aluguel, atualizar o imóvel
            if (!empty($imovel_id) && !empty($valor_aluguel)) {
                $this->Imovel_model->update($imovel_id, array('valor_aluguel' => $valor_aluguel));
            }
            // Se não temos imóvel selecionado mas temos inquilino e discriminação, criar novo imóvel
            elseif (empty($imovel_id) && !empty($inquilino_id) && !empty($valor_aluguel)) {
                // Extrair possível endereço da discriminação
                $discriminacao = $this->input->post('discriminacao');
                $partes = explode("\n", $discriminacao);
                $endereco_imovel = (count($partes) >= 2) ? trim($partes[1]) : 'Endereço não especificado';
                
                $imovel_data = array(
                    'endereco' => $endereco_imovel,
                    'inquilino_id' => $inquilino_id,
                    'valor_aluguel' => $valor_aluguel
                );
                
                $imovel_id = $this->Imovel_model->save($imovel_data);
            }
            
            $update_data = array(
                'numero' => $this->input->post('numero'),
                'codigo_verificacao' => $this->input->post('codigo_verificacao'),
                'data_emissao' => $this->input->post('data_emissao'),
                'competencia' => $this->input->post('competencia'),
                'valor_servicos' => $this->input->post('valor_servicos'),
                'valor_iss' => $this->input->post('valor_iss'),
                'base_calculo' => $this->input->post('base_calculo'),
                'aliquota' => $this->input->post('aliquota'),
                'valor_liquido' => $this->input->post('valor_liquido'),
                'discriminacao' => $this->input->post('discriminacao'),
                'descricao_servico' => $this->input->post('descricao_servico'),
                'prestador_id' => $this->input->post('prestador_id'),
                'tomador_id' => $this->input->post('tomador_id'),
                'inquilino_id' => $inquilino_id,
                'imovel_id' => $imovel_id,
                'editado_manualmente' => 1
            );
            
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
}
