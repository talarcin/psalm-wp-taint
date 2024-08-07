<?php

namespace Tuncay\PsalmWpTaint\tests\unit;

use PHPUnit\Framework\TestCase;
use Tuncay\PsalmWpTaint\src\PsalmError\PsalmError;
use Tuncay\PsalmWpTaint\src\PsalmError\PsalmErrorCollection;
use Tuncay\PsalmWpTaint\src\PsalmError\PsalmPluginResult;

class PsalmPluginResultTest extends TestCase {
	public function testPsalmErrorsAreEqual() {
		$psalmPluginResultOne = new PsalmPluginResult();
		$psalmPluginResultTwo = new PsalmPluginResult();

		$psalmError               = new PsalmError();
		$psalmError->errorPath    = "test";
		$psalmError->errorType    = "rwesfd";
		$psalmError->errorMessage = [];

		$psalmPluginResultOne->psalmErrors[] = new PsalmError();
		$psalmPluginResultTwo->psalmErrors[] = new PsalmError();

		$this->assertTrue( $psalmPluginResultOne->equals( $psalmPluginResultTwo ) );
	}

	public function testPsalmErrorsAreNotEqual() {
		$psalmPluginResultOne = new PsalmPluginResult();
		$psalmPluginResultTwo = new PsalmPluginResult();

		$psalmErrorOne               = new PsalmError();
		$psalmErrorOne->errorPath    = "test";
		$psalmErrorOne->errorType    = "rwesfd";
		$psalmErrorOne->errorMessage = [];

		$psalmErrorTwo               = new PsalmError();
		$psalmErrorTwo->errorPath    = "testds";
		$psalmErrorTwo->errorType    = "rwesdsfd";
		$psalmErrorTwo->errorMessage = [];

		$psalmPluginResultOne->psalmErrors[] = $psalmErrorOne;

		$psalmPluginResultTwo->psalmErrors[] = $psalmErrorTwo;

		$this->assertFalse($psalmPluginResultOne->equals($psalmPluginResultTwo));
	}
}