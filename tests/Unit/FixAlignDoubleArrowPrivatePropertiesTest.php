<?php

use PHPUnit\Framework\TestCase;

class FixAlignDoubleArrowPrivatePropertiesTest extends TestCase
{
    public function testItWorks()
    {
        exec("php " . __DIR__ . "/../../fmt.stub.php --passes=AlignDoubleArrow -o=- " . __DIR__ . '/fixtures/fourteen.txt', $output);

        $file = file_get_contents(__DIR__ . '/fixtures/fourteen.php');

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $output = explode("\n", implode(PHP_EOL, $output));
        }

        $this->assertSame($file, implode("\n", $output));
    }
}
