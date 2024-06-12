<?php

namespace Tuncay\PsalmWpTaint\tests\unit;

use PHPUnit\Framework\TestCase;
use Tuncay\PsalmWpTaint\src\PsalmError;
use Tuncay\PsalmWpTaint\src\PsalmOutputParser;

class PsalmOutputParserTest extends TestCase
{
    protected PsalmOutputParser $psalmOutputParser;

    protected function setUp(): void
    {
        $this->psalmOutputParser = new PsalmOutputParser();
    }

    public function testSplitPsalmOutputIntoErrorMessagesWithOneError(): void
    {
        $output = [
            "Target PHP version: 8.3 (inferred from current PHP version) Enabled extensions: simplexml.",
            "",
            "Scanning files...",
            "",
            "79 / 79...Getting stub files...",
            "115 / 115...",
            "",
            "Analyzing files...",
            "",
            "░░░░░░░░░░░░░░░░░░░",
            "",
            "ERROR: TaintedHtml",
            "at /home/tuncay/GitHub/wp-test-site/wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:17:18",
            "Detected tainted HTML (see https://psalm.dev/245)",
            "  \$_POST",
            "    <no known location>",
            "",
            "  call to Options::set - wp-content/plugins/vulnerable-wp-plugin/admin/class-vulnerable-plugin-admin.php:134:18",
            "                        \$options->set(\$_POST);",
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
            "1 error found",
            "------------------------------",
            "",
            "Checks took 1.63 seconds and used 221.030MB of memory",
            "Psalm was able to infer types for 82.4017% of the codebase"
        ];

        $expected = array(
            0 => [
                "ERROR: TaintedHtml",
                "at /home/tuncay/GitHub/wp-test-site/wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:17:18",
                "Detected tainted HTML (see https://psalm.dev/245)",
                "\$_POST",
                "<no known location>",
                "",
                "call to Options::set - wp-content/plugins/vulnerable-wp-plugin/admin/class-vulnerable-plugin-admin.php:134:18",
                "\$options->set(\$_POST);",
                "",
                "Options::set#1 - wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:10:22",
                "public function set(\$field, \$value = null): void",
                "",
                "\$field - wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:10:22",
                "public function set(\$field, \$value = null): void",
                "",
                "call to update_option - wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:17:18",
                "update_option(\$field, \$value);"
            ]
        );

        $actual = $this->psalmOutputParser->splitPsalmOutputIntoErrorMessages($output);

        $this->assertSame($expected, $actual);
    }

