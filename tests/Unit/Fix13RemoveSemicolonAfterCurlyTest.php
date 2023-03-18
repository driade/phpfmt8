<?php

use PHPUnit\Framework\TestCase;

class Fix13RemoveSemicolonAfterCurlyTest extends TestCase
{
    public function testItWorks()
    {
        if (PHP_VERSION_ID < 70000) {
            $this->markTestSkipped();
        }
        exec("php " . __DIR__ . "/../../fmt.stub.php -o=- --passes=RemoveSemicolonAfterCurly " . __DIR__ . '/fixtures/twelve.txt', $output);

        $file = file_get_contents(__DIR__ . '/fixtures/twelve.php');
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $output = explode("\n", implode(PHP_EOL, $output));
        }

        $this->assertSame($file, implode("\n", $output));
    }
}
