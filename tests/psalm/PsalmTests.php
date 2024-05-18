<?php

use PHPUnit\Framework\TestCase;

final class PsalmTests extends TestCase
{
    public function testPsalmGetPrevNodes()
    {
        exec("./../../vendor/bin/psalm --taint-analysis", $out);
        print_r($out);
    }
}