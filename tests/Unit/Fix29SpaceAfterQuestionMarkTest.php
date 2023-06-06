<?php

use PHPUnit\Framework\TestCase;

class Fix29SpaceAfterQuestionMarkTest extends TestCase
{
    public function testItWorks()
    {
        if (PHP_VERSION_ID < 71000) {
            $this->markTestSkipped();
        }
        exec("php " . __DIR__ . "/../../fmt.stub.php --psr2 --exclude=PSR2ModifierVisibilityStaticOrder -o=- " . __DIR__ . '/fixtures/twentynine.txt', $output);

        $file = file_get_contents(__DIR__ . '/fixtures/twentynine.php');

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $output = explode("\n", implode(PHP_EOL, $output));
        }

        // file_put_contents(__DIR__ . '/fixtures/twentynine.php', implode("\n", $output));

        $this->assertSame($file, implode("\n", $output));
    }
}
