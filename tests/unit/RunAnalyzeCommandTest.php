<?php

namespace Tuncay\PsalmWpTaint\tests\unit;

use PHPUnit\Framework\TestCase;
use Tuncay\PsalmWpTaint\src\Cli\RunAnalyzeCommand;

class RunAnalyzeCommandTest extends TestCase {
	public function testRunAnalyzeCommandParseCorrectArgs(): void {
		$args = [ "run", "outputName", "optionalPluginsDir", "./tests/res/test.csv", "-i", "-n" ];

		$command  = new RunAnalyzeCommand();
		$actual   = $command->parseCommand( $args );
		$expected = [
			"help"             => null,
			"version"          => "",
			"verbosity"        => 0,
			"outputFilename"   => "outputName",
			"pluginsDirectory" => "optionalPluginsDir",
			"pluginCsvFile"    => "./tests/res/test.csv",
			"install"          => true,
			"analyze"          => false,
		];

		$this->assertEquals( $expected, $actual->values() );
	}

	public function testRunAnalyzeCommandParseWrongArgs(): void {
		$args    = [ "run", "outputName", "optionalPluginsDir", "./tests/res/", "-i", "-n" ];
		$command = new RunAnalyzeCommand();
		$actual  = $command->parseCommand( $args );

		$this->assertFalse( $actual );

		$args    = [ "run", "outputName", "optionalPluginsDir", "./tests/res/test-empt.json", "-i", "-n" ];
		$command = new RunAnalyzeCommand();
		$actual  = $command->parseCommand( $args );

		$this->assertFalse( $actual );

		$args    = [ "run", "outputName", "optionalPluginsDir", "-i", "-n" ];
		$command = new RunAnalyzeCommand();
		$actual  = $command->parseCommand( $args );

		$this->assertFalse( $actual );
	}
}