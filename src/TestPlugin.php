<?php

namespace Tuncay\PsalmWpTaint\src;

use Psalm\Plugin\EventHandler\AfterStatementAnalysisInterface;
use Psalm\Plugin\EventHandler\Event\AfterStatementAnalysisEvent;

class TestPlugin implements AfterStatementAnalysisInterface
{

    public static function afterStatementAnalysis(AfterStatementAnalysisEvent $event): ?bool
    {
        $statement = $event->getStmt();
        print_r($statement);
        return null;
    }
}