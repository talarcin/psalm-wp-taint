<?php

namespace Tuncay\PsalmWpTaint\tests\unit;

use PHPUnit\Framework\TestCase;
use Tuncay\PsalmWpTaint\src\FuzzableActionSelector;
use Tuncay\PsalmWpTaint\src\PsalmError\PsalmError;
use Tuncay\PsalmWpTaint\src\PsalmError\PsalmPluginResult;
use Tuncay\PsalmWpTaint\src\PsalmError\PsalmResult;

class FuzzableActionSelectorTest extends TestCase
{
  private const string TEST_DIR_PATH = "./tests/res/test.php";
  private array $addActionsMap = ["admin_menu" => ["adivaha_main_menu", "audio_record_menu"], "init" => ["aivaha_booking_engine", "audio_record_engine"]];
  private FuzzableActionSelector $fuzzableActionSelector;

  protected function setUp(): void
  {
    $psalmResults = new PsalmResult();
    $psalmResults->total = 2;
    $psalmResults->totalNoTaint = 0;
    $psalmResults->totalTaintedPlugins = 2;
    $psalmPluginResultOne = new PsalmPluginResult("testSlugOne", 1);
    $psalmErrorOne = new PsalmError("TaintedHtml", "./test.php");
    $psalmErrorOne->errorMessage[] = ["id" => "\$_POST", "stmt" => "<no known location>"];
    $psalmErrorOne->errorMessage[] = ["id" => "call to testFunctionName - ./test.php:10:5", "stmt" => "return testFunctionName();"];
    $psalmPluginResultOne->psalmErrors[] = $psalmErrorOne;

    $psalmPluginResultTwo = new PsalmPluginResult("testSlugTwo", 2);
    $psalmErrorTwo = new PsalmError("TaintedHtml", "./test.php");
    $psalmErrorTwo->errorMessage[] = ["id" => "\$_POST", "stmt" => "<no known location>"];
    $psalmErrorTwo->errorMessage[] = ["id" => "call to testFunctionNameTwo - ./test.php:10:5", "stmt" => "return testFunctionNameTwo();"];
    $psalmErrorThree = new PsalmError("TaintedHtml", "./test.php");
    $psalmErrorThree->errorMessage[] = ["id" => "\$_GET", "stmt" => "<no known location>"];
    $psalmErrorThree->errorMessage[] = ["id" => "call to testFunctionNameThree - ./test.php:25:5", "stmt" => "return testFunctionNameThree();"];
    $psalmPluginResultTwo->psalmErrors[] = $psalmErrorTwo;
    $psalmPluginResultTwo->psalmErrors[] = $psalmErrorThree;

    $psalmResults->addResult($psalmPluginResultOne);
    $psalmResults->addResult($psalmPluginResultTwo);

    $this->fuzzableActionSelector = new FuzzableActionSelector($this->addActionsMap, $psalmResults);
  }

  public function testScanFilesForFunctionsNames(): void
  {
    $this->fuzzableActionSelector->scanFilesForFunctionNames(self::TEST_DIR_PATH);
    $this->assertSame(["admin_menu"], $this->fuzzableActionSelector->getFuzzableActions());
  }

  public function testWriteFuzzableActionsToFile(): void {}
}
