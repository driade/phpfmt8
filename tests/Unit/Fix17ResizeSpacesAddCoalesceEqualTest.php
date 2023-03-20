<?php

use PHPUnit\Framework\TestCase;

class Fix17ResizeSpacesAddCoalesceEqualTest extends TestCase
{
    public function testItWorks()
    {
        if (PHP_VERSION_ID < 70400) {
            $this->markTestSkipped();
        }

        exec("php " . __DIR__ . "/../../fmt.stub.php --passes=AutoSemicolon -o=- " . __DIR__ . '/fixtures/seventeen.txt', $output);

        $file = file_get_contents(__DIR__ . '/fixtures/seventeen.php');

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $output = explode("\n", implode(PHP_EOL, $output));
        }

        $this->assertSame($file, implode("\n", $output));
    }
}
