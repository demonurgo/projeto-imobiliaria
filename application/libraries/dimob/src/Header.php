<?php

declare(strict_types=1);

namespace Dimob;

class Header extends Base
{
    private function __construct(
        private string $ano,
        private string $cnpj,
        private string $codigo,
        private string $empresa,
        private string $estado
    ) {

    }

    public static function create(
        string $ano,
        string $cnpj,
        string $codigo,
        string $empresa,
        string $estado
    ): self {
        return new self(
            $ano,
            $cnpj,
            $codigo,
            $empresa,
            $estado
        );
    }

    public function dimob(): string
    {
        return 'DIMOB';
    }

    public function ano(): string
    {
        return str_pad($this->ano, 4, '0', STR_PAD_LEFT);
    }

    public function cnpj(): string
    {
        return str_pad($this->removeMask($this->cnpj), 14, '0', STR_PAD_LEFT);
    }

    public function codigo(): string
    {
        return str_pad($this->codigo, 4, '0', STR_PAD_LEFT);
    }

    public function empresa(): string
    {
        return str_pad($this->string($this->empresa), 60, ' ', STR_PAD_RIGHT);
    }

    public function estado(): string
    {
        return str_pad($this->estado, 2, ' ', STR_PAD_RIGHT);
    }

    public function toArray()
    {
        return [
            $this->dimob(),
            $this->blank(7),
            $this->ano(),
            $this->blank(5),
            $this->cnpj(),
            $this->codigo(),
            $this->empresa(),
            $this->estado(),
            $this->blank(273),
        ];
    }
}