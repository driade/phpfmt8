<?php

use PHPUnit\Framework\TestCase;

class Fix43PSR2CurlyOpenNextLineTest extends TestCase
{
    public function testItWorks()
    {
        exec("php " . __DIR__ . "/../../fmt.stub.php --psr2 -o=- " . __DIR__ . '/fixtures/fix43psr2curlyopennextlinetest.txt', $output);

        $file = file_get_contents(__DIR__ . '/fixtures/fix43psr2curlyopennextlinetest.php');

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $output = explode("\n", implode(PHP_EOL, $output));
        }

        $this->assertSame($file, implode("\n", $output));
    }
}
