<?php

use PHPUnit\Framework\TestCase;

class Fix20MissingSemicolonBeforeLastStatementTest extends TestCase
{
    public function testItWorks()
    {
        exec("php " . __DIR__ . "/../../fmt.stub.php --passes=AutoSemicolon -o=- " . __DIR__ . '/fixtures/twenty.txt', $output);

        $file = file_get_contents(__DIR__ . '/fixtures/twenty.php');

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $output = explode("\n", implode(PHP_EOL, $output));
        }

        // file_put_contents(__DIR__ . '/fixtures/twenty.php', implode("\n", $output));

        $this->assertSame($file, implode("\n", $output));
    }
}
