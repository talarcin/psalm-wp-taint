<?php

namespace Tuncay\PsalmWpTaint\tests\unit;

use PHPUnit\Framework\TestCase;
use Tuncay\PsalmWpTaint\src\FuzzableActionSelector;

class FuzzableActionTest extends TestCase
{
  private array $addActionsMap = ["admin_menu" => ["adivaha_main_menu", "audio_record_menu"], "init" => ["aivaha_booking_engine", "audio_record_enginge"]];
  private array $psalmResults = [];
  private FuzzableActionSelector $fuzzableActionSelector;

  protected function setUp(): void
  {
    $this->fuzzableActionSelector = new FuzzableActionSelector($this->addActionsMap, $this->psalmResults);
  }

  public function testScanFilesForFunctionsNames(): void {}

  public function testWriteFuzzableActionsToFile(): void {}
}
