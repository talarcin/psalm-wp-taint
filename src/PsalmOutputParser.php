<?php

namespace Tuncay\PsalmWpTaint\src;

use Tuncay\PsalmWpTaint\src\PsalmError\PsalmError;
use Tuncay\PsalmWpTaint\src\PsalmError\PsalmErrorCollection;
use Tuncay\PsalmWpTaint\src\PsalmError\PsalmPluginResult;

/**
 * @author Tuncay Alarcin
 */
class PsalmOutputParser {
	public function __construct() {
	}

	/**
	 * Parses the given output of a psalm taint analysis and builds a PsalmPluginResult object.
	 *
	 * Separates the given output in error messages and parses each individually to build a list of errors
	 * and then build the PsalmPluginResult.
	 *
	 * @param array $report
	 *
	 * @return PsalmPluginResult|bool
	 */
	public function parsePsalmReport( array $report ): PsalmPluginResult|bool {
		if ( $report["count"] == 0 ) {
			return false;
		}

		$pluginResult        = new PsalmPluginResult();
		$pluginResult->count = $report["count"];

		$psalmErrors = new PsalmErrorCollection();

		foreach ( $report["errors"] as $error ) {
			$psalmError               = new PsalmError( $error["errorType"], $error["errorPath"] );
			$psalmError->errorMessage = $error["errorMessage"];
			$psalmErrors[] = $psalmError;
		}

		$pluginResult->psalmErrors = $psalmErrors;

		return $pluginResult;
	}
}
