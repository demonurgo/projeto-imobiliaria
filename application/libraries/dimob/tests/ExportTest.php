<?php


namespace Tests;

use Dimob\Export;
use Dimob\Header;
use Dimob\R01;
use Dimob\R02;
use Dimob\T9;
use PHPUnit\Framework\TestCase;

class ExportTest extends TestCase
{
    public function testToArray()
    {
        $export = Export::create();
        $header = Header::create(
            '2022',
            '75930295069',
            '2020',
            'Nome da empresa',
            'SP'
        );
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
            '0359509421',
            '',
        );
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

        $export->addHeader($header);
        $export->addR01($r01);
        $export->addR02($r02);
        $export->addR02($r02);

        $this->assertEquals($export->getHeader(), (string) $header);
        $this->assertEquals($export->getR01(), (string) $r01);
        $this->assertEquals($export->getR02(), array($r02, $r02));
        $this->assertNotEmpty($export->getT9());
    }
}