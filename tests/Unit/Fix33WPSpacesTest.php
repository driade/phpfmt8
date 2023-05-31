<?php

use PHPUnit\Framework\TestCase;

class Fix33WPSpacesTest extends TestCase
{
    public function testItWorks()
    {
        exec("php " . __DIR__ . "/../../fmt.stub.php --psr2 --wp -o=- " . __DIR__ . '/fixtures/thirtythree.txt', $output);

        $file = file_get_contents(__DIR__ . '/fixtures/thirtythree.php');

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $output = explode("\n", implode(PHP_EOL, $output));
        }

        // file_put_contents(__DIR__ . '/fixtures/thirtythree.php', implode("\n", $output));

        $this->assertSame($file, implode("\n", $output));
    }
}
