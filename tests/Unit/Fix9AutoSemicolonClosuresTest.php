<?php

use PHPUnit\Framework\TestCase;

class Fix9AutoSemicolonClosuresTest extends TestCase
{
    public function testItWorks()
    {
        exec("php " . __DIR__ . "/../../fmt.stub.php --passes=AutoSemicolon -o=- " . __DIR__ . '/fixtures/ten.txt', $output);

        $file = file_get_contents(__DIR__ . '/fixtures/ten.php');

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $output = explode("\n", implode(PHP_EOL, $output));
        }

        $this->assertSame($file, implode("\n", $output));
    }
}
