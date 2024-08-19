<?php

namespace Tuncay\PsalmWpTaint\tests\unit;

use Tuncay\PsalmWpTaint\src\PsalmError\PsalmError;
use PHPUnit\Framework\TestCase;
use Tuncay\PsalmWpTaint\src\PsalmOutputParser;

class PsalmOutputParserTest extends TestCase {
	protected PsalmOutputParser $psalmOutputParser;

	protected function setUp(): void {
		$this->psalmOutputParser = new PsalmOutputParser();
	}

	public function testParseNoErrors(): void {
		$report = [
			"count"  => 0,
			"errors" => [],
		];

		$this->assertFalse( $this->psalmOutputParser->parsePsalmReport( $report ) );
	}

}
