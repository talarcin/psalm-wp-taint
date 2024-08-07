<?php

namespace Tuncay\PsalmWpTaint\tests\unit;

use PHPUnit\Framework\TestCase;
use Tuncay\PsalmWpTaint\src\PsalmError\PsalmPluginResult;
use Tuncay\PsalmWpTaint\src\PsalmError\PsalmResult;

class PsalmResultTest extends TestCase {
	public function testGetResults(): void {
		$psalmResults = new PsalmResult();

		$this->assertSame( [], $psalmResults->getResults() );

		$psalmResults->addResult( new PsalmPluginResult( "test", 2 ) );

		$this->assertSame( 1, count( $psalmResults->getResults() ) );
	}

	public function testPluginResultsAreEqual(): void {
		$psalmResultsOne = new PsalmResult();
		$psalmResultsTwo = new PsalmResult();

		$psalmResultsOne->addResult( new PsalmPluginResult( "test", 2 ) );
		$psalmResultsTwo->addResult( new PsalmPluginResult( "test", 2 ) );

		$this->assertTrue( $psalmResultsOne->equals( $psalmResultsTwo ) );
	}

	public function testPluginResultsAreNotEqual(): void {
		$psalmResultsOne = new PsalmResult();
		$psalmResultsTwo = new PsalmResult();

		$psalmResultsOne->addResult( new PsalmPluginResult( "test", 2 ) );
		$psalmResultsTwo->addResult( new PsalmPluginResult( "tesst", 3 ) );

		$this->assertFalse( $psalmResultsOne->equals( $psalmResultsTwo ) );
	}
}