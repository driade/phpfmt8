<?php

use PHPUnit\Framework\TestCase;

class FixIndentCaseInsideEnumsTest extends TestCase
{
    public function testItWorks()
    {
        if (PHP_VERSION_ID < 80100) {
            $this->markTestSkipped();
        }

        exec("php " . __DIR__ . "/../../fmt.stub.php --passes=ReindentEnumBlocks -o=- " . __DIR__ . '/fixtures/thirteen.txt', $output);

        $file = file_get_contents(__DIR__ . '/fixtures/thirteen.php');

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $output = explode("\n", implode(PHP_EOL, $output));
        }

        $this->assertSame($file, implode("\n", $output));
    }
}
