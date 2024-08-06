<?php

declare(strict_types=1);

namespace Tuncay\PsalmWpTaint\src;

class FuzzableActionSelector
{
  private array $fuzzableActions;
  private array $addActionsMap;
  private array $psalmResults;

  public function __construct(array $addActionsMap, array $psalmResults)
  {
    $this->addActionsMap = $addActionsMap;
    $this->psalmResults = $psalmResults;
  }

  public function scanFilesForFunctionNames(): bool
  {
    return false;
  }

  public function writeFuzzableActionsToFile(string $filename): bool
  {
    return false;
  }

  private function selectActionToFuzz(): void {

  }
}
