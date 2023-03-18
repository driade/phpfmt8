<?php

use PHPUnit\Framework\TestCase;

class BasicPHP82Test extends TestCase
{
    public function testItParsesAGenericFile()
    {
        if (PHP_VERSION_ID < 80200) {
            $this->markTestSkipped();
        }
        exec("php " . __DIR__ . "/../../fmt.stub.php --passes=PSR2KeywordsLowerCase -o=- " . __DIR__ . '/fixtures/three.txt', $output);

        $file = file_get_contents(__DIR__ . '/fixtures/three.php');
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $output = explode("\n", implode(PHP_EOL, $output));
        }

        $this->assertSame($file, implode("\n", $output));
    }
}
