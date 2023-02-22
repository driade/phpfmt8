<?php

use PHPUnit\Framework\TestCase;

/** @see https://www.php-fig.org/psr/psr-2/ **/
class PSR2ExampleTest extends TestCase
{
    public function testItParsesAGenericFile()
    {
        exec("php " . __DIR__ . "/../../fmt.stub.php --psr2 -o=- " . __DIR__ . '/fixtures/seven.txt', $output);

        $file = file_get_contents(__DIR__ . '/fixtures/seven.php');
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $output = explode("\n", implode(PHP_EOL, $output));
        }

        // file_put_contents(__DIR__ . '/fixtures/seven.php', implode("\n", $output));

        $this->assertSame($file, implode("\n", $output));
    }
}
