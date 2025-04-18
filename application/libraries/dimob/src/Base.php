<?php

declare(strict_types=1);

namespace Dimob;

abstract class Base
{
    abstract public function toArray();

    protected function blank(int $qtd)
    {
        return str_pad('', $qtd, ' ', STR_PAD_LEFT);
    }

    protected function zero(int $qtd)
    {
        return str_pad('', $qtd, '0', STR_PAD_LEFT);
    }

    public function __toString(): string
    {
        $string = '';
        foreach ($this->toArray() as $value) {
            $string .= $value;
        }
        return mb_strtoupper($string);
    }

    protected function numberFormat(string $str): string
    {
        $str = ((float) $str) * 100;
        $str = number_format((float) $str, 0, '', '');
        $str = str_pad($str, 14, '0', STR_PAD_LEFT);
        return $str;
    }

    protected function removeMask(string $str): string
    {
        return trim(preg_replace('/[^0-9]/', '', $str));
    }

    protected function string(string $string): string
    {
        $string = preg_replace(array("/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/", "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/"), explode(" ", "a A e E i I o O u U n N"), $string);
        $string = preg_replace("/[][><}{)(:;,!?*%~^`@]/", "", $string);
        return trim(preg_replace('/[^A-Za-z0-9\s]/', '', $string));
    }

    public function convertNewLine(string $string): string
    {
        return str_replace(["\r\n", "\n", "\r"], "\r\n", $string);
    }

}