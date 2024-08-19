<?php

namespace Tuncay\PsalmWpTaint\tests\unit;

use PHPUnit\Framework\TestCase;
use Tuncay\PsalmWpTaint\src\PsalmError\PsalmXMLTaintReport;

class PsalmXMLTaintReportTest extends TestCase {
	public function testConstructor() {
		$xmlFilePath = "./tests/res/example.xml";
		$report      = new PsalmXMLTaintReport( $xmlFilePath );

		$expected = [
			"count"  => 3,
			"errors" => [
				[
					"errorType"    => "TaintedHtml",
					"errorPath"    => "/home/tuncay/GitHub/psalm-wp-taint/tests/res/psalm-wp-taint-analysis/files/tainted-plugin/tainted.php",
					"errorMessage" => [
						[ "id" => "\$_POST", "stmt" => "<no known location>" ],
						[
							"id"   => "\$_POST['tainted'] - /home/tuncay/GitHub/psalm-wp-taint/tests/res/psalm-wp-taint-analysis/files/tainted-plugin/tainted.php:7:7",
							"stmt" => "echo \$_POST[\"tainted\"];"
						],
						[
							"id"   => "call to echo - /home/tuncay/GitHub/psalm-wp-taint/tests/res/psalm-wp-taint-analysis/files/tainted-plugin/tainted.php:7:7",
							"stmt" => "echo \$_POST[\"tainted\"];"
						]
					]
				],
				[
					"errorType"    => "TaintedTextWithQuotes",
					"errorPath"    => "/home/tuncay/GitHub/psalm-wp-taint/tests/res/psalm-wp-taint-analysis/files/tainted-plugin/tainted.php",
					"errorMessage" => [
						[ "id" => "\$_POST", "stmt" => "<no known location>" ],
						[
							"id"   => "\$_POST['tainted'] - /home/tuncay/GitHub/psalm-wp-taint/tests/res/psalm-wp-taint-analysis/files/tainted-plugin/tainted.php:7:7",
							"stmt" => "echo \$_POST[\"tainted\"];"
						],
						[
							"id"   => "call to echo - /home/tuncay/GitHub/psalm-wp-taint/tests/res/psalm-wp-taint-analysis/files/tainted-plugin/tainted.php:7:7",
							"stmt" => "echo \$_POST[\"tainted\"];"
						]
					]
				],
				[
					"errorType"    => "TaintedHtml",
					"errorPath"    => "/home/tuncay/GitHub/psalm-wp-taint/tests/res/psalm-wp-taint-analysis/files/tainted-plugin/tainted.php",
					"errorMessage" => [
						[ "id" => "\$_POST", "stmt" => "<no known location>" ],
						[
							"id"   => "\$_POST['taintedTwo'] - /home/tuncay/GitHub/psalm-wp-taint/tests/res/psalm-wp-taint-analysis/files/tainted-plugin/tainted.php:11:11",
							"stmt" => "print_r( \$_POST[\"taintedTwo\"] );"
						],
						[
							"id"   => "call to print_r - /home/tuncay/GitHub/psalm-wp-taint/tests/res/psalm-wp-taint-analysis/files/tainted-plugin/tainted.php:11:11",
							"stmt" => "print_r( \$_POST[\"taintedTwo\"] );"
						]
					]
				]
			]
		];

		$this->assertSame( $expected, $report->reportValues() );

	}
}