<?php

use PHPUnit\Framework\TestCase;
use Tuncay\PsalmWpTaint\src\PsalmError;
use Tuncay\PsalmWpTaint\src\PsalmPluginResult;

class PsalmPluginResultTest extends TestCase
{
    protected PsalmPluginResult $pluginResult;

    public function testPluginResultEquals(): void
    {
        $resultOne = new PsalmPluginResult();
        $resultTwo = new PsalmPluginResult();

        $psalmError = new PsalmError();

        $psalmError->errorType = "type";
        $psalmError->errorPath = "path";
        $psalmError->errorMessage = [array("id" => "test", "stmt" => "testStmt")];

        $resultOne->pluginSlug = "test";
        $resultOne->addError($psalmError);

        $resultTwo->pluginSlug = "test";
        $resultTwo->addError($psalmError);

        $this->assertTrue($resultOne->equals($resultTwo));
    }
}
