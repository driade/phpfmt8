<?php

use PHPUnit\Framework\TestCase;

class FixAutoSemicolon2Test extends TestCase
{
    public function testItWorks()
    {
        exec("php " . __DIR__ . "/../../fmt.stub.php --passes=AutoSemicolon -o=- " . __DIR__ . '/fixtures/fixautosemicolon2.txt', $output);

        // file_put_contents(__DIR__ . '/fixtures/fixautosemicolon2.php', implode("\n", $output));

        $file = file_get_contents(__DIR__ . '/fixtures/fixautosemicolon2.php');

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $output = explode("\n", implode(PHP_EOL, $output));
        }

        $this->assertSame($file, implode("\n", $output));
    }
}
