<?php

use PHPUnit\Framework\TestCase;

class AlignDoubleSlashCommentsTest extends TestCase
{
    public function testItWorks()
    {
        exec("php " . __DIR__ . "/../../fmt.stub.php --passes=AlignDoubleSlashComments -o=- " . __DIR__ . '/fixtures/align_double_slash_comments.txt', $output);

        // file_put_contents(__DIR__ . '/fixtures/align_double_slash_comments.php', implode("\n", $output));

        $file = file_get_contents(__DIR__ . '/fixtures/align_double_slash_comments.php');

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $output = explode("\n", implode(PHP_EOL, $output));
        }

        $this->assertSame($file, implode("\n", $output));
    }
}
