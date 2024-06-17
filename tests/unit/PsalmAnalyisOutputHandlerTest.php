<?php

namespace Tuncay\PsalmWpTaint\tests\unit;

use PHPUnit\Framework\TestCase;
use Tuncay\PsalmWpTaint\src\PsalmAnalysisOutputHandler;
use Tuncay\PsalmWpTaint\src\PsalmError;
use Tuncay\PsalmWpTaint\src\PsalmOutputParser;

class PsalmAnalyisOutputHandlerTest extends TestCase
{
    protected $psalmAnalyisOutputHandler;

    protected function setUp(): void
    {
        $this->psalmAnalyisOutputHandler = new PsalmAnalysisOutputHandler();
    }

    public function testOutputHandle(): void
    {
        $outputs = array(
            "testSlug" =>
                [
                    "",
                    "ERROR: TaintedHtml",
                    "at /home/tuncay/GitHub/wp-test-site/wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:17:18",
                    "Detected tainted HTML (see https://psalm.dev/245)",
                    "  \$_POST",
                    "    <no known location>",
                    "",
                    "  call to Options::set - wp-content/plugins/vulnerable-wp-plugin/admin/class-vulnerable-plugin-admin.php:134:18",
                    "                        \$options->set(\"ERROR:\" \. \$_POST);",
                    "",
                    "  Options::set#1 - wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:10:22",
                    "        public function set(\$field, \$value = null): void",
                    "",
                    "  \$field - wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:10:22",
                    "        public function set(\$field, \$value = null): void",
                    "",
                    "  call to update_option - wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:17:18",
                    "                        update_option(\$field, \$value);",
                    "",
                    "",
                    "",
                    "fatal: not a git repository (or any of the parent directories): .git",
                    "------------------------------",
                    "5 errors found",
                    "------------------------------",
                    "",
                    "Checks took 1.63 seconds and used 221.030MB of memory",
                    "Psalm was able to infer types for 82.4017% of the codebase"
                ],
            "testSlugTwo" => [
                "",
                "ERROR: TaintedHtml",
                "at /home/tuncay/GitHub/wp-test-site/wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:17:18",
                "Detected tainted HTML (see https://psalm.dev/245)",
                "  \$_POST",
                "    <no known location>",
                "",
                "  call to Options::set - wp-content/plugins/vulnerable-wp-plugin/admin/class-vulnerable-plugin-admin.php:134:18",
                "                        \$options->set(\"ERROR:\" \. \$_POST);",
                "",
                "  Options::set#1 - wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:10:22",
                "        public function set(\$field, \$value = null): void",
                "",
                "  \$field - wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:10:22",
                "        public function set(\$field, \$value = null): void",
                "",
                "  call to update_option - wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:17:18",
                "                        update_option(\$field, \$value);",
                "",
                "",
                "",
                "fatal: not a git repository (or any of the parent directories): .git",
                "------------------------------",
                "5 errors found",
                "------------------------------",
                "",
                "Checks took 1.63 seconds and used 221.030MB of memory",
                "Psalm was able to infer types for 82.4017% of the codebase"
            ]
        );

        $expectedPsalmError = new PsalmError();
        $expectedPsalmError->errorType = "TaintedHtml";
        $expectedPsalmError->errorPath = "/home/tuncay/GitHub/wp-test-site/wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:17:18";
        $expectedPsalmError->errorMessage = array(
            array(
                "id" => "\$_POST",
                "stmt" => "<no known location>"
            ),
            array(
                "id" => "call to Options::set - wp-content/plugins/vulnerable-wp-plugin/admin/class-vulnerable-plugin-admin.php:134:18",
                "stmt" => "\$options->set(\"ERROR:\" \. \$_POST);"
            ),
            array(
                "id" => "Options::set#1 - wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:10:22",
                "stmt" => "public function set(\$field, \$value = null): void"
            ),
            array(
                "id" => "\$field - wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:10:22",
                "stmt" => "public function set(\$field, \$value = null): void"
            ),
            array(
                "id" => "call to update_option - wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:17:18",
                "stmt" => "update_option(\$field, \$value);"
            )
        );

        $expected = array(
            "testSlug" => [
                "count" => 1,
                "errors" => [
                    $expectedPsalmError
                ]
            ],
            "testSlugTwo" => [
                "count" => 1,
                "errors" => [
                    $expectedPsalmError
                ]
            ]
        );

        $actual = $this->psalmAnalyisOutputHandler->handle(new PsalmOutputParser(), $outputs);

        print_r($actual);

        $this->assertPluginResultsAreSame($expected, $actual);
    }

    protected function assertPluginResultsAreSame(array $expected, array $actual): void
    {
        foreach ($expected as $key => $value) {
            $this->assertArrayHasKey($key, $actual);
            $this->assertErrorsAreSame($value, $actual[$key]);
        }
    }

    protected function assertErrorsAreSame($expected, $actual): void
    {
        $this->assertSame($expected["count"], $actual["count"]);

        $expectedErrors = $expected["errors"];
        $actualErrors = $actual["errors"];

        foreach ($expectedErrors as $key => $expectedError) {
            $this->assertTrue($expectedError->equals($actualErrors[$key]));
        }
    }
}