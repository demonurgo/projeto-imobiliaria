<?php

namespace Tests;

use Dimob\Header;
use PHPUnit\Framework\TestCase;

class HeaderTest extends TestCase
{
    public function testInstanceOf()
    {
        $header = Header::create(
            date('Y'),
            '123456789',
            '2020',
            'Nome da empresa',
            'SP'
        );

        $this->assertInstanceOf(Header::class, $header);

    }

    public function testToString()
    {

        $header = Header::create(
            '2022',
            '75930295069',
            '2020',
            'Nome da empresa',
            'SP'
        );

        $str = 'DIMOB       2022     000759302950692020NOME DA EMPRESA                                             SP                                                                                                                                                                                                                                                                                 ';
        $this->assertEquals('2022', $header->ano());
        $this->assertEquals('00075930295069', $header->cnpj());
        $this->assertEquals('2020', $header->codigo());
        $this->assertEquals('Nome da empresa                                             ', $header->empresa());
        $this->assertEquals('SP', $header->estado());
        $this->assertEquals($str, (string) $header);
        $this->assertEquals(374, strlen((string) $header));
    }

}