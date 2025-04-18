<?php

declare(strict_types=1);

namespace Dimob;

class T9 extends Base
{
    private function __construct(
        private ?string $cnpj,
        private ?string $totalLinhas
    ) {

    }

    public static function create(
        ?string $cnpj,
        ?string $totalLinhas
    ): self {
        return new self(
            $cnpj ?? '0',
            $totalLinhas ?? '0'
        );
    }

    public function tipo(): ?string
    {
        return 'T9';
    }

    public function cnpj(): ?string
    {
        return str_pad($this->cnpj, 14, '0', STR_PAD_LEFT);
    }

    public function totalLinhas(): ?string
    {
        return str_pad($this->totalLinhas, 6, '0', STR_PAD_LEFT);
    }

    public function toArray(): array
    {
        return [
            $this->tipo(),
            $this->cnpj(),
            $this->totalLinhas(),
            $this->blank(80),
        ];
    }
}