<?php

namespace Tuncay\PsalmWpTaint\src;

use PhpParser\Node\Stmt\Function_;
use Psalm\Plugin\EventHandler\AfterStatementAnalysisInterface;
use Psalm\Plugin\EventHandler\Event\AfterStatementAnalysisEvent;

class TestPlugin implements AfterStatementAnalysisInterface
{

    public static function afterStatementAnalysis(AfterStatementAnalysisEvent $event): ?bool
    {
        $statement = $event->getStmt();
        $statements_source = $event->getStatementsSource();
        $codebase = $event->getCodebase();

        if ($statement instanceof Function_) {
            print_r("Found a function call " . $statement->name . "\n");
        }

        return null;
    }
}