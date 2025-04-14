# Funcionalidade de Exclusão em Lote

## Visão Geral

A funcionalidade de exclusão em lote permite selecionar múltiplos registros através de checkboxes para excluí-los de uma só vez. Esta implementação inclui verificação de relacionamentos para evitar a exclusão de registros que possuem dependências.

## Componentes Implementados

### 1. Modelo Base `MY_Model`

O arquivo `application/core/MY_Model.php` implementa uma classe base que estende `CI_Model` e adiciona funcionalidades de CRUD e operações em lote:

- `delete_batch()`: Exclui múltiplos registros de uma vez
- `check_batch_relations()`: Verifica se os registros possuem relacionamentos que impediriam sua exclusão
- `can_delete()`: Verifica se um único registro pode ser excluído

### 2. Helper de Batch Operations

O arquivo `application/helpers/batch_helper.php` contém funções úteis para implementar a interface de usuário:

- `batch_checkbox_header()`: Gera o HTML do cabeçalho da coluna de checkboxes com a opção "Selecionar todos"
- `batch_checkbox_cell()`: Gera o HTML da célula com checkbox para um registro específico
- `batch_delete_button()`: Gera o botão de exclusão em lote
- `batch_delete_js()`: Gera o JavaScript necessário para a funcionalidade de seleção e exclusão em lote

### 3. Controllers

Cada controller que implementa a exclusão em lote possui:

- Um método `delete_batch()` que processa o formulário de exclusão em lote
- Verificação das dependências de cada registro selecionado
- Tratamento para excluir apenas os registros que não possuem dependências
- Mensagens informativas sobre os registros que não puderam ser excluídos

### 4. Views

As views que implementam a exclusão em lote possuem:

- Um formulário envolvendo a tabela de dados
- Uma coluna adicional com checkboxes para seleção
- Um botão de exclusão em lote que fica desabilitado até que pelo menos um registro seja selecionado
- Configuração do DataTables para tratar corretamente a nova coluna de checkboxes
- JavaScript para atualizar a interface do usuário conforme os checkboxes são selecionados

## Como Implementar em Novos Módulos

Para adicionar a funcionalidade de exclusão em lote em um novo módulo, siga os passos abaixo:

### 1. Atualizar o Modelo

Estenda o modelo a partir de `MY_Model` em vez de `CI_Model`:

```php
class MeuModelo_model extends MY_Model {
    protected $table = 'minha_tabela';
    protected $primary_key = 'id';
    protected $fillable = array(
        'campo1', 'campo2', 'campo3'
    );
    protected $relations = array(
        'tabela_relacionada1' => 'campo_foreign_key1',
        'tabela_relacionada2' => 'campo_foreign_key2'
    );
    
    // Se necessário, sobrescreva o método can_delete() para verificações
    // específicas de relações deste modelo
}
```

### 2. Adicionar o Método no Controller

Adicione o método `delete_batch()` ao controller:

```php
public function delete_batch() {
    // Receber os IDs dos registros selecionados
    $selected_ids = $this->input->post('selected_ids');
    
    if (empty($selected_ids)) {
        $this->session->set_flashdata('error', 'Nenhum registro selecionado.');
        redirect('nome_do_modulo');
    }
    
    // Verificar quais IDs têm associações
    $locked_ids = $this->MeuModelo_model->check_batch_relations($selected_ids);
    
    if (!empty($locked_ids)) {
        // Filtrar os IDs que podem ser excluídos
        $deletable_ids = array_diff($selected_ids, $locked_ids);
        
        // Buscar nomes dos registros bloqueados para exibir na mensagem
        $this->db->select('id, nome_ou_campo_identificador');
        $this->db->where_in('id', $locked_ids);
        $locked_records = $this->db->get('minha_tabela')->result_array();
        
        $locked_names = array_column($locked_records, 'nome_ou_campo_identificador');
        $locked_message = 'Os seguintes registros não puderam ser excluídos por possuírem associações: ' . implode(', ', $locked_names);
        
        // Se ainda existem IDs que podem ser excluídos
        if (!empty($deletable_ids)) {
            if ($this->MeuModelo_model->delete_batch($deletable_ids)) {
                $count = count($deletable_ids);
                $this->session->set_flashdata('success', $count . ' registro(s) excluído(s) com sucesso. ' . $locked_message);
            } else {
                $this->session->set_flashdata('error', 'Erro ao excluir registros. ' . $locked_message);
            }
        } else {
            $this->session->set_flashdata('error', $locked_message);
        }
    } else {
        // Todos os IDs selecionados podem ser excluídos
        if ($this->MeuModelo_model->delete_batch($selected_ids)) {
            $count = count($selected_ids);
            $this->session->set_flashdata('success', $count . ' registro(s) excluído(s) com sucesso.');
        } else {
            $this->session->set_flashdata('error', 'Erro ao excluir registros.');
        }
    }
    
    redirect('nome_do_modulo');
}
```

### 3. Atualizar a View

Modifique o arquivo `index.php` da view do módulo:

```php
<div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
    <h5 class="card-title mb-0"><i class="fas fa-icon"></i> Título do Módulo</h5>
    <div>
        <a href="<?= base_url('nome_do_modulo/create') ?>" class="btn btn-light btn-sm">
            <i class="fas fa-plus"></i> Novo Registro
        </a>
        <?= batch_delete_button() ?>
    </div>
</div>

<div class="table-responsive">
    <form id="batchActionForm" action="<?= base_url('nome_do_modulo/delete_batch') ?>" method="post">
    <table class="table table-striped table-hover" id="minhaTabelaId">
        <thead class="table-light">
            <tr>
                <th><?= batch_checkbox_header() ?></th>
                <!-- ... outras colunas ... -->
            </tr>
        </thead>
        <tbody>
            <?php foreach($registros as $registro): ?>
            <tr>
                <td><?= batch_checkbox_cell($registro['id']) ?></td>
                <!-- ... outras células ... -->
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </form>
</div>

<!-- No final do arquivo, após inicializar o DataTable -->
<?= batch_delete_js('batchActionForm', 'Tem certeza que deseja excluir os registros selecionados?') ?>
```

## Considerações

- Certifique-se de que a propriedade `relations` do modelo contenha todas as tabelas relacionadas para evitar a exclusão de registros que possuem dependências.
- Personalize as mensagens de confirmação e erro conforme necessário para cada módulo.
- O método `can_delete()` pode ser sobrescrito em cada modelo para implementar verificações específicas de dependências.
- Para ordenação correta no DataTables, ajuste o índice da coluna considerando que agora a primeira coluna (índice 0) é a dos checkboxes.
