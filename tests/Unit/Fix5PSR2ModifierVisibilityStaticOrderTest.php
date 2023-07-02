<?php

use PHPUnit\Framework\TestCase;

class Fix5PSR2ModifierVisibilityStaticOrderTest extends TestCase
{
    public function testItParsesAGenericFile()
    {
        if (PHP_VERSION_ID < 70400) {
            $this->markTestSkipped();
        }
        exec("php " . __DIR__ . "/../../fmt.stub.php --passes=PSR2ModifierVisibilityStaticOrder -o=- " . __DIR__ . '/fixtures/six.txt', $output);

        $file = file_get_contents(__DIR__ . '/fixtures/six.php');
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $output = explode("\n", implode(PHP_EOL, $output));
        }

        $this->assertSame($file, implode("\n", $output));
    }
}
