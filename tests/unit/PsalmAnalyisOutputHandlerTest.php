<?php

namespace Tuncay\PsalmWpTaint\tests\unit;

use Psalm\Internal\Cli\Psalm;
use Tuncay\PsalmWpTaint\src\PsalmError\PsalmError;
use PHPUnit\Framework\TestCase;
use Tuncay\PsalmWpTaint\src\PsalmAnalysisOutputHandler;
use Tuncay\PsalmWpTaint\src\PsalmError\PsalmErrorCollection;
use Tuncay\PsalmWpTaint\src\PsalmError\PsalmPluginResult;
use Tuncay\PsalmWpTaint\src\PsalmError\PsalmResult;
use Tuncay\PsalmWpTaint\src\PsalmOutputParser;

class PsalmAnalyisOutputHandlerTest extends TestCase {
	protected $psalmAnalyisOutputHandler;

	protected function setUp(): void {
		$this->psalmAnalyisOutputHandler = new PsalmAnalysisOutputHandler();
	}

	public function testOutputHandle(): void {
		$outputs = array(
			"testSlug"      =>
				[
					"count"  => 1,
					"errors" => [
						0 => [
							"errorType"    => "TaintedHtml",
							"errorPath"    => "/home/tuncay/GitHub/wp-test-site/wp-content/plugins/vulnerable-wp-plugin/admin/Options.php",
							"errorMessage" => [
								[ "id" => "\$_POST", "stmt" => "<no known location>" ],
								[
									"id"   => "call to Options::set - wp-content/plugins/vulnerable-wp-plugin/admin/class-vulnerable-plugin-admin.php:134:18",
									"stmt" => "\$options->set(\"ERROR:\" \. \$_POST);"
								],
								[
									"id"   => "Options::set#1 - wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:10:22",
									"stmt" => "public function set(\$field, \$value = null): void"
								],
								[
									"id"   => "\$field - wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:10:22",
									"stmt" => "public function set(\$field, \$value = null): void"
								],
								[
									"id"   => "call to update_option - wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:17:18",
									"stmt" => "update_option(\$field, \$value);"
								],
							],
						],
					],
				],
			"testSlugTwo"   => [
				"count"  => 1,
				"errors" => [
					0 => [
						"errorType"    => "TaintedHtml",
						"errorPath"    => "/home/tuncay/GitHub/wp-test-site/wp-content/plugins/vulnerable-wp-plugin/admin/Options.php",
						"errorMessage" => [
							[ "id" => "\$_POST", "stmt" => "<no known location>" ],
							[
								"id"   => "call to Options::set - wp-content/plugins/vulnerable-wp-plugin/admin/class-vulnerable-plugin-admin.php:134:18",
								"stmt" => "\$options->set(\"ERROR:\" \. \$_POST);"
							],
							[
								"id"   => "Options::set#1 - wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:10:22",
								"stmt" => "public function set(\$field, \$value = null): void"
							],
							[
								"id"   => "\$field - wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:10:22",
								"stmt" => "public function set(\$field, \$value = null): void"
							],
							[
								"id"   => "call to update_option - wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:17:18",
								"stmt" => "update_option(\$field, \$value);"
							],
						],
					],
				],
			],
			"testSlugThree" => [ "count" => 0, "errors" => [] ],
		);

		$expectedPsalmError               = new PsalmError();
		$expectedPsalmError->errorType    = "TaintedHtml";
		$expectedPsalmError->errorPath    = "/home/tuncay/GitHub/wp-test-site/wp-content/plugins/vulnerable-wp-plugin/admin/Options.php";
		$expectedPsalmError->errorMessage = array(
			array(
				"id"   => "\$_POST",
				"stmt" => "<no known location>"
			),
			array(
				"id"   => "call to Options::set - wp-content/plugins/vulnerable-wp-plugin/admin/class-vulnerable-plugin-admin.php:134:18",
				"stmt" => "\$options->set(\"ERROR:\" \. \$_POST);"
			),
			array(
				"id"   => "Options::set#1 - wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:10:22",
				"stmt" => "public function set(\$field, \$value = null): void"
			),
			array(
				"id"   => "\$field - wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:10:22",
				"stmt" => "public function set(\$field, \$value = null): void"
			),
			array(
				"id"   => "call to update_option - wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:17:18",
				"stmt" => "update_option(\$field, \$value);"
			)
		);

		$expectedPsalmErrorCollectionOne   = new PsalmErrorCollection();
		$expectedPsalmErrorCollectionOne[] = $expectedPsalmError;

		$expectedPsalmErrorCollectionTwo   = new PsalmErrorCollection();
		$expectedPsalmErrorCollectionTwo[] = $expectedPsalmError;

		$expectedPluginResultOne = new PsalmPluginResult();
		$expectedPluginResultTwo = new PsalmPluginResult();


		$expectedPluginResultOne->pluginSlug  = "testSlug";
		$expectedPluginResultOne->psalmErrors = $expectedPsalmErrorCollectionOne;
		$expectedPluginResultOne->count       = 1;
		$expectedPluginResultTwo->pluginSlug  = "testSlugTwo";
		$expectedPluginResultTwo->psalmErrors = $expectedPsalmErrorCollectionTwo;
		$expectedPluginResultTwo->count       = 1;


		$expected = new PsalmResult();
		$expected->addResult( $expectedPluginResultOne );
		$expected->addResult( $expectedPluginResultTwo );
		$expected->total               = 3;
		$expected->totalTaintedPlugins = 2;
		$expected->totalNoTaint        = 1;
		$actual                        = $this->psalmAnalyisOutputHandler->handle( new PsalmOutputParser(), $outputs );

		$this->assertTrue( $expected->equals( $actual ) );
	}
}