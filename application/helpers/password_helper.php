<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Verifica se a função password_verify() existe, e se não, implementa uma versão compatível
 */
if (!function_exists('password_verify')) {
    /**
     * Verifica se uma senha corresponde a um hash
     * 
     * @param string $password A senha em texto plano
     * @param string $hash O hash armazenado no banco
     * @return bool
     */
    function password_verify($password, $hash) {
        if (substr($hash, 0, 4) == '$2y$' && strlen($hash) == 60) {
            $rehashed = crypt($password, $hash);
            return $rehashed === $hash;
        }
        return false;
    }
}

/**
 * Verifica se a função password_hash() existe, e se não, implementa uma versão compatível
 */
if (!function_exists('password_hash')) {
    /**
     * Cria um hash bcrypt de uma senha
     * 
     * @param string $password A senha em texto plano
     * @param int $algo Ignorado, apenas para compatibilidade
     * @param array $options Opções para o algoritmo de hash
     * @return string O hash gerado
     */
    function password_hash($password, $algo, array $options = array()) {
        $cost = isset($options['cost']) ? (int) $options['cost'] : 10;
        
        // Verifica se o custo está entre 4 e 31
        $cost = ($cost < 4 || $cost > 31) ? 10 : $cost;
        
        // Cria um salt aleatório
        $salt = substr(str_replace('+', '.', base64_encode(random_bytes(16))), 0, 22);
        
        // Cria o formato do salt bcrypt
        $salt = sprintf('$2y$%02d$%s', $cost, $salt);
        
        // Hash a senha usando o salt
        $hash = crypt($password, $salt);
        
        return $hash;
    }
}

/**
 * Função para validar a força da senha
 */
if (!function_exists('password_strength')) {
    /**
     * Verifica a força de uma senha
     * 
     * @param string $password A senha em texto plano
     * @return bool|string Verdadeiro se for forte, ou mensagem de erro
     */
    function password_strength($password) {
        // Mínimo de 8 caracteres
        if (strlen($password) < 8) {
            return 'A senha deve ter pelo menos 8 caracteres';
        }
        
        // Deve ter pelo menos um número
        if (!preg_match('/[0-9]/', $password)) {
            return 'A senha deve conter pelo menos um número';
        }
        
        // Deve ter pelo menos uma letra maiúscula
        if (!preg_match('/[A-Z]/', $password)) {
            return 'A senha deve conter pelo menos uma letra maiúscula';
        }
        
        // Deve ter pelo menos uma letra minúscula
        if (!preg_match('/[a-z]/', $password)) {
            return 'A senha deve conter pelo menos uma letra minúscula';
        }
        
        return true;
    }
}
