<?php

namespace Tuncay\PsalmWpTaint\tests\integration;

use PHPUnit\Framework\TestCase;
use SimpleXMLElement;
use Tuncay\PsalmWpTaint\src\Cli\PsalmWpTaintAnalysis;
use Tuncay\PsalmWpTaint\src\Cli\RunAnalyzeCommand;

class PsalmWpTaintAnalysisTest extends TestCase
{

	protected function setUp(): void
	{
		if (file_exists("./add-actions-map.json")) {
			$file = fopen("./add-actions-map.json", "w");
			fwrite($file, "");
		}
	}

	public function testRunningAnalysis(): void
	{
		$args     = ["run", "output", "./tests/res/psalm-wp-taint-analysis/files/"];
		$command  = new RunAnalyzeCommand();
		$parser   = $command->parseCommand($args);
		$expected = [
			"help"             => null,
			"version"          => "",
			"verbosity"        => 0,
			"outputFilename"   => "output",
			"pluginsDirectory" => "./tests/res/psalm-wp-taint-analysis/files/",
			"pluginCsvFile"    => null,
			"install"          => null,
			"analyze"          => true,
		];

		$this->assertSame($expected, $parser->values());

		PsalmWpTaintAnalysis::run($command);

		$expectedFuzzableActions = ["admin_post_tainted", "admin_post_tainted_two"];
		$actual                  = json_decode(file_get_contents("./psalm-result/actions_to_fuzz-output.json"));

		$this->assertSame($expectedFuzzableActions, $actual);
	}

	public function testRunningAnalysisWithMissingPsalmXMLFile(): void
	{
		$this->expectOutputString("File \"psalm.xml\" not found. Please make sure psalm is setup correctly.\n");
		rename("./psalm.xml", "./renamed.xml");

		$args     = ["run", "output", "./tests/res/psalm-wp-taint-analysis/files/"];
		$command  = new RunAnalyzeCommand();
		$parser   = $command->parseCommand($args);
		$expected = [
			"help"             => null,
			"version"          => "",
			"verbosity"        => 0,
			"outputFilename"   => "output",
			"pluginsDirectory" => "./tests/res/psalm-wp-taint-analysis/files/",
			"pluginCsvFile"    => null,
			"install"          => null,
			"analyze"          => true,
		];

		$this->assertSame($expected, $parser->values());

		PsalmWpTaintAnalysis::run($command);
		rename("./renamed.xml", "./psalm.xml");
	}
}
