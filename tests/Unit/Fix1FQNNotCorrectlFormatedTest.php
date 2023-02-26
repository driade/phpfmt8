<?php

use PHPUnit\Framework\TestCase;

class Fix1FQNNotCorrectlFormatedTest extends TestCase
{
    public function testItWorks()
    {
        exec("php " . __DIR__ . "/../../fmt.stub.php -o=- " . __DIR__ . '/fixtures/eleven.txt', $output);

        $file = file_get_contents(__DIR__ . '/fixtures/eleven.php');

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $output = explode("\n", implode(PHP_EOL, $output));
        }

        $this->assertSame($file, implode("\n", $output));
    }
}
