<?php

use PHPUnit\Framework\TestCase;

class PluginIntegrationTests extends TestCase
{
    public function testPluginIsCalled()
    {
        exec("./../vendor/bin/psalm --taint-analysis");
        $fileContents = file_get_contents("/home/tuncay/GitHub/psalm-wp-taint/actions-map.json");
        $expectedActionsMap = array("admin_post" => array("example_admin_post_callback", "example_admin_post_callback"), "test_hook" => array("example_admin_post_callback"));

        $this->assertSame($expectedActionsMap, json_decode($fileContents, true));
    }
}