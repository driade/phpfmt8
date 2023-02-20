<?php

use PHPUnit\Framework\TestCase;

class BasicTest extends TestCase
{
    public function testItParsesAGenericFile()
    {
        exec("php " . __DIR__ . "/../../fmt.stub.php -o=- " . __DIR__ . '/fixtures/one.txt', $output);

        $file = file_get_contents(__DIR__ . '/fixtures/one.php');
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $output = str_replace(["\r", "\n"], "\r\n", $output);
            $output = preg_replace("/\r\n{2,}/", "\r\n\r\n", $output);
        }

        $this->assertSame($file, implode("\n", $output));
    }
}
