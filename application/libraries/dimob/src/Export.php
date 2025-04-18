<?php


namespace Dimob;

use SplFileObject;

class Export
{
    private Header $header;
    private R01 $r01;
    private array $r02List = [];

    private $error = '';

    private function __construct()
    {
    }

    public static function create(): Export
    {
        return new self();
    }

    public function addHeader(Header $header): void
    {
        $this->header = $header;
    }

    public function addR01(R01 $r01): void
    {
        $this->r01 = $r01;
    }

    public function addR02(R02 $r02): void
    {
        $this->r02List[] = $r02;
    }

    public function toArray(): array
    {
        return [];
    }

    public function getHeader(): string
    {
        return (string) $this->header;
    }
    public function getR01(): string
    {
        return (string) $this->r01;
    }
    public function getR02(): array
    {
        return $this->r02List;
    }

    public function getT9(): T9
    {
        return T9::create(
            $this->header->cnpj(),
            count($this->r02List) + 3
        );
    }

    public function validarConteudoASCII($conteudo): bool
    {
        $this->error = "";
        $valido = true;
        for ($i = 0; $i < strlen($conteudo); $i++) {
            if (!ctype_print($conteudo[$i])) {
                $this->error = "Erro: Caractere inválido encontrado na posição $i.";
                $valido = false;
                break;
            }
        }
        return $valido;
    }

    public function validarConteudoASCIIError(): string
    {
        return $this->error;
    }

    public function writeFile(string $file): void
    {
        @unlink($file);

        $arquivo = new SplFileObject($file, 'w');

        // $arquivo->fwrite("\xEF\xBB\xBF");
        $arquivo->fwrite($this->getHeader() . PHP_EOL);
        $arquivo->fwrite($this->getR01() . PHP_EOL);
        foreach ($this->getR02() as $key => $r02) {
            $arquivo->fwrite((string) $r02 . PHP_EOL);
        }
        $arquivo->fwrite($this->getT9() . PHP_EOL);
        $arquivo = null;

        $this->convertToANSI($file);
    }

    private function convertToANSI(string $file)
    {
        // Lê o conteúdo do arquivo de texto
        $content = file_get_contents($file);

        // Converte para o formato ANSI
        $content = mb_convert_encoding($content, 'Windows-1252', 'UTF-8');

        // Escreve o conteúdo convertido para o arquivo de saída
        file_put_contents($file, $content);
    }
}
