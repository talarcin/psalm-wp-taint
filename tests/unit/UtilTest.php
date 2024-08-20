<?php

namespace Tuncay\PsalmWpTaint\tests\unit;

use PHPUnit\Framework\TestCase;
use Tuncay\PsalmWpTaint\src\Util;

class UtilTest extends TestCase
{
	const TEST_DIR_WITH_FSLASH = "./tests/res/psalm/plugins/";
	const TEST_DIR_WITHOUT_FSLASH = "./tests/res/psalm/plugins";
	const TEST_DIR2 = "./tests/res/psalm/files";


	protected function tearDown(): void
	{
		$this->rewindXML();
	}

	public function testGetDirsInIfBranch(): void
	{
		$expected = ["./tests/res/psalm/plugins/adiaha-hotel", "./tests/res/psalm/plugins/audio-record"];
		$actual   = Util::getDirsIn(self::TEST_DIR_WITH_FSLASH);

		$this->assertEquals($expected, $actual);
	}
	public function testGetDirsInElseBranch(): void
	{
		$expected = ["./tests/res/psalm/plugins/adiaha-hotel", "./tests/res/psalm/plugins/audio-record"];
		$actual   = Util::getDirsIn(self::TEST_DIR_WITHOUT_FSLASH);

		$this->assertEquals($expected, $actual);
	}

	public function testScanDirForFiles(): void
	{
		$expected = ["./tests/res/psalm/files/test-file.php", "./tests/res/psalm/files/test-file-2.php", "./tests/res/psalm/files/test-file-3.php"];
		$actual = Util::scanDirForPHPFiles(self::TEST_DIR2);

		$this->assertEquals($expected, $actual);
	}

	public function testChangePsalmProjectDirWithoutDirectoryChild(): void
	{
		$expected = "./tests/res/psalm/plugins/audio-record";
		Util::changePsalmProjectDir("./tests/res/psalm/plugins/audio-record", "./tests/res/psalm/psalm.xml");
		$actual = simplexml_load_file("./tests/res/psalm/psalm.xml")->projectFiles->directory["name"];

		$this->assertEquals($expected, $actual);
	}
	public function testChangePsalmProjectDirWithoutNameAttribute(): void
	{
		$expected = "./tests/res/psalm/plugins/audio-record";

		$xml = simplexml_load_file("./tests/res/psalm/psalm.xml");

		$xml->projectFiles->addChild("directory");

		$xml->asXML("./tests/res/psalm/psalm.xml");

		Util::changePsalmProjectDir("./tests/res/psalm/plugins/audio-record", "./tests/res/psalm/psalm.xml");
		$actual = simplexml_load_file("./tests/res/psalm/psalm.xml")->projectFiles->directory["name"];

		$this->assertEquals($expected, $actual);
	}


	private function rewindXML(): void
	{
		$xml = simplexml_load_file("./tests/res/psalm/psalm.xml");

		$xml->projectFiles = null;
		$xml->asXML("./tests/res/psalm/psalm.xml");
	}
}
