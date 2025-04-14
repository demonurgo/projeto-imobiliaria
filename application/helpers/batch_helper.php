<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Helper com funções para operações em lote
 */

/**
 * Gera o HTML para colunas de checkbox que podem ser usadas em tabelas CRUD
 * 
 * @param int $id ID do registro
 * @return string HTML da célula com checkbox
 */
function batch_checkbox_cell($id) {
    return '<div class="form-check">
                <input class="form-check-input row-checkbox" type="checkbox" name="selected_ids[]" value="' . $id . '">
            </div>';
}

/**
 * Gera o HTML para cabeçalho da coluna de checkbox
 * 
 * @return string HTML do cabeçalho com checkbox "Selecionar todos"
 */
function batch_checkbox_header() {
    return '<div class="form-check">
                <input class="form-check-input" type="checkbox" id="selectAll">
            </div>';
}

/**
 * Gera o HTML para o botão de exclusão em lote
 * 
 * @param string $text Texto do botão
 * @return string HTML do botão de exclusão em lote
 */
function batch_delete_button($text = 'Excluir Selecionados') {
    return '<button id="deleteSelectedBtn" class="btn btn-danger btn-sm ms-2" disabled>
                <i class="fas fa-trash"></i> ' . $text . '
            </button>';
}

/**
 * Gera o JavaScript para funcionalidade de exclusão em lote
 * 
 * @param string $form_id ID do formulário que contém os checkboxes
 * @param string $confirmation_message Mensagem de confirmação
 * @return string JavaScript para funcionalidade de exclusão em lote
 */
function batch_delete_js($form_id = 'batchActionForm', $confirmation_message = 'Tem certeza que deseja excluir os itens selecionados?') {
    return "
    <script>
    $(document).ready(function() {
        // Função para inicializar os eventos de checkbox
        function initCheckboxEvents() {
            // Selecionar/Deselecionar todos
            $('#selectAll').off('change').on('change', function() {
                $('.row-checkbox').prop('checked', $(this).prop('checked'));
                updateDeleteButton();
            });
            
            // Atualizar status do botão de exclusão quando muda qualquer checkbox
            $('.row-checkbox').off('change').on('change', function() {
                updateDeleteButton();
                
                // Se desmarcar qualquer checkbox, desmarcar o selectAll
                if (!$(this).prop('checked')) {
                    $('#selectAll').prop('checked', false);
                } else {
                    // Verificar se todos estão marcados
                    var allChecked = $('.row-checkbox:checked').length === $('.row-checkbox').length;
                    $('#selectAll').prop('checked', allChecked);
                }
            });
        }
        
        // Inicializar eventos de checkbox
        initCheckboxEvents();
        
        // Reinicializar eventos quando o DataTable é desenhado
        // Isso é necessário porque o DataTable pode recriar a tabela
        $('table.dataTable').on('draw.dt', function() {
            initCheckboxEvents();
            updateDeleteButton();
        });
        
        // Confirmação de exclusão em lote
        $('#deleteSelectedBtn').off('click').on('click', function(e) {
            e.preventDefault();
            
            var count = $('.row-checkbox:checked').length;
            if (count > 0) {
                if (confirm('" . $confirmation_message . " (' + count + ')')) {
                    $('#" . $form_id . "').submit();
                }
            }
        });
        
        // Função para atualizar status do botão de exclusão
        function updateDeleteButton() {
            var count = $('.row-checkbox:checked').length;
            $('#deleteSelectedBtn').prop('disabled', count === 0);
            
            if (count > 0) {
                $('#deleteSelectedBtn').text('Excluir (' + count + ') Selecionados');
            } else {
                $('#deleteSelectedBtn').text('Excluir Selecionados');
            }
        }
    });
    </script>";
}
