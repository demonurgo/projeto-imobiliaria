<?php


namespace Tests;

use Dimob\R02;
use PHPUnit\Framework\TestCase;

class R02Test extends TestCase
{
    public function testInstanceOf()
    {
        $r02 = R02::create(
            '09719733000155',
            '2020',
            '0',
            '93529222046',
            'Nome Locador',
            '93529222046',
            'Nome Locatario',
            '123456',
            '20200101',
            '1',
            '1',
            '1',
            '2',
            '2',
            '2',
            '3',
            '3',
            '3',
            '4',
            '4',
            '4',
            '5',
            '5',
            '5',
            '6',
            '6',
            '6',
            '7',
            '7',
            '7',
            '8',
            '8',
            '8',
            '9',
            '9',
            '9',
            '10',
            '10',
            '10',
            '11',
            '11',
            '11',
            '12',
            '12',
            '12',
            'U',
            'Endereco',
            '14032000',
            '6969',
            '',
            'SP',
            ''
        );

        $this->assertInstanceOf(R02::class, $r02);
        $this->assertEquals('R02', $r02->tipo());
        $this->assertEquals('09719733000155', $r02->cnpj());
        $this->assertEquals('2020', $r02->ano());
        $this->assertEquals('0000000', $r02->sequencia());
        $this->assertEquals('93529222046   ', $r02->cpfCnpjLocador());
        $this->assertEquals('Nome Locador                                                ', $r02->nomeLocador());
        $this->assertEquals('93529222046   ', $r02->cpfCnpjLocatario());
        $this->assertEquals('Nome Locatario                                              ', $r02->nomeLocatario());
        $this->assertEquals('123456', $r02->numeroContrato());
        $this->assertEquals('20200101', $r02->dataContrato());
        $this->assertEquals('00000000000100', $r02->janeiroAluguel());
        $this->assertEquals('00000000000100', $r02->janeiroComissao());
        $this->assertEquals('00000000000100', $r02->janeiroImposto());
        $this->assertEquals('00000000000200', $r02->fevereiroAluguel());
        $this->assertEquals('00000000000200', $r02->fevereiroComissao());
        $this->assertEquals('00000000000200', $r02->fevereiroImposto());
        $this->assertEquals('00000000000300', $r02->marcoAluguel());
        $this->assertEquals('00000000000300', $r02->marcoComissao());
        $this->assertEquals('00000000000300', $r02->marcoImposto());
        $this->assertEquals('00000000000400', $r02->abrilAluguel());
        $this->assertEquals('00000000000400', $r02->abrilComissao());
        $this->assertEquals('00000000000400', $r02->abrilImposto());
        $this->assertEquals('00000000000500', $r02->maioAluguel());
        $this->assertEquals('00000000000500', $r02->maioComissao());
        $this->assertEquals('00000000000500', $r02->maioImposto());
        $this->assertEquals('00000000000600', $r02->junhoAluguel());
        $this->assertEquals('00000000000600', $r02->junhoComissao());
        $this->assertEquals('00000000000600', $r02->junhoImposto());
        $this->assertEquals('00000000000700', $r02->julhoAluguel());
        $this->assertEquals('00000000000700', $r02->julhoComissao());
        $this->assertEquals('00000000000700', $r02->julhoImposto());
        $this->assertEquals('00000000000800', $r02->agostoAluguel());
        $this->assertEquals('00000000000800', $r02->agostoComissao());
        $this->assertEquals('00000000000800', $r02->agostoImposto());
        $this->assertEquals('00000000000900', $r02->setembroAluguel());
        $this->assertEquals('00000000000900', $r02->setembroComissao());
        $this->assertEquals('00000000000900', $r02->setembroImposto());
        $this->assertEquals('00000000001000', $r02->outubroAluguel());
        $this->assertEquals('00000000001000', $r02->outubroComissao());
        $this->assertEquals('00000000001000', $r02->outubroImposto());
        $this->assertEquals('00000000001100', $r02->novembroAluguel());
        $this->assertEquals('00000000001100', $r02->novembroComissao());
        $this->assertEquals('00000000001100', $r02->novembroImposto());
        $this->assertEquals('00000000001200', $r02->dezembroAluguel());
        $this->assertEquals('00000000001200', $r02->dezembroComissao());
        $this->assertEquals('00000000001200', $r02->dezembroImposto());
        $this->assertEquals('U', $r02->tipoImovel());
        $this->assertEquals('Endereco                                                    ', $r02->enderecoImovel());
        $this->assertEquals('14032000', $r02->cep());
        $this->assertEquals('6969', $r02->codigoMunicipio());
        $this->assertEquals('                    ', $r02->reservado1());
        $this->assertEquals('SP', $r02->uf());
        $this->assertEquals('          ', $r02->reservado2());
        $this->assertEquals(799, strlen((string) $r02));

    }

}