    public function testSplitPsalmOutputIntoErrorMessagesWithMultipleErrors(): void
    {
        $output = [
            "Target PHP version: 8.3 (inferred from current PHP version) Enabled extensions: simplexml.",
            "",
            "Scanning files...",
            "",
            "79 / 79...Getting stub files...",
            "115 / 115...",
            "",
            "Analyzing files...",
            "",
            "░░░░░░░░░░░░░░░░░░░",
            "",
            "ERROR: TaintedHtml",
            "at /home/tuncay/GitHub/wp-test-site/wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:14:26",
            "Detected tainted HTML (see https://psalm.dev/245)",
            "  \$_POST",
            "    <no known location>",
            "",
            "  call to Options::set - wp-content/plugins/vulnerable-wp-plugin/admin/class-vulnerable-plugin-admin.php:134:18",
            "                        \$options->set(\$_POST);",
            "",
            "  Options::set#1 - wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:10:22",
            "        public function set(\$field, \$value = null): void",
            "",
            "  \$field - wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:10:22",
            "        public function set(\$field, \$value = null): void",
            "",
            "  arrayvalue-fetch - wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:13:13",
            "                        foreach (\$field as \$name => \$val) {",
            "",
            "  \$val - wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:13:32",
            "                        foreach (\$field as \$name => \$val) {",
            "",
            "  call to update_option - wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:14:26",
            "                                update_option(\$name, \$val);",
            "",
            "",
            "",
            "ERROR: TaintedHtml",
            "at /home/tuncay/GitHub/wp-test-site/wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:17:18",
            "Detected tainted HTML (see https://psalm.dev/245)",
            "  \$_POST",
            "    <no known location>",
            "",
            "  call to Options::set - wp-content/plugins/vulnerable-wp-plugin/admin/class-vulnerable-plugin-admin.php:134:18",
            "            \$options->set(\$_POST);",
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
            "ERROR: TaintedHtml",
            "at /home/tuncay/GitHub/wp-test-site/wp-content/plugins/vulnerable-wp-plugin/admin/class-vulnerable-plugin-admin.php:146:9",
            "Detected tainted HTML (see https://psalm.dev/245)",
            "  \$_POST",
            "    <no known location>",
            "",
            "  \$_POST['vulnerable_plugin_reflected_xss'] - wp-content/plugins/vulnerable-wp-plugin/admin/class-vulnerable-plugin-admin.php:146:9",
            "                        echo \$_POST['vulnerable_plugin_reflected_xss'];",
            "",
            "  call to echo - wp-content/plugins/vulnerable-wp-plugin/admin/class-vulnerable-plugin-admin.php:146:9",
            "                        echo \$_POST['vulnerable_plugin_reflected_xss'];",
            "",
            "",
            "",
            "ERROR: TaintedTextWithQuotes",
            "at /home/tuncay/GitHub/wp-test-site/wp-content/plugins/vulnerable-wp-plugin/admin/class-vulnerable-plugin-admin.php:146:9",
            "Detected tainted text with possible quotes (see https://psalm.dev/274)",
            "  \$_POST",
            "    <no known location>",
            "",
            "  \$_POST['vulnerable_plugin_reflected_xss'] - wp-content/plugins/vulnerable-wp-plugin/admin/class-vulnerable-plugin-admin.php:146:9",
            "                        echo \$_POST['vulnerable_plugin_reflected_xss'];",
            "",
            "  call to echo - wp-content/plugins/vulnerable-wp-plugin/admin/class-vulnerable-plugin-admin.php:146:9",
            "                        echo \$_POST['vulnerable_plugin_reflected_xss'];",
            "",
            "",
            "",
            "ERROR: TaintedFile",
            "at /home/tuncay/GitHub/wp-test-site/wp-content/plugins/vulnerable-wp-plugin/admin/class-vulnerable-plugin-admin.php:158:28",
            "Detected tainted file handling (see https://psalm.dev/255)",
            "  \$_POST",
            "    <no known location>",
            "",
            "  \$_POST['vulnerable_plugin_arbitrary_file_read'] - wp-content/plugins/vulnerable-wp-plugin/admin/class-vulnerable-plugin-admin.php:158:28",
            "                                echo file_get_contents(\$_POST['vulnerable_plugin_arbitrary_file_read']);",
            "",
            "  call to file_get_contents - wp-content/plugins/vulnerable-wp-plugin/admin/class-vulnerable-plugin-admin.php:158:28",
            "                                echo file_get_contents(\$_POST['vulnerable_plugin_arbitrary_file_read']);",
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
        ];
        $expected = array(
            0 => [
                "ERROR: TaintedHtml",
                "at /home/tuncay/GitHub/wp-test-site/wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:14:26",
                "Detected tainted HTML (see https://psalm.dev/245)",
                "\$_POST",
                "<no known location>",
                "",
                "call to Options::set - wp-content/plugins/vulnerable-wp-plugin/admin/class-vulnerable-plugin-admin.php:134:18",
                "\$options->set(\$_POST);",
                "",
                "Options::set#1 - wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:10:22",
                "public function set(\$field, \$value = null): void",
                "",
                "\$field - wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:10:22",
                "public function set(\$field, \$value = null): void",
                "",
                "arrayvalue-fetch - wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:13:13",
                "foreach (\$field as \$name => \$val) {",
                "",
                "\$val - wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:13:32",
                "foreach (\$field as \$name => \$val) {",
                "",
                "call to update_option - wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:14:26",
                "update_option(\$name, \$val);",
            ],
            1 => [
                "ERROR: TaintedHtml",
                "at /home/tuncay/GitHub/wp-test-site/wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:17:18",
                "Detected tainted HTML (see https://psalm.dev/245)",
                "\$_POST",
                "<no known location>",
                "",
                "call to Options::set - wp-content/plugins/vulnerable-wp-plugin/admin/class-vulnerable-plugin-admin.php:134:18",
                "\$options->set(\$_POST);",
                "",
                "Options::set#1 - wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:10:22",
                "public function set(\$field, \$value = null): void",
                "",
                "\$field - wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:10:22",
                "public function set(\$field, \$value = null): void",
                "",
                "call to update_option - wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:17:18",
                "update_option(\$field, \$value);",
            ],
            2 => [
                "ERROR: TaintedHtml",
                "at /home/tuncay/GitHub/wp-test-site/wp-content/plugins/vulnerable-wp-plugin/admin/class-vulnerable-plugin-admin.php:146:9",
                "Detected tainted HTML (see https://psalm.dev/245)",
                "\$_POST",
                "<no known location>",
                "",
                "\$_POST['vulnerable_plugin_reflected_xss'] - wp-content/plugins/vulnerable-wp-plugin/admin/class-vulnerable-plugin-admin.php:146:9",
                "echo \$_POST['vulnerable_plugin_reflected_xss'];",
                "",
                "call to echo - wp-content/plugins/vulnerable-wp-plugin/admin/class-vulnerable-plugin-admin.php:146:9",
                "echo \$_POST['vulnerable_plugin_reflected_xss'];",
            ],
            3 => [
                "ERROR: TaintedTextWithQuotes",
                "at /home/tuncay/GitHub/wp-test-site/wp-content/plugins/vulnerable-wp-plugin/admin/class-vulnerable-plugin-admin.php:146:9",
                "Detected tainted text with possible quotes (see https://psalm.dev/274)",
                "\$_POST",
                "<no known location>",
                "",
                "\$_POST['vulnerable_plugin_reflected_xss'] - wp-content/plugins/vulnerable-wp-plugin/admin/class-vulnerable-plugin-admin.php:146:9",
                "echo \$_POST['vulnerable_plugin_reflected_xss'];",
                "",
                "call to echo - wp-content/plugins/vulnerable-wp-plugin/admin/class-vulnerable-plugin-admin.php:146:9",
                "echo \$_POST['vulnerable_plugin_reflected_xss'];",
            ],
            4 => [
                "ERROR: TaintedFile",
                "at /home/tuncay/GitHub/wp-test-site/wp-content/plugins/vulnerable-wp-plugin/admin/class-vulnerable-plugin-admin.php:158:28",
                "Detected tainted file handling (see https://psalm.dev/255)",
                "\$_POST",
                "<no known location>",
                "",
                "\$_POST['vulnerable_plugin_arbitrary_file_read'] - wp-content/plugins/vulnerable-wp-plugin/admin/class-vulnerable-plugin-admin.php:158:28",
                "echo file_get_contents(\$_POST['vulnerable_plugin_arbitrary_file_read']);",
                "",
                "call to file_get_contents - wp-content/plugins/vulnerable-wp-plugin/admin/class-vulnerable-plugin-admin.php:158:28",
                "echo file_get_contents(\$_POST['vulnerable_plugin_arbitrary_file_read']);",
            ]
        );

        $actual = $this->psalmOutputParser->splitPsalmOutputIntoErrorMessages($output);

        $this->assertSame($expected, $actual);
    }

