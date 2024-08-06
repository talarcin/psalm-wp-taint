<?php

namespace Tuncay\PsalmWpTaint\tests\unit;

use PHPUnit\Framework\TestCase;
use Tuncay\PsalmWpTaint\src\PsalmError\PsalmError;
use Tuncay\PsalmWpTaint\src\PsalmError\PsalmErrorCollection;

class PsalmErrorCollectionTest extends TestCase {
	private PsalmErrorCollection $collection;

	protected function setUp(): void {
		$this->collection = new PsalmErrorCollection();

	}

	public function testExceptionThrownOnWrongType(): void {
		$this->expectException( \UnexpectedValueException::class );

		$this->collection[0] = 1;
	}

	public function testSettingValueAndIsset(): void {
		$expected = new PsalmError();
		$this->collection[0] = new PsalmError();
		$this->collection[]  = $expected;

		$this->assertTrue( isset( $this->collection[0] ) );
		$this->assertSame($expected, $this->collection[1]);
	}

	public function testUnsettingValue(): void {
		$this->collection[0] = new PsalmError();
		$this->assertTrue( isset( $this->collection[0] ) );
		unset( $this->collection[0] );
		$this->assertFalse( isset( $this->collection[0] ) );
	}

	public function testCounting(): void {
		$this->collection[0] = new PsalmError();
		$this->collection[1] = new PsalmError();
		$this->collection[2] = new PsalmError();

		$this->assertSame( 3, count( $this->collection ) );
	}

	/**
	 * @throws \Exception
	 */
	public function testGetIterator(): void {
		$this->collection[0] = new PsalmError();
		$this->collection[1] = new PsalmError();

		foreach ( $this->collection->getIterator() as $error ) {
			$this->assertInstanceOf( PsalmError::class, $error );
		}
	}

	public function testOffsetMustBeInteger(): void {
		$this->expectException( \UnexpectedValueException::class );

		$this->collection["value"] = new PsalmError();
	}
}