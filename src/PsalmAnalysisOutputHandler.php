<?php

namespace Tuncay\PsalmWpTaint\src;

use Tuncay\PsalmWpTaint\src\PsalmError\PsalmResult;

/**
 * @author Tuncay Alarcin
 */
class PsalmAnalysisOutputHandler
{

    public function __construct()
    {
    }

	/**
	 * Builds a PsalmResult object from given psalm taint analysis output.
	 *
	 * Orchestrates the parsing via PsalmOutputParser and handles the correct
	 * order of parsing the output.
	 *
	 * @param PsalmOutputParser $outputParser
	 * @param array $outputs
	 *
	 * @return PsalmResult
	 */
    public function handle(PsalmOutputParser $outputParser, array $outputs): PsalmResult
    {
		$results = new PsalmResult();
		$results->total = count($outputs);
		$results->totalNoTaint = 0;
		$results->totalTaintedPlugins = 0;

        foreach ($outputs as $pluginSlug => $output) {
            if (is_array($output)) {
                $pluginResult = $outputParser->parsePsalmReport($output);
                if (!$pluginResult) {
					$results->totalNoTaint++;
					continue;
                } else {
					$results->totalTaintedPlugins++;
                }

				$pluginResult->pluginSlug = $pluginSlug;

                $results->addResult($pluginResult);
            }
        }

        return $results;
    }
}
