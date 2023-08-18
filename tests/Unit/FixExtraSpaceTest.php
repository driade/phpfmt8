<?php

use PHPUnit\Framework\TestCase;

class FixExtraSpaceTest extends TestCase
{
    public function testItWorks()
    {
        exec("php " . __DIR__ . "/../../fmt.stub.php -o=- " . __DIR__ . '/fixtures/fixextraspacetest.txt', $output);

        $file = file_get_contents(__DIR__ . '/fixtures/fixextraspacetest.php');

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $output = explode("\n", implode(PHP_EOL, $output));
        }

        // file_put_contents(__DIR__ . '/fixtures/fixextraspacetest.php', implode("\n", $output));

        $this->assertSame($file, implode("\n", $output));
    }
}
