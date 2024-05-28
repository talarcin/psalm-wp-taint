<?php

namespace Tuncay\PsalmWpTaint\src;

use Psalm\Plugin\EventHandler\AfterAnalysisInterface;
use Psalm\Plugin\EventHandler\Event\AfterAnalysisEvent;

class AddActionCheckFinisher implements AfterAnalysisInterface
{

    public static function afterAnalysis(AfterAnalysisEvent $event): void
    {
        AddActionParser::getInstance()->parseFoundExpressions();
        AddActionParser::getInstance()->writeActionsMapToFile("./actions-map.json");
    }
}