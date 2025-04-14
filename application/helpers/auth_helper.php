<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('is_logged_in')) {
    function is_logged_in() {
        $CI =& get_instance();
        return $CI->session->userdata('logged_in') === TRUE;
    }
}

if (!function_exists('require_login')) {
    function require_login() {
        if (!is_logged_in()) {
            $CI =& get_instance();
            redirect('auth');
        }
    }
}

if (!function_exists('is_admin')) {
    function is_admin() {
        $CI =& get_instance();
        return ($CI->session->userdata('logged_in') === TRUE && 
                $CI->session->userdata('is_admin') == 1);
    }
}

if (!function_exists('require_admin')) {
    function require_admin() {
        if (!is_admin()) {
            $CI =& get_instance();
            if (is_logged_in()) {
                show_error('Você não tem permissão para acessar esta página', 403);
            } else {
                redirect('auth');
            }
        }
    }
}

if (!function_exists('get_current_user')) {
    function get_current_user() {
        $CI =& get_instance();
        if (is_logged_in() && $CI->session->userdata('user_id')) {
            $CI->load->model('user_model');
            $user = $CI->user_model->get($CI->session->userdata('user_id'));
            
            // Verificação adicional para debug
            if (!$user) {
                log_message('error', 'get_current_user: Usuário não encontrado para ID ' . $CI->session->userdata('user_id'));
            }
            
            return $user;
        }
        return null;
    }
}
