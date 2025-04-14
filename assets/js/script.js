/**
 * Custom scripts for NFSe-DIMOB System
 */

// Document Ready Function
$(document).ready(function() {
    
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
    
    // Initialize popovers
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl)
    });
    
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert-dismissible').fadeOut('slow');
    }, 5000);
    
    // Confirm deletion
    $('.btn-delete').on('click', function(e) {
        if (!confirm('Tem certeza que deseja excluir este item?')) {
            e.preventDefault();
        }
    });
    
    // File input change - show filename
    $('.custom-file-input').on('change', function() {
        var fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName);
    });
    
    // DIMOB form validation
    $('#dimob-form').on('submit', function(e) {
        var year = $('#ano_referencia').val();
        
        if (!year || year.length != 4) {
            alert('Por favor, informe um ano de referência válido com 4 dígitos.');
            e.preventDefault();
            return false;
        }
    });
    
    // Mask for Brazilian CPF/CNPJ
    if ($.fn.mask) {
        $('.cpf-mask').mask('000.000.000-00');
        $('.cnpj-mask').mask('00.000.000/0000-00');
        $('.phone-mask').mask('(00) 00000-0000');
        $('.cep-mask').mask('00000-000');
    }
    
    // Toggle sidebar on mobile
    $('#sidebarToggle').on('click', function() {
        $('body').toggleClass('sidebar-toggled');
        $('.sidebar').toggleClass('toggled');
    });
    
    // Close any open menu when window is resized
    $(window).resize(function() {
        if ($(window).width() < 768) {
            $('.sidebar').addClass('toggled');
        }
    });
});
