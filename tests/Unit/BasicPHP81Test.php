<?php

use PHPUnit\Framework\TestCase;

class BasicPHP81Test extends TestCase
{
    public function testItParsesAGenericFile()
    {
        if (PHP_VERSION_ID < 80100) {
            $this->markTestSkipped();
        }
        exec("php " . __DIR__ . "/../../fmt.stub.php -o=- " . __DIR__ . '/fixtures/four.txt', $output);

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $output = explode("\n", implode(PHP_EOL, $output));
        }

        $file = file_get_contents(__DIR__ . '/fixtures/four.php');

        $this->assertSame($file, implode("\n", $output));
    }
}
