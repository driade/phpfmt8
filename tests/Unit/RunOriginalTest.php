<?php

use PHPUnit\Framework\TestCase;

class RunOriginalTest extends TestCase
{
    public function testItWorks()
    {
        if (PHP_VERSION_ID > 70400) {
            $this->markTestSkipped();
        }

        exec("php " . __DIR__ . "/../run_all_tests.php -v", $output, $retvar);

        if ($retvar === 1) {
            echo implode(PHP_EOL, $output);
            throw new \Exception;
        }
    }
}
