<?php

declare(strict_types=1);

namespace Tuncay\PsalmWpTaint\src;

use Psalm\Plugin\EventHandler\AfterExpressionAnalysisInterface;
use Psalm\Plugin\EventHandler\Event\AfterExpressionAnalysisEvent;

class AddActionChecker implements AfterExpressionAnalysisInterface
{
    /**
     * @param AfterExpressionAnalysisEvent $event
     * @return bool|null
     */
    public static function afterExpressionAnalysis(AfterExpressionAnalysisEvent $event): ?bool
    {
        $expr = $event->getExpr();
        $codebase = $event->getCodebase();
        $statements_source = $event->getStatementsSource();
        $add_action_parser = new AddActionParser();

        if ($add_action_parser->isAddAction($expr)) {
            $add_action_parser->foundExpressions[] = $expr;
            $add_action_parser->readActionsMapFromFile("./actions-map.json");
            $add_action_parser->parseFoundExpressions();
            $add_action_parser->writeActionsMapToFile("./actions-map.json");
        }

        return null;
    }
}