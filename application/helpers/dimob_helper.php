<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * DIMOB Helper Functions
 *
 * Funções auxiliares para geração de arquivos DIMOB
 */

/**
 * Formata um número para o formato DIMOB no padrão R$ (campo monetário)
 * De acordo com o layout, formato R$ é: 12 dígitos para parte inteira + 2 para parte decimal
 * 
 * @param float $value Valor a ser formatado
 * @param int $length Comprimento total do valor formatado (default 14, sendo 12+2)
 * @return string Valor formatado
 */
if (!function_exists('dimob_format_number')) {
    function dimob_format_number($value, $length = 14) {
        // Garantir que o valor seja numérico
        $value = (float)$value;
        
        // Separar parte inteira e decimal
        $parte_inteira = floor($value);
        $parte_decimal = round(($value - $parte_inteira) * 100);
        
        // Formatar parte inteira com zeros à esquerda (12 dígitos)
        $parte_inteira_formatada = str_pad($parte_inteira, $length - 2, '0', STR_PAD_LEFT);
        
        // Formatar parte decimal com zeros à direita (2 dígitos)
        $parte_decimal_formatada = str_pad($parte_decimal, 2, '0', STR_PAD_LEFT);
        
        // Juntar parte inteira e decimal
        return $parte_inteira_formatada . $parte_decimal_formatada;
    }
}

/**
 * Formata uma string para o formato DIMOB (preenchido com espaços à direita)
 * 
 * @param string $value Valor a ser formatado
 * @param int $length Comprimento total do valor formatado
 * @return string Valor formatado
 */
if (!function_exists('dimob_format_string')) {
    function dimob_format_string($value, $length) {
        // Remove acentos e caracteres especiais
        $value = remove_accents($value);
        
        // Limita ao comprimento máximo e preenche com espaços à direita
        return str_pad(substr($value, 0, $length), $length, ' ', STR_PAD_RIGHT);
    }
}

/**
 * Formata um CPF/CNPJ para o formato DIMOB
 * 
 * @param string $value CPF ou CNPJ a ser formatado
 * @param int $length Comprimento total do valor formatado
 * @return string Valor formatado
 */
if (!function_exists('dimob_format_document')) {
    function dimob_format_document($value, $length = 14) {
        // Remove caracteres não numéricos
        $value = preg_replace('/[^0-9]/', '', $value);
        
        // Preenche com zeros à esquerda
        // Para DIMOB, os documentos são alinhados à esquerda com zeros em espaços vazios
        return str_pad($value, $length, '0', STR_PAD_LEFT);
    }
}

/**
 * Formata uma data para o formato DIMOB (DDMMAAAA)
 * 
 * @param string $date Data no formato Y-m-d
 * @return string Data formatada
 */
if (!function_exists('dimob_format_date')) {
    function dimob_format_date($date) {
        if (empty($date)) {
            return '00000000';
        }
        
        return date('dmY', strtotime($date));
    }
}

/**
 * Remove acentos e caracteres especiais de uma string
 * 
 * @param string $string String a ser processada
 * @return string String sem acentos
 */
