<?php

use PHPUnit\Framework\TestCase;

class AddSpaceAroundParenthesesTest extends TestCase
{
    public function testItWorks()
    {
        exec("php " . __DIR__ . "/../../fmt.stub.php --passes=SpaceAroundParentheses -o=- " . __DIR__ . '/fixtures/addspacearoundparenthesestest.txt', $output);

        $file = file_get_contents(__DIR__ . '/fixtures/addspacearoundparenthesestest.php');

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $output = explode("\n", implode(PHP_EOL, $output));
        }

        // file_put_contents(__DIR__ . '/fixtures/addspacearoundparenthesestest.php', implode("\n", $output));

        $this->assertSame($file, implode("\n", $output));
    }
}
