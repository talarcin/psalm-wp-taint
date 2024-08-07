<?php

declare(strict_types=1);

namespace Tuncay\PsalmWpTaint\src;

use Tuncay\PsalmWpTaint\src\PsalmError\PsalmResult;

class FuzzableActionSelector
{
  private array $fuzzableActions;
  private array $addActionsMap;
  private PsalmResult $psalmResult;

  public function __construct(array $addActionsMap, PsalmResult $psalmResult)
  {
    $this->addActionsMap = $addActionsMap;
    $this->psalmResult = $psalmResult;
    $this->fuzzableActions = [];
  }

  public function scanFilesForFunctionNames(string $dirPath): bool
  {
    return false;
  }

  public function writeFuzzableActionsToFile(string $filename): bool
  {
    return false;
  }

  public function getFuzzableActions(): array
  {
    return $this->fuzzableActions;
  }

  private function selectActionToFuzz(): void {}
}
