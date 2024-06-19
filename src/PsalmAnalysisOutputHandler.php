<?php

namespace Tuncay\PsalmWpTaint\src;

class PsalmAnalysisOutputHandler
{

    public function __construct()
    {
    }

    public function handle(PsalmOutputParser $outputParser, array $outputs): array
    {
        $results = array(
            "total" => count($outputs),
            "total_tainted_plugins" => 0,
            "total_no_taint" => 0,
            "results" => []
        );

        foreach ($outputs as $pluginSlug => $output) {
            if (is_array($output)) {
                $errors = $outputParser->parsePsalmOutput($output);
                if (!$errors) {
                    $results["total_no_taint"]++;
                } else {
                    $results["total_tainted_plugins"]++;
                }

                $results["results"][$pluginSlug] = $errors;
            }
        }

        return $results;
    }
}
