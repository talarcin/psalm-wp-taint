<?php

declare( strict_types=1 );

namespace Tuncay\PsalmWpTaint\src;

use Psalm\Plugin\EventHandler\AfterExpressionAnalysisInterface;
use Psalm\Plugin\EventHandler\Event\AfterExpressionAnalysisEvent;

/**
 * @author Tuncay Alarcin
 */
class AddActionChecker implements AfterExpressionAnalysisInterface {
	public static function afterExpressionAnalysis( AfterExpressionAnalysisEvent $event ): ?bool {
		$expr = $event->getExpr();
		$file = $event->getStatementsSource()->getFileName();
		AddActionParser::getInstance()->addFile( $file );


		if ( AddActionParser::getInstance()->isAddAction( $expr ) ) {
			AddActionParser::getInstance()->addExpression( $expr );
		}

		return null;
	}
}