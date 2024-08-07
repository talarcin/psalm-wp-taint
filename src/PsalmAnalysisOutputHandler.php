<?php

namespace Tuncay\PsalmWpTaint\src;

use Tuncay\PsalmWpTaint\src\PsalmError\PsalmResult;

class PsalmAnalysisOutputHandler
{

    public function __construct()
    {
    }

    public function handle(PsalmOutputParser $outputParser, array $outputs): PsalmResult
    {
		$results = new PsalmResult();
		$results->total = count($outputs);
		$results->totalNoTaint = 0;
		$results->totalTaintedPlugins = 0;

        foreach ($outputs as $pluginSlug => $output) {
            if (is_array($output)) {
                $pluginResult = $outputParser->parsePsalmOutput($output);
                if (!$pluginResult) {
					$results->totalNoTaint++;
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
