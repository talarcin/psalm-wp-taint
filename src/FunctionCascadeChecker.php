<?php

namespace Tuncay\PsalmWpTaint\src;

use Psalm\Plugin\EventHandler\AfterAnalysisInterface;
use Psalm\Plugin\EventHandler\Event\AfterAnalysisEvent;

class FunctionCascadeChecker implements AfterAnalysisInterface
{
    public static function afterAnalysis(AfterAnalysisEvent $event): void
    {
        
    }
}