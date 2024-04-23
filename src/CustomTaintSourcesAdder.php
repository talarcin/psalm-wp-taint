<?php

namespace Tuncay\PsalmWpTaint\src;

use PhpParser\Node\Expr\Variable;
use Psalm\CodeLocation;
use Psalm\Plugin\EventHandler\AfterExpressionAnalysisInterface;
use Psalm\Plugin\EventHandler\Event\AfterExpressionAnalysisEvent;

use Psalm\Type\TaintKindGroup;

class CustomTaintSourcesAdder implements AfterExpressionAnalysisInterface
{

    public static function afterExpressionAnalysis(AfterExpressionAnalysisEvent $event): ?bool
    {
        $expr = $event->getExpr();
        $stmt_source = $event->getStatementsSource();
        $codebase = $event->getCodebase();

        if ($expr instanceof Variable) {
            print_r('Found expression ' . $expr->name . PHP_EOL);

            if ($expr->name == '_POST') {
                $expr_type = $stmt_source->getNodeTypeProvider()->getType($expr);

                // should be a globally unique id
                // you can use its line number/start offset
                $expr_identifier = $expr->name
                    . '-' . $stmt_source->getFileName()
                    . ':' . $expr->getAttribute('startFilePos');

                if ($expr_type) {
                    print_r('Adding ' . $expr_identifier . ' as taint source');

                    $codebase->addTaintSource(
                        $expr_type,
                        $expr_identifier,
                        TaintKindGroup::ALL_INPUT,
                        new CodeLocation($stmt_source, $expr)
                    );
                }
            }
        }

        return null;
    }
}