<?php

use PHPUnit\Framework\TestCase;

class Fix19AddReindentBreaksMatchTest extends TestCase
{
    public function testItWorks()
    {
        if (PHP_VERSION_ID < 80000) {
            $this->markTestSkipped();
        }

        exec("php " . __DIR__ . "/../../fmt.stub.php --passes=Reindent,ReindentMatchBlocks -o=- " . __DIR__ . '/fixtures/nineteen_two.txt', $output);

        $file = file_get_contents(__DIR__ . '/fixtures/nineteen_two.php');

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $output = explode("\n", implode(PHP_EOL, $output));
        }

        // file_put_contents(__DIR__ . '/fixtures/nineteen_two.php', implode("\n", $output));

        $this->assertSame($file, implode("\n", $output));
    }
}
