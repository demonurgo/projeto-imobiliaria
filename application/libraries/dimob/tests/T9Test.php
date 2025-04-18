<?php


namespace Tests;

use Dimob\T9;
use PHPUnit\Framework\TestCase;

class T9Test extends TestCase
{
    public function testInstanceOf()
    {
        $t9 = T9::create(
            '09719733000155',
            '3'
        );

        $this->assertInstanceOf(T9::class, $t9);
        $this->assertEquals('09719733000155', $t9->cnpj());
        $this->assertEquals('000003', $t9->totalLinhas());
        $this->assertEquals(102, strlen((string) $t9));
    }

}