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
            $fn_name = $statement->name;
            print_r("Found a function " . $fn_name . "\n");

            if ($fn_name === 'update_user_data') {
                $stmt_id = $fn_name
                    . '-' . $statements_source->getFileName()
                    . ':' . $statement->getAttribute('startFilePos');

                print_r('Adding ' . $stmt_id . ' as taint sink');

                $codebase->addTaintSink(
                    $stmt_id,
                    TaintKindGroup::ALL_INPUT,
                    new CodeLocation($statements_source, $statement)
                );
            }
        }

        return null;
    }
}