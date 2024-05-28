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

        if (AddActionParser::getInstance()->isAddAction($expr)) {
            AddActionParser::getInstance()->addExpression($expr);
        }

        return null;
    }
}