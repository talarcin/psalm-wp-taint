<?php

namespace Tuncay\PsalmWpTaint\src;

use Psalm\Plugin\EventHandler\AfterAnalysisInterface;
use Psalm\Plugin\EventHandler\Event\AfterAnalysisEvent;

class AfterAnalysisAddActionChecker implements AfterAnalysisInterface
{

    public static function afterAnalysis(AfterAnalysisEvent $event): void
    {
        AddActionParser::getInstance()->parseFoundExpressions();
        AddActionParser::getInstance()->writeActionsMapToFile("./add-actions-map.json");
    }
}