if (!function_exists('remove_accents')) {
    function remove_accents($string) {
        if (!preg_match('/[\x80-\xff]/', $string)) {
            return $string;
        }
        
        $chars = array(
            // Decomposições para Latin-1 Supplement
            chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
            chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
            chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
            chr(195).chr(135) => 'C', chr(195).chr(136) => 'E',
            chr(195).chr(137) => 'E', chr(195).chr(138) => 'E',
            chr(195).chr(139) => 'E', chr(195).chr(140) => 'I',
            chr(195).chr(141) => 'I', chr(195).chr(142) => 'I',
            chr(195).chr(143) => 'I', chr(195).chr(145) => 'N',
            chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
            chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
            chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
            chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
            chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
            chr(195).chr(159) => 's', chr(195).chr(160) => 'a',
            chr(195).chr(161) => 'a', chr(195).chr(162) => 'a',
            chr(195).chr(163) => 'a', chr(195).chr(164) => 'a',
            chr(195).chr(165) => 'a', chr(195).chr(167) => 'c',
            chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
            chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
            chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
            chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
            chr(195).chr(177) => 'n', chr(195).chr(178) => 'o',
            chr(195).chr(179) => 'o', chr(195).chr(180) => 'o',
            chr(195).chr(181) => 'o', chr(195).chr(182) => 'o',
            chr(195).chr(182) => 'o', chr(195).chr(185) => 'u',
            chr(195).chr(186) => 'u', chr(195).chr(187) => 'u',
            chr(195).chr(188) => 'u', chr(195).chr(189) => 'y',
            chr(195).chr(191) => 'y',
            // Decomposições para Latin Extended-A
            chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
            chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
            chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
            chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
            chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
            chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
            chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
            chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
            chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
            chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
            chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
            chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
            chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
            chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
            chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
            chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
            chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
            chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
            chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
            chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
            chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
            chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
            chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
            chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
            chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
            chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
            chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
            chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
            chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
            chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
            chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
            chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
            chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
            chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
            chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
            chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
            chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
            chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
            chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
            chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
            chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
            chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
            chr(197).chr(148) => 'R', chr(197).chr(149) => 'r',
            chr(197).chr(150) => 'R', chr(197).chr(151) => 'r',
            chr(197).chr(152) => 'R', chr(197).chr(153) => 'r',
            chr(197).chr(154) => 'S', chr(197).chr(155) => 's',
            chr(197).chr(156) => 'S', chr(197).chr(157) => 's',
            chr(197).chr(158) => 'S', chr(197).chr(159) => 's',
            chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
            chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
            chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
            chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
            chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
            chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
            chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
            chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
            chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
            chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
            chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
            chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
            chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
            chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
            chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
            chr(197).chr(190) => 'z', chr(197).chr(191) => 's'
        );
        
        return strtr($string, $chars);
    }
}

/**
 * Converte o tipo de documento (CPF ou CNPJ) para o código utilizado no DIMOB
 * 
 * @param string $cpf_cnpj CPF ou CNPJ
 * @return string Código do tipo de documento (1 para CPF, 2 para CNPJ)
 */
if (!function_exists('dimob_get_document_type')) {
    function dimob_get_document_type($cpf_cnpj) {
        $only_numbers = preg_replace('/[^0-9]/', '', $cpf_cnpj);
        // Para DIMOB, retorna o número sem espaços
        return (strlen($only_numbers) > 11) ? '2' : '1';
    }
}

/**
 * Obtém o código do município conforme tabela da Receita Federal
 * 
 * @param string $uf UF do município
 * @param string $cidade Nome do município
 * @return string Código do município
 */
if (!function_exists('dimob_get_municipio_code')) {
    function dimob_get_municipio_code($uf, $cidade) {
        // Códigos de algumas capitais e cidades principais (simplificado)
        $codigos_municipios = [
            'PE|RECIFE' => '2531',
            'PE|OLINDA' => '2491',
            'PE|JABOATAO DOS GUARARAPES' => '2475',
            'PE|PAULISTA' => '2495',
            'SP|SAO PAULO' => '7107',
            'RJ|RIO DE JANEIRO' => '6001',
            'MG|BELO HORIZONTE' => '4123',
            'RS|PORTO ALEGRE' => '8801',
            'BA|SALVADOR' => '3849',
            'DF|BRASILIA' => '9701',
            'PR|CURITIBA' => '7535',
            'AM|MANAUS' => '0255',
            'PA|BELEM' => '0427',
            'CE|FORTALEZA' => '1389',
            'PE|CAMARAGIBE' => '2453',
            'PE|CABO DE SANTO AGOSTINHO' => '2445',
            // Adicionar mais códigos conforme necessário
        ];
        
        $chave = strtoupper($uf) . '|' . strtoupper(remove_accents($cidade));
        
        if (isset($codigos_municipios[$chave])) {
            return $codigos_municipios[$chave];
        }
        
        // Se não encontrar, retorna um código padrão para a UF
        $codigos_uf = [
            'AC' => '0100',
            'AL' => '2700',
            'AM' => '0200',
            'AP' => '1600',
            'BA' => '2900',
            'CE' => '2300',
            'DF' => '5300',
            'ES' => '3200',
            'GO' => '5200',
            'MA' => '2100',
            'MG' => '3100',
            'MS' => '5000',
            'MT' => '5100',
            'PA' => '1500',
            'PB' => '2500',
            'PE' => '2600',
            'PI' => '2200',
            'PR' => '4100',
            'RJ' => '3300',
            'RN' => '2400',
            'RO' => '1100',
            'RR' => '1400',
            'RS' => '4300',
            'SC' => '4200',
            'SE' => '2800',
            'SP' => '3500',
            'TO' => '1700',
        ];
        
        return isset($codigos_uf[strtoupper($uf)]) ? $codigos_uf[strtoupper($uf)] : '0000';
    }
}
