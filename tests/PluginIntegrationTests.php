<?php

use PHPUnit\Framework\TestCase;
use Tuncay\PsalmWpTaint\src\AddActionParser;

class PluginIntegrationTests extends TestCase
{
    public function testPluginIsCalled(): void
    {
        exec("./../vendor/bin/psalm --taint-analysis");
        $expectedActionsMap = array("admin_post" => array("example_admin_post_callback", "example_admin_post_callback"),
            "test_hook" => array("example_admin_post_callback"), "admin_menu" => array("example_admin_menu_callback"),
            "wp_ajax" => array("example_wp_ajax_callback"), "init" => array("adrotate_insert_group"));

        AddActionParser::getInstance()->readActionsMapFromFile("./../add-actions-map.json");

        foreach ($expectedActionsMap as $expectedAction) {
            $this->assertTrue(in_array($expectedAction, AddActionParser::getInstance()->getActionsMap()));
        }
    }

    protected function setUp(): void
    {
        file_put_contents("./../add-actions-map.json", "");
    }
}