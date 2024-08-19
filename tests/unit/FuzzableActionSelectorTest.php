<?php

namespace Tuncay\PsalmWpTaint\tests\unit;

use PHPUnit\Framework\TestCase;
use Tuncay\PsalmWpTaint\src\FuzzableActionSelector;
use Tuncay\PsalmWpTaint\src\PsalmError\PsalmError;
use Tuncay\PsalmWpTaint\src\PsalmError\PsalmPluginResult;
use Tuncay\PsalmWpTaint\src\PsalmError\PsalmResult;

class FuzzableActionSelectorTest extends TestCase
{
  private const TEST_DIR_PATH = "./tests/res/fuzzable-action-selector";
  private const FUZZABLE_ACTIONS_FILE = "./tests/res/fuzzable-action-selector/fuzzable-actions";
  private array $addActionsMap = ["admin_menu" => ["testFunctionName", "testFunctionNameTwo", "testFunctionNameThree"], "init" => ["aivaha_booking_engine", "audio_record_engine"]];
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
    $psalmErrorOne->errorMessage[] = ["id" => "call to testFunctionName - ./test.php:6:5", "stmt" => "return testFunctionName();"];
    $psalmPluginResultOne->psalmErrors[] = $psalmErrorOne;

    $psalmPluginResultTwo = new PsalmPluginResult("testSlugTwo", 2);
    $psalmErrorTwo = new PsalmError("TaintedHtml", "./test.php");
    $psalmErrorTwo->errorMessage[] = ["id" => "\$_POST", "stmt" => "<no known location>"];
    $psalmErrorTwo->errorMessage[] = ["id" => "call to testFunctionNameTwo - ./test.php:12:5", "stmt" => "return testFunctionNameTwo();"];
    $psalmErrorThree = new PsalmError("TaintedHtml", "./test.php");
    $psalmErrorThree->errorMessage[] = ["id" => "\$_GET", "stmt" => "<no known location>"];
    $psalmErrorThree->errorMessage[] = ["id" => "call to testFunctionNameThree - ./test.php:25:5", "stmt" => "return testFunctionNameThree();"];
    $psalmPluginResultTwo->psalmErrors[] = $psalmErrorTwo;
    $psalmPluginResultTwo->psalmErrors[] = $psalmErrorThree;

    $psalmResults->addResult($psalmPluginResultOne);
    $psalmResults->addResult($psalmPluginResultTwo);

    $this->fuzzableActionSelector = new FuzzableActionSelector($this->addActionsMap, $psalmResults);
  }

  public function testSelectActionsToFuzz(): void
  {
    $dirIsOk = $this->fuzzableActionSelector->selectActionsToFuzz(self::TEST_DIR_PATH);

    $this->assertTrue($dirIsOk);

    $this->assertSame(["admin_menu"], $this->fuzzableActionSelector->getFuzzableActions());
  }

  public function testWriteFuzzableActionsToFile(): void
  {
    $this->fuzzableActionSelector->selectActionsToFuzz(self::TEST_DIR_PATH);
    $this->fuzzableActionSelector->writeFuzzableActionsToFile(self::FUZZABLE_ACTIONS_FILE);
    $fuzzableActions = file_get_contents(self::FUZZABLE_ACTIONS_FILE . ".json");
    $fuzzableActions = json_decode($fuzzableActions);

    $this->assertSame("admin_menu", $fuzzableActions[0]);
  }

  public function testSelectActionsToFuzzWithNonexistentDir(): void
  {
    $this->assertFalse($this->fuzzableActionSelector->selectActionsToFuzz("./tests/res/nonexistendir/"));
  }
}
