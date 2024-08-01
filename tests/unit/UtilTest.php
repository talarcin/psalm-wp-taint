<?php

namespace Tuncay\PsalmWpTaint\tests\unit;

use PHPUnit\Framework\TestCase;
use Tuncay\PsalmWpTaint\src\Util;

class UtilTest extends TestCase {
	const string TEST_DIR = "./tests/res/psalm/plugins/";
	const string TEST_DIR2 = "./tests/res/psalm/files";

	public function testGetDirsIn() {
		$expected = [ "./tests/res/psalm/plugins/adiaha-hotel", "./tests/res/psalm/plugins/audio-record" ];
		$actual   = Util::getDirsIn( self::TEST_DIR );

		$this->assertEquals( $expected, $actual );
	}

	public function testScanDirForFiles() {
		$expected = ["./tests/res/psalm/files/test-file.php", "./tests/res/psalm/files/test-file-2.php", "./tests/res/psalm/files/test-file-3.php"];
		$actual = Util::scanDirForPHPFiles(self::TEST_DIR2);

		$this->assertEquals( $expected, $actual );
	}
}