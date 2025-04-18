<?php


namespace Tests;

use Dimob\R01;
use PHPUnit\Framework\TestCase;

class R01Test extends TestCase
{
    public function testInstanceOf()
    {
        $r01 = R01::create(
            '09719733000155',
            '2020',
            '0',
            '0',
            '0',
            '0',
            '0',
            'Empresa',
            '93529222046',
            'Rua A',
            'SP',
            '6969',
            'Ribeirao Preto',
            '0359509421'
        );

        $this->assertInstanceOf(R01::class, $r01);
        $this->assertEquals('09719733000155', $r01->cnpj());
        $this->assertEquals('2020', $r01->ano());
        $this->assertEquals('0', $r01->delcaracao());
        $this->assertEquals('0000000000', $r01->recido());
        $this->assertEquals('0', $r01->situacao());
        $this->assertEquals('00000000', $r01->data());
        $this->assertEquals('00', $r01->codigo());
        $this->assertEquals('Empresa                                                     ', $r01->nome());
        $this->assertEquals('93529222046', $r01->cpf());
        $this->assertEquals('Rua A                                                                                                                   ', $r01->endereco());
        $this->assertEquals('SP', $r01->uf());
        $this->assertEquals('6969', $r01->codigoMunicipio());
        $this->assertEquals('Ribeirao Preto      ', $r01->reservado1());
        $this->assertEquals('0359509421', $r01->reservado2());
        $this->assertEquals(270, strlen((string) $r01));
    }

}