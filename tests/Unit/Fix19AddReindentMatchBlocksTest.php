<?php

use PHPUnit\Framework\TestCase;

class Fix19AddReindentMatchBlocksTest extends TestCase
{
    public function testItWorks()
    {
        if (PHP_VERSION_ID < 80000) {
            $this->markTestSkipped();
        }

        exec("php " . __DIR__ . "/../../fmt.stub.php --exclude=Reindent --passes=ReindentMatchBlocks -o=- " . __DIR__ . '/fixtures/nineteen.txt', $output);

        $file = file_get_contents(__DIR__ . '/fixtures/nineteen.php');

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $output = explode("\n", implode(PHP_EOL, $output));
        }

        // file_put_contents(__DIR__ . '/fixtures/nineteen.php', implode("\n", $output));

        $this->assertSame($file, implode("\n", $output));
    }
}
