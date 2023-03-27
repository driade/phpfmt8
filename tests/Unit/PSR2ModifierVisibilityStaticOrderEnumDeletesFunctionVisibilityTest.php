<?php

use PHPUnit\Framework\TestCase;

class PSR2ModifierVisibilityStaticOrderEnumDeletesFunctionVisibilityTest extends TestCase
{
    public function testItWorks()
    {
        if (PHP_VERSION_ID < 80100) {
            $this->markTestSkipped();
        }
        
        exec("php " . __DIR__ . "/../../fmt.stub.php --passes=PSR2ModifierVisibilityStaticOrder -o=- " . __DIR__ . '/fixtures/eighteen.txt', $output);

        $file = file_get_contents(__DIR__ . '/fixtures/eighteen.php');

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $output = explode("\n", implode(PHP_EOL, $output));
        }

        // file_put_contents(__DIR__ . '/fixtures/eighteen.php', implode("\n", $output));

        $this->assertSame($file, implode("\n", $output));
    }
}
