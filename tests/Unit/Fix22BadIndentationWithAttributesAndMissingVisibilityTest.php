<?php

use PHPUnit\Framework\TestCase;

class Fix22BadIndentationWithAttributesAndMissingVisibilityTest extends TestCase
{
    public function testItWorks()
    {
        if (PHP_VERSION_ID < 80000) {
            $this->markTestSkipped();
        }
        exec("php " . __DIR__ . "/../../fmt.stub.php --psr2 -o=- " . __DIR__ . '/fixtures/twentytwo.txt', $output);

        $file = file_get_contents(__DIR__ . '/fixtures/twentytwo.php');

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $output = explode("\n", implode(PHP_EOL, $output));
        }

        // file_put_contents(__DIR__ . '/fixtures/twentytwo.php', implode("\n", $output));

        $this->assertSame($file, implode("\n", $output));
    }
}
