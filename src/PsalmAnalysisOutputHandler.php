<?php

namespace Tuncay\PsalmWpTaint\src;

class PsalmAnalysisOutputHandler
{

    public function __construct()
    {
    }

    public function handle(PsalmOutputParser $outputParser, array $outputs): array
    {
        $results = array();

        foreach ($outputs as $pluginSlug => $output) {
            $pluginResult = $outputParser->parsePsalmOutput($output);

            if (!$pluginResult) continue;

            $results[$pluginSlug] = $pluginResult;
        }

        return $results;
    }
}
