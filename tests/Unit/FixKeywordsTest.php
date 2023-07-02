<?php

use PHPUnit\Framework\TestCase;

class FixKeywordsTest extends TestCase
{
    public function testItWorks()
    {
        exec("php " . __DIR__ . "/../../fmt.stub.php --psr2 -o=- " . __DIR__ . '/fixtures/fixkeywords.txt', $output);

        // file_put_contents(__DIR__ . '/fixtures/fixkeywords.php', implode("\n", $output));

        $file = file_get_contents(__DIR__ . '/fixtures/fixkeywords.php');

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $output = explode("\n", implode(PHP_EOL, $output));
        }

        $this->assertSame($file, implode("\n", $output));
    }
}
