<?php

declare(strict_types=1);

namespace Dimob;

class R01 extends Base
{
    private function __construct(
        private ?string $cnpj,
        private ?string $ano,
        private ?string $delcaracao,
        private ?string $recido,
        private ?string $situacao,
        private ?string $data,
        private ?string $codigo,
        private ?string $nome,
        private ?string $cpf,
        private ?string $endereco,
        private ?string $uf,
        private ?string $codigoMunicipio,
        private ?string $reservado1,
        private ?string $reservado2,
    ) {

    }

    public static function create(
        ?string $cnpj,
        ?string $ano,
        ?string $delcaracao,
        ?string $recido,
        ?string $situacao,
        ?string $data,
        ?string $codigo,
        ?string $nome,
        ?string $cpf,
        ?string $endereco,
        ?string $uf,
        ?string $codigoMunicipio,
        ?string $reservado1,
        ?string $reservado2,
    ): self {
        return new self(
            $cnpj ?? '0',
            $ano ?? '0',
            $delcaracao ?? '0',
            $recido ?? '0',
            $situacao ?? '0',
            $data ?? '0',
            $codigo ?? '0',
            $nome ?? '',
            $cpf ?? '0',
            $endereco ?? '',
            $uf ?? '',
            $codigoMunicipio ?? '0',
            $reservado1 ?? '',
            $reservado2 ?? '',
        );
    }

    public function tipo(): ?string
    {
        return 'R01';
    }

    public function cnpj(): ?string
    {
        return str_pad($this->removeMask($this->cnpj), 14, '0', STR_PAD_LEFT);
    }

    public function ano(): ?string
    {
        return str_pad($this->ano, 4, '0', STR_PAD_LEFT);
    }

    public function delcaracao(): ?string
    {
        return str_pad($this->delcaracao, 1, '0', STR_PAD_LEFT);
    }

    public function recido(): ?string
    {
        return str_pad($this->recido, 10, '0', STR_PAD_LEFT);
    }

    public function situacao(): ?string
    {
        return str_pad($this->situacao, 1, '0', STR_PAD_LEFT);
    }

    public function data(): ?string
    {
        return str_pad($this->data, 8, '0', STR_PAD_LEFT);
    }

    public function codigo(): ?string
    {
        return str_pad($this->codigo, 2, '0', STR_PAD_LEFT);
    }

    public function nome(): ?string
    {
        return str_pad($this->string($this->nome), 60, ' ', STR_PAD_RIGHT);
    }

    public function cpf(): ?string
    {
        return str_pad($this->removeMask($this->cpf), 11, '0', STR_PAD_LEFT);
    }

    public function endereco(): ?string
    {
        return str_pad($this->string($this->endereco), 120, ' ', STR_PAD_RIGHT);
    }

    public function uf(): ?string
    {
        return str_pad($this->uf, 2, ' ', STR_PAD_RIGHT);
    }

    public function codigoMunicipio(): ?string
    {
        return str_pad($this->codigoMunicipio, 4, '0', STR_PAD_LEFT);
    }

    public function reservado1(): ?string
    {
        return str_pad($this->string($this->reservado1), 20, ' ', STR_PAD_RIGHT);
    }

    public function reservado2(): ?string
    {
        return str_pad($this->string($this->reservado2), 10, ' ', STR_PAD_RIGHT);
    }

    public function toArray(): array
    {
        return [
            $this->tipo(),
            $this->cnpj(),
            $this->ano(),
            $this->delcaracao(),
            $this->recido(),
            $this->situacao(),
            $this->data(),
            $this->codigo(),
            $this->nome(),
            $this->cpf(),
            $this->endereco(),
            $this->uf(),
            $this->codigoMunicipio(),
            $this->reservado1(),
            $this->reservado2(),
        ];
    }
}