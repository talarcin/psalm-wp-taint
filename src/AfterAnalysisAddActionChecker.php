<?php

namespace Tuncay\PsalmWpTaint\src;

use Psalm\Plugin\EventHandler\AfterAnalysisInterface;
use Psalm\Plugin\EventHandler\Event\AfterAnalysisEvent;

/**
 * @author Tuncay Alarcin
 */
class AfterAnalysisAddActionChecker implements AfterAnalysisInterface {
	public static function afterAnalysis( AfterAnalysisEvent $event ): void {
		AddActionParser::getInstance()->parseFoundExpressions();
		AddActionParser::getInstance()->writeActionsMapToFile( "./add-actions-map.json" );
		AddActionParser::getInstance()->printAnalyzedFilesToFile( "./analyzed-files.json" );
	}
}