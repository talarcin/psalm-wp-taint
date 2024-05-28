<?php

use PHPUnit\Framework\TestCase;

class PluginIntegrationTests extends TestCase
{
    public function testPluginIsCalled(): void
    {
        exec("./../vendor/bin/psalm --taint-analysis");
        $fileContents = file_get_contents("/home/tuncay/GitHub/psalm-wp-taint/actions-map.json");
        $expectedActionsMap = array("admin_post" => array("example_admin_post_callback", "example_admin_post_callback"),
            "test_hook" => array("example_admin_post_callback"), "admin_menu" => array("example_admin_menu_callback"),
            "wp_ajax" => array("example_wp_ajax_callback"),);
        $actualActionsMap = json_decode($fileContents, true);

        foreach ($expectedActionsMap as $expectedAction) {
            $this->assertTrue(in_array($expectedAction, $actualActionsMap));
        }

        $this->cleanUp();
    }

    private function cleanUp(): void
    {
        $filePath = "/home/tuncay/GitHub/psalm-wp-taint/actions-map.json";
        file_put_contents($filePath, "");
    }
}