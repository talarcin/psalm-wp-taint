<?php

namespace Tuncay\PsalmWpTaint\tests\unit;

use PHPUnit\Framework\TestCase;
use Tuncay\PsalmWpTaint\src\Cli\RunAnalyzeCommand;

class RunAnalyzeCommandTest extends TestCase {
	public function testRunAnalyzeCommandParseCorrectArgs(): void {
		$args = [ "run", "./tests/res/test.csv", "outputName", "optionalPluginsDir", "-i", "-n" ];

		$command  = new RunAnalyzeCommand();
		$actual   = $command->parseCommand( $args );
		$expected = [
			"help"             => null,
			"version"          => "",
			"verbosity"        => 0,
			"pluginCsvList"    => "./tests/res/test.csv",
			"outputFilename"   => "outputName",
			"pluginsDirectory" => "optionalPluginsDir",
			"install"          => true,
			"analyze"          => false,
		];

		$this->assertEquals( $expected, $actual->values() );
	}

	public function testRunAnalyzeCommandParseWrongArgs(): void {
		$args    = ["run", "./tests/res/", "outputName", "optionalPluginsDir", "-i", "-n" ];
		$command = new RunAnalyzeCommand();
		$actual  = $command->parseCommand( $args );

		$this->assertFalse( $actual );

		$args    = ["run", "./tests/res/test-empt.json", "outputName", "optionalPluginsDir", "-i", "-n" ];
		$command = new RunAnalyzeCommand();
		$actual  = $command->parseCommand( $args );

		$this->assertFalse( $actual );
	}
}