<?php

namespace Tuncay\PsalmWpTaint\src;

use PhpParser\Node\Stmt\Function_;
use Psalm\CodeLocation;
use Psalm\Plugin\EventHandler\AfterStatementAnalysisInterface;
use Psalm\Plugin\EventHandler\Event\AfterStatementAnalysisEvent;
use Psalm\Type\TaintKindGroup;

class TestPlugin implements AfterStatementAnalysisInterface
{

    public static function afterStatementAnalysis(AfterStatementAnalysisEvent $event): ?bool
    {
        $statement = $event->getStmt();
        $statements_source = $event->getStatementsSource();
        $codebase = $event->getCodebase();

        if ($statement instanceof Function_) {
            print_r("Found a function " . $statement->name . "\n");

            if ($statement->name == 'update_user_data') {
                $stmt_id = $statement->name
                    . '-' . $statements_source->getFileName()
                    . ':' . $statement->getAttribute('startFilePos');

                print_r('Adding ' . $stmt_id . ' as taint sink');

                $codebase->addTaintSink(
                    $stmt_id,
                    TaintKindGroup::ALL_INPUT,
                    new CodeLocation($statements_source, $statement)
                );

                print_r($codebase->taint_flow_graph);
            }
        }

        return null;
    }
}