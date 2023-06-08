<?php

use PHPUnit\Framework\TestCase;

class Fix342SemicolonAfterCurlyTest extends TestCase
{
    public function testItWorks()
    {
        exec("php " . __DIR__ . "/../../fmt.stub.php --psr2 --passes=RemoveSemicolonAfterCurly,AutoSemicolon -o=- " . __DIR__ . '/fixtures/thirtyfour_2.txt', $output);

        $file = file_get_contents(__DIR__ . '/fixtures/thirtyfour_2.php');

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $output = explode("\n", implode(PHP_EOL, $output));
        }

        // file_put_contents(__DIR__ . '/fixtures/thirtyfour_2.php', implode("\n", $output));

        $this->assertSame($file, implode("\n", $output));
    }
}