    public function testParseErrorMessageWithSingleError(): void
    {
        $output = [
            "Target PHP version: 8.3 (inferred from current PHP version) Enabled extensions: simplexml.",
            "",
            "Scanning files...",
            "",
            "79 / 79...Getting stub files...",
            "115 / 115...",
            "",
            "Analyzing files...",
            "",
            "░░░░░░░░░░░░░░░░░░░",
            "",
            "ERROR: TaintedHtml",
            "at /home/tuncay/GitHub/wp-test-site/wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:17:18",
            "Detected tainted HTML (see https://psalm.dev/245)",
            "  \$_POST",
            "    <no known location>",
            "",
            "  call to Options::set - wp-content/plugins/vulnerable-wp-plugin/admin/class-vulnerable-plugin-admin.php:134:18",
            "                        \$options->set(\$_POST);",
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
        ];

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
                "stmt" => "\$options->set(\$_POST);"
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


        $expectedErrorArray = array(
            0 => $expectedPsalmError,
        );

        $actual = $this->psalmOutputParser->parsePsalmOutput($output);

        for ($i = 0; $i < count($expectedErrorArray); $i++) {
            $this->assertErrorObjectsAreSame($expectedErrorArray[$i], $actual["errors"][$i]);
        }
    }

    public function testParseErrorMessageWithMultipleErrors(): void
    {
        $output = [
            "Target PHP version: 8.3 (inferred from current PHP version) Enabled extensions: simplexml.",
            "",
            "Scanning files...",
            "",
            "79 / 79...Getting stub files...",
            "115 / 115...",
            "",
            "Analyzing files...",
            "",
            "░░░░░░░░░░░░░░░░░░░",
            "",
            "ERROR: TaintedHtml",
            "at /home/tuncay/GitHub/wp-test-site/wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:14:26",
            "Detected tainted HTML (see https://psalm.dev/245)",
            "  \$_POST",
            "    <no known location>",
            "",
            "  call to Options::set - wp-content/plugins/vulnerable-wp-plugin/admin/class-vulnerable-plugin-admin.php:134:18",
            "                        \$options->set(\$_POST);",
            "",
            "  Options::set#1 - wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:10:22",
            "        public function set(\$field, \$value = null): void",
            "",
            "  \$field - wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:10:22",
            "        public function set(\$field, \$value = null): void",
            "",
            "  arrayvalue-fetch - wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:13:13",
            "                        foreach (\$field as \$name => \$val) {",
            "",
            "  \$val - wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:13:32",
            "                        foreach (\$field as \$name => \$val) {",
            "",
            "  call to update_option - wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:14:26",
            "                                update_option(\$name, \$val);",
            "",
            "",
            "",
            "ERROR: TaintedHtml",
            "at /home/tuncay/GitHub/wp-test-site/wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:17:18",
            "Detected tainted HTML (see https://psalm.dev/245)",
            "  \$_POST",
            "    <no known location>",
            "",
            "  call to Options::set - wp-content/plugins/vulnerable-wp-plugin/admin/class-vulnerable-plugin-admin.php:134:18",
            "            \$options->set(\$_POST);",
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
            "ERROR: TaintedHtml",
            "at /home/tuncay/GitHub/wp-test-site/wp-content/plugins/vulnerable-wp-plugin/admin/class-vulnerable-plugin-admin.php:146:9",
            "Detected tainted HTML (see https://psalm.dev/245)",
            "  \$_POST",
            "    <no known location>",
            "",
            "  \$_POST['vulnerable_plugin_reflected_xss'] - wp-content/plugins/vulnerable-wp-plugin/admin/class-vulnerable-plugin-admin.php:146:9",
            "                        echo \$_POST['vulnerable_plugin_reflected_xss'];",
            "",
            "  call to echo - wp-content/plugins/vulnerable-wp-plugin/admin/class-vulnerable-plugin-admin.php:146:9",
            "                        echo \$_POST['vulnerable_plugin_reflected_xss'];",
            "",
            "",
            "",
            "ERROR: TaintedTextWithQuotes",
            "at /home/tuncay/GitHub/wp-test-site/wp-content/plugins/vulnerable-wp-plugin/admin/class-vulnerable-plugin-admin.php:146:9",
            "Detected tainted text with possible quotes (see https://psalm.dev/274)",
            "  \$_POST",
            "    <no known location>",
            "",
            "  \$_POST['vulnerable_plugin_reflected_xss'] - wp-content/plugins/vulnerable-wp-plugin/admin/class-vulnerable-plugin-admin.php:146:9",
            "                        echo \$_POST['vulnerable_plugin_reflected_xss'];",
            "",
            "  call to echo - wp-content/plugins/vulnerable-wp-plugin/admin/class-vulnerable-plugin-admin.php:146:9",
            "                        echo \$_POST['vulnerable_plugin_reflected_xss'];",
            "",
            "",
            "",
            "ERROR: TaintedFile",
            "at /home/tuncay/GitHub/wp-test-site/wp-content/plugins/vulnerable-wp-plugin/admin/class-vulnerable-plugin-admin.php:158:28",
            "Detected tainted file handling (see https://psalm.dev/255)",
            "  \$_POST",
            "    <no known location>",
            "",
            "  \$_POST['vulnerable_plugin_arbitrary_file_read'] - wp-content/plugins/vulnerable-wp-plugin/admin/class-vulnerable-plugin-admin.php:158:28",
            "                                echo file_get_contents(\$_POST['vulnerable_plugin_arbitrary_file_read']);",
            "",
            "  call to file_get_contents - wp-content/plugins/vulnerable-wp-plugin/admin/class-vulnerable-plugin-admin.php:158:28",
            "                                echo file_get_contents(\$_POST['vulnerable_plugin_arbitrary_file_read']);",
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
        ];

        $psalmErrorOne = new PsalmError();
        $psalmErrorTwo = new PsalmError();
        $psalmErrorThree = new PsalmError();
        $psalmErrorFour = new PsalmError();
        $psalmErrorFive = new PsalmError();

        $psalmErrorOne->errorType = "TaintedHtml";
        $psalmErrorOne->errorPath = "/home/tuncay/GitHub/wp-test-site/wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:14:26";
        $psalmErrorOne->errorMessage = [
            array("id" => "\$_POST", "stmt" => "<no known location>"),
            array("id" => "call to Options::set - wp-content/plugins/vulnerable-wp-plugin/admin/class-vulnerable-plugin-admin.php:134:18", "stmt" => "\$options->set(\$_POST);"),
            array("id" => "Options::set#1 - wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:10:22", "stmt" => "public function set(\$field, \$value = null): void"),
            array("id" => "\$field - wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:10:22", "stmt" => "public function set(\$field, \$value = null): void"),
            array("id" => "arrayvalue-fetch - wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:13:13", "stmt" => "foreach (\$field as \$name => \$val) {"),
            array("id" => "\$val - wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:13:32", "stmt" => "foreach (\$field as \$name => \$val) {"),
            array("id" => "call to update_option - wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:14:26", "stmt" => "update_option(\$name, \$val);"),
        ];

        $psalmErrorTwo->errorType = "TaintedHtml";
        $psalmErrorTwo->errorPath = "/home/tuncay/GitHub/wp-test-site/wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:17:18";
        $psalmErrorTwo->errorMessage = [
            array("id" => "\$_POST", "stmt" => "<no known location>"),
            array("id" => "call to Options::set - wp-content/plugins/vulnerable-wp-plugin/admin/class-vulnerable-plugin-admin.php:134:18", "stmt" => "\$options->set(\$_POST);"),
            array("id" => "Options::set#1 - wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:10:22", "stmt" => "public function set(\$field, \$value = null): void"),
            array("id" => "\$field - wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:10:22", "stmt" => "public function set(\$field, \$value = null): void"),
            array("id" => "call to update_option - wp-content/plugins/vulnerable-wp-plugin/admin/Options.php:17:18", "stmt" => "update_option(\$field, \$value);")
        ];

        $psalmErrorThree->errorType = "TaintedHtml";
        $psalmErrorThree->errorPath = "/home/tuncay/GitHub/wp-test-site/wp-content/plugins/vulnerable-wp-plugin/admin/class-vulnerable-plugin-admin.php:146:9";
        $psalmErrorThree->errorMessage = [
            array("id" => "\$_POST", "stmt" => "<no known location>"),
            array("id" => "\$_POST['vulnerable_plugin_reflected_xss'] - wp-content/plugins/vulnerable-wp-plugin/admin/class-vulnerable-plugin-admin.php:146:9", "stmt" => "echo \$_POST['vulnerable_plugin_reflected_xss'];"),
            array("id" => "call to echo - wp-content/plugins/vulnerable-wp-plugin/admin/class-vulnerable-plugin-admin.php:146:9", "stmt" => "echo \$_POST['vulnerable_plugin_reflected_xss'];")
        ];

        $psalmErrorFour->errorType = "TaintedTextWithQuotes";
        $psalmErrorFour->errorPath = "/home/tuncay/GitHub/wp-test-site/wp-content/plugins/vulnerable-wp-plugin/admin/class-vulnerable-plugin-admin.php:146:9";
        $psalmErrorFour->errorMessage = [
            array("id" => "\$_POST", "stmt" => "<no known location>"),
            array("id" => "\$_POST['vulnerable_plugin_reflected_xss'] - wp-content/plugins/vulnerable-wp-plugin/admin/class-vulnerable-plugin-admin.php:146:9", "stmt" => "echo \$_POST['vulnerable_plugin_reflected_xss'];"),
            array("id" => "call to echo - wp-content/plugins/vulnerable-wp-plugin/admin/class-vulnerable-plugin-admin.php:146:9", "stmt" => "echo \$_POST['vulnerable_plugin_reflected_xss'];")
        ];

        $psalmErrorFive->errorType = "TaintedFile";
        $psalmErrorFive->errorPath = "/home/tuncay/GitHub/wp-test-site/wp-content/plugins/vulnerable-wp-plugin/admin/class-vulnerable-plugin-admin.php:158:28";
        $psalmErrorFive->errorMessage = [
            array("id" => "\$_POST", "stmt" => "<no known location>"),
            array("id" => "\$_POST['vulnerable_plugin_arbitrary_file_read'] - wp-content/plugins/vulnerable-wp-plugin/admin/class-vulnerable-plugin-admin.php:158:28", "stmt" => "echo file_get_contents(\$_POST['vulnerable_plugin_arbitrary_file_read']);"),
            array("id" => "call to file_get_contents - wp-content/plugins/vulnerable-wp-plugin/admin/class-vulnerable-plugin-admin.php:158:28", "stmt" => "echo file_get_contents(\$_POST['vulnerable_plugin_arbitrary_file_read']);")
        ];

        $expected = [$psalmErrorOne, $psalmErrorTwo, $psalmErrorThree, $psalmErrorFour, $psalmErrorFive];

        $actual = $this->psalmOutputParser->parsePsalmOutput($output);

        for ($i = 0; $i < count($expected); $i++) {
            $this->assertErrorObjectsAreSame($expected[$i], $actual["errors"][$i]);
        }
    }

    protected function assertErrorObjectsAreSame(PsalmError $expected, PsalmError $actual): void
    {
        $this->assertTrue($expected->equals($actual));
    }
}