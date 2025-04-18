<?php

declare(strict_types=1);

namespace Dimob;

class R02 extends Base
{
    private function __construct(
        private ?string $cnpj,
        private ?string $ano,
        private ?string $sequencia,
        private ?string $cpfCnpjLocador,
        private ?string $nomeLocador,
        private ?string $cpfCnpjLocatario,
        private ?string $nomeLocatario,
        private ?string $numeroContrato,
        private ?string $dataContrato,
        private ?string $janeiroAluguel,
        private ?string $janeiroComissao,
        private ?string $janeiroImposto,
        private ?string $fevereiroAluguel,
        private ?string $fevereiroComissao,
        private ?string $fevereiroImposto,
        private ?string $marcoAluguel,
        private ?string $marcoComissao,
        private ?string $marcoImposto,
        private ?string $abrilAluguel,
        private ?string $abrilComissao,
        private ?string $abrilImposto,
        private ?string $maioAluguel,
        private ?string $maioComissao,
        private ?string $maioImposto,
        private ?string $junhoAluguel,
        private ?string $junhoComissao,
        private ?string $junhoImposto,
        private ?string $julhoAluguel,
        private ?string $julhoComissao,
        private ?string $julhoImposto,
        private ?string $agostoAluguel,
        private ?string $agostoComissao,
        private ?string $agostoImposto,
        private ?string $setembroAluguel,
        private ?string $setembroComissao,
        private ?string $setembroImposto,
        private ?string $outubroAluguel,
        private ?string $outubroComissao,
        private ?string $outubroImposto,
        private ?string $novembroAluguel,
        private ?string $novembroComissao,
        private ?string $novembroImposto,
        private ?string $dezembroAluguel,
        private ?string $dezembroComissao,
        private ?string $dezembroImposto,
        private ?string $tipoImovel,
        private ?string $enderecoImovel,
        private ?string $cep,
        private ?string $codigoMunicipio,
        private ?string $reservado1,
        private ?string $uf,
        private ?string $reservado2,
    ) {

    }

    public static function create(
        ?string $cnpj,
        ?string $ano,
        ?string $sequencia,
        ?string $cpfCnpjLocador,
        ?string $nomeLocador,
        ?string $cpfCnpjLocatario,
        ?string $nomeLocatario,
        ?string $numeroContrato,
        ?string $dataContrato,
        ?string $janeiroAluguel,
        ?string $janeiroComissao,
        ?string $janeiroImposto,
        ?string $fevereiroAluguel,
        ?string $fevereiroComissao,
        ?string $fevereiroImposto,
        ?string $marcoAluguel,
        ?string $marcoComissao,
        ?string $marcoImposto,
        ?string $abrilAluguel,
        ?string $abrilComissao,
        ?string $abrilImposto,
        ?string $maioAluguel,
        ?string $maioComissao,
        ?string $maioImposto,
        ?string $junhoAluguel,
        ?string $junhoComissao,
        ?string $junhoImposto,
        ?string $julhoAluguel,
        ?string $julhoComissao,
        ?string $julhoImposto,
        ?string $agostoAluguel,
        ?string $agostoComissao,
        ?string $agostoImposto,
        ?string $setembroAluguel,
        ?string $setembroComissao,
        ?string $setembroImposto,
        ?string $outubroAluguel,
        ?string $outubroComissao,
        ?string $outubroImposto,
        ?string $novembroAluguel,
        ?string $novembroComissao,
        ?string $novembroImposto,
        ?string $dezembroAluguel,
        ?string $dezembroComissao,
        ?string $dezembroImposto,
        ?string $tipoImovel,
        ?string $enderecoImovel,
        ?string $cep,
        ?string $codigoMunicipio,
        ?string $reservado1,
        ?string $uf,
        ?string $reservado2,
    ): self {
        return new self(
            $cnpj ?? '0',
            $ano ?? '0',
            $sequencia ?? '0',
            $cpfCnpjLocador ?? '0',
            $nomeLocador ?? '',
            $cpfCnpjLocatario ?? '0',
            $nomeLocatario ?? '',
            $numeroContrato ?? '0',
            $dataContrato ?? '0',
            $janeiroAluguel ?? '0',
            $janeiroComissao ?? '0',
            $janeiroImposto ?? '0',
            $fevereiroAluguel ?? '0',
            $fevereiroComissao ?? '0',
            $fevereiroImposto ?? '0',
            $marcoAluguel ?? '0',
            $marcoComissao ?? '0',
            $marcoImposto ?? '0',
            $abrilAluguel ?? '0',
            $abrilComissao ?? '0',
            $abrilImposto ?? '0',
            $maioAluguel ?? '0',
            $maioComissao ?? '0',
            $maioImposto ?? '0',
            $junhoAluguel ?? '0',
            $junhoComissao ?? '0',
            $junhoImposto ?? '0',
            $julhoAluguel ?? '0',
            $julhoComissao ?? '0',
            $julhoImposto ?? '0',
            $agostoAluguel ?? '0',
            $agostoComissao ?? '0',
            $agostoImposto ?? '0',
            $setembroAluguel ?? '0',
            $setembroComissao ?? '0',
            $setembroImposto ?? '0',
            $outubroAluguel ?? '0',
            $outubroComissao ?? '0',
            $outubroImposto ?? '0',
            $novembroAluguel ?? '0',
            $novembroComissao ?? '0',
            $novembroImposto ?? '0',
            $dezembroAluguel ?? '0',
            $dezembroComissao ?? '0',
            $dezembroImposto ?? '0',
            $tipoImovel ?? '',
            $enderecoImovel ?? '',
            $cep ?? '0',
            $codigoMunicipio ?? '0',
            $reservado1 ?? '',
            $uf ?? '',
            $reservado2 ?? '',
        );

    }

    public function tipo(): ?string
    {
        return 'R02';
    }

    public function cnpj(): ?string
    {
        return str_pad($this->removeMask($this->cnpj), 14, '0', STR_PAD_LEFT);
    }

    public function ano(): ?string
    {
        return str_pad($this->ano, 4, '0', STR_PAD_LEFT);
    }

    public function sequencia(): ?string
    {
        return str_pad($this->sequencia, 7, '0', STR_PAD_LEFT);
    }

    public function cpfCnpjLocador(): ?string
    {
        return str_pad($this->removeMask($this->cpfCnpjLocador), 14, ' ', STR_PAD_RIGHT);
    }

    public function nomeLocador(): ?string
    {
        return str_pad($this->string($this->nomeLocador), 60, ' ', STR_PAD_RIGHT);
    }

    public function cpfCnpjLocatario(): ?string
    {
        return str_pad($this->removeMask($this->cpfCnpjLocatario), 14, ' ', STR_PAD_RIGHT);
    }

    public function nomeLocatario(): ?string
    {
        return str_pad($this->string($this->nomeLocatario), 60, ' ', STR_PAD_RIGHT);
    }

    public function numeroContrato(): ?string
    {
        return str_pad($this->numeroContrato, 6, '0', STR_PAD_LEFT);
    }

    public function dataContrato(): ?string
    {
        return str_pad($this->dataContrato, 8, '0', STR_PAD_LEFT);
    }

    public function janeiroAluguel(): ?string
    {
        return $this->numberFormat($this->janeiroAluguel);
    }

    public function janeiroComissao(): ?string
    {
        return $this->numberFormat($this->janeiroComissao);
    }

    public function janeiroImposto(): ?string
    {
        return $this->numberFormat($this->janeiroImposto);
    }

    public function fevereiroAluguel(): ?string
    {
        return $this->numberFormat($this->fevereiroAluguel);
    }

    public function fevereiroComissao(): ?string
    {
        return $this->numberFormat($this->fevereiroComissao);
    }

    public function fevereiroImposto(): ?string
    {
        return $this->numberFormat($this->fevereiroImposto);
    }

    public function marcoAluguel(): ?string
    {
        return $this->numberFormat($this->marcoAluguel);
    }

    public function marcoComissao(): ?string
    {
        return $this->numberFormat($this->marcoComissao);
    }

    public function marcoImposto(): ?string
    {
        return $this->numberFormat($this->marcoImposto);
    }

    public function abrilAluguel(): ?string
    {
        return $this->numberFormat($this->abrilAluguel);
    }

    public function abrilComissao(): ?string
    {
        return $this->numberFormat($this->abrilComissao);
    }

    public function abrilImposto(): ?string
    {
        return $this->numberFormat($this->abrilImposto);
    }

    public function maioAluguel(): ?string
    {
        return $this->numberFormat($this->maioAluguel);
    }

    public function maioComissao(): ?string
    {
        return $this->numberFormat($this->maioComissao);
    }

    public function maioImposto(): ?string
    {
        return $this->numberFormat($this->maioImposto);
    }

    public function junhoAluguel(): ?string
    {
        return $this->numberFormat($this->junhoAluguel);
    }

    public function junhoComissao(): ?string
    {
        return $this->numberFormat($this->junhoComissao);
    }

    public function junhoImposto(): ?string
    {
        return $this->numberFormat($this->junhoImposto);
    }

    public function julhoAluguel(): ?string
    {
        return $this->numberFormat($this->julhoAluguel);
    }

    public function julhoComissao(): ?string
    {
        return $this->numberFormat($this->julhoComissao);
    }

    public function julhoImposto(): ?string
    {
        return $this->numberFormat($this->julhoImposto);
    }

    public function agostoAluguel(): ?string
    {
        return $this->numberFormat($this->agostoAluguel);
    }

    public function agostoComissao(): ?string
    {
        return $this->numberFormat($this->agostoComissao);
    }

    public function agostoImposto(): ?string
    {
        return $this->numberFormat($this->agostoImposto);
    }

    public function setembroAluguel(): ?string
    {
        return $this->numberFormat($this->setembroAluguel);
    }

    public function setembroComissao(): ?string
    {
        return $this->numberFormat($this->setembroComissao);
    }

    public function setembroImposto(): ?string
    {
        return $this->numberFormat($this->setembroImposto);
    }

    public function outubroAluguel(): ?string
    {
        return $this->numberFormat($this->outubroAluguel);
    }

    public function outubroComissao(): ?string
    {
        return $this->numberFormat($this->outubroComissao);
    }

    public function outubroImposto(): ?string
    {
        return $this->numberFormat($this->outubroImposto);
    }

    public function novembroAluguel(): ?string
    {
        return $this->numberFormat($this->novembroAluguel);
    }

    public function novembroComissao(): ?string
    {
        return $this->numberFormat($this->novembroComissao);
    }

    public function novembroImposto(): ?string
    {
        return $this->numberFormat($this->novembroImposto);
    }

    public function dezembroAluguel(): ?string
    {
        return $this->numberFormat($this->dezembroAluguel);
    }

    public function dezembroComissao(): ?string
    {
        return $this->numberFormat($this->dezembroComissao);
    }

    public function dezembroImposto(): ?string
    {
        return $this->numberFormat($this->dezembroImposto);
    }

    public function tipoImovel(): ?string
    {
        $this->tipoImovel = $this->tipoImovel === 'R' ? 'R' : 'U';
        return str_pad($this->tipoImovel, 1, '0', STR_PAD_RIGHT);
    }

    public function enderecoImovel(): ?string
    {
        return str_pad($this->string($this->enderecoImovel), 60, ' ', STR_PAD_RIGHT);
    }

    public function cep(): ?string
    {
        return str_pad($this->removeMask($this->cep), 8, '0', STR_PAD_LEFT);
    }

    public function codigoMunicipio(): ?string
    {
        return str_pad($this->codigoMunicipio, 4, '0', STR_PAD_LEFT);
    }

    public function reservado1(): ?string
    {
        return str_pad($this->string($this->reservado1), 20, ' ', STR_PAD_RIGHT);
    }

    public function uf(): ?string
    {
        return str_pad($this->uf, 2, ' ', STR_PAD_RIGHT);
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
            $this->sequencia(),
            $this->cpfCnpjLocador(),
            $this->nomeLocador(),
            $this->cpfCnpjLocatario(),
            $this->nomeLocatario(),
            $this->numeroContrato(),
            $this->dataContrato(),
            $this->janeiroAluguel(),
            $this->janeiroComissao(),
            $this->janeiroImposto(),
            $this->fevereiroAluguel(),
            $this->fevereiroComissao(),
            $this->fevereiroImposto(),
            $this->marcoAluguel(),
            $this->marcoComissao(),
            $this->marcoImposto(),
            $this->abrilAluguel(),
            $this->abrilComissao(),
            $this->abrilImposto(),
            $this->maioAluguel(),
            $this->maioComissao(),
            $this->maioImposto(),
            $this->junhoAluguel(),
            $this->junhoComissao(),
            $this->junhoImposto(),
            $this->julhoAluguel(),
            $this->julhoComissao(),
            $this->julhoImposto(),
            $this->agostoAluguel(),
            $this->agostoComissao(),
            $this->agostoImposto(),
            $this->setembroAluguel(),
            $this->setembroComissao(),
            $this->setembroImposto(),
            $this->outubroAluguel(),
            $this->outubroComissao(),
            $this->outubroImposto(),
            $this->novembroAluguel(),
            $this->novembroComissao(),
            $this->novembroImposto(),
            $this->dezembroAluguel(),
            $this->dezembroComissao(),
            $this->dezembroImposto(),
            $this->tipoImovel(),
            $this->enderecoImovel(),
            $this->cep(),
            $this->codigoMunicipio(),
            $this->reservado1(),
            $this->uf(),
            $this->reservado2(),
        ];
    }
}
