<?php

use PHPUnit\Framework\TestCase;

class BasicPHP8Test extends TestCase
{
    public function testItParsesAGenericFile()
    {
        if (PHP_VERSION_ID < 80000) {
            $this->markTestSkipped();
        }
        exec("php " . __DIR__ . "/../../fmt.stub.php -o=- " . __DIR__ . '/fixtures/two.txt', $output);

        $file = file_get_contents(__DIR__ . '/fixtures/two.php');
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $output = explode("\n", implode(PHP_EOL, $output));
        }

        $this->assertSame($file, implode("\n", $output));
    }
}
