<?php

namespace Tuncay\PsalmWpTaint\tests\unit;

use PHPUnit\Framework\TestCase;
use Tuncay\PsalmWpTaint\src\Util;

class UtilTest extends TestCase
{
    const string TEST_DIR = "./tests/res/psalm/plugins/";

    public function testGetDirsIn()
    {
        $expected = ["./tests/res/psalm/plugins/adiaha-hotel", "./tests/res/psalm/plugins/audio-record"];
        $actual = Util::getDirsIn(self::TEST_DIR);

        $this->assertEquals($expected, $actual);
    }
}