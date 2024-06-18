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
            if (is_array($output)) {
                $errors = $outputParser->parsePsalmOutput($output);
                $results[$pluginSlug] = $errors;
            }
        }

        return $results;
    }
}
