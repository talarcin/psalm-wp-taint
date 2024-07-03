<?php

namespace Tuncay\PsalmWpTaint\tests\integration;

use PHPUnit\Framework\TestCase;
use Tuncay\PsalmWpTaint\src\AddActionParser;
use Tuncay\PsalmWpTaint\src\Util;

class PluginIntegrationTest extends TestCase
{
    public function testPluginIsCalled(): void
    {
        Util::changePsalmProjectDir("./tests/res/psalm/files", "./psalm.xml");

        exec("./vendor/bin/psalm --taint-analysis");
        $expectedActionsMap = array(
            "admin_post" => ["example_admin_post_callback", "example_admin_post_callback"],
            "test_hook" => ["example_admin_post_callback"],
            "admin_menu" => ["example_admin_menu_callback"],
            "wp_ajax" => ["example_wp_ajax_callback"],
            'admin_head' => ['disable_all_in_one_free'],
            'plugins_loaded' => ['aiosp_add_cap', "aioseop_init_class", 'version_updates'],
            'admin_notices' => ["admin_notices_already_defined"],
            'admin_init' => ['version_updates', 'aioseop_welcome', 'aioseop_scan_post_header'],
            'init' => ['add_hooks', 'aioseop_load_modules', "adrotate_insert_group"],
            'shutdown' => ['aioseop_ajax_scan_header'],
            'wp_ajax_aioseop_ajax_save_meta' => ['aioseop_ajax_save_meta'],
            'wp_ajax_aioseop_ajax_save_url' => ['aioseop_ajax_save_url'],
            'wp_ajax_aioseop_ajax_delete_url' => ['aioseop_ajax_delete_url'],
            'wp_ajax_aioseop_ajax_scan_header' => ['aioseop_ajax_scan_header'],
            'wp_ajax_aioseop_ajax_facebook_debug' => ['aioseop_ajax_facebook_debug'],
            'wp_ajax_aioseop_ajax_save_settings' => ['aioseop_ajax_save_settings'],
            'wp_ajax_aioseop_ajax_get_menu_links' => ['aioseop_ajax_get_menu_links'],
            'wp_ajax_aioseo_dismiss_yst_notice' => ['aioseop_update_yst_detected_notice'],
            'wp_ajax_aioseo_dismiss_visibility_notice' => ['aioseop_update_user_visibilitynotice'],
            'wp_ajax_aioseo_dismiss_woo_upgrade_notice' => ['aioseop_woo_upgrade_notice_dismissed'],
            'admin_enqueue_scripts' => ['aioseop_admin_enqueue_styles']
        );

        AddActionParser::getInstance()->readActionsMapFromFile("./add-actions-map.json");
        $actualActionsMap = AddActionParser::getInstance()->getActionsMap();

        foreach ($expectedActionsMap as $expectedKey => $expectedValue) {
            $actualValue = $actualActionsMap[$expectedKey];

            foreach ($expectedValue as $value) {
                $this->assertTrue(in_array($value, $actualValue));
            }
        }
    }

    public function testPluginAddActionWorksWithOnePlugin() {
        Util::changePsalmProjectDir("./tests/res/psalm/plugins/adiaha-hotel", "./psalm.xml");
        exec("./vendor/bin/psalm --taint-analysis");

        $expectedActionsMap = array(
            'admin_bar_menu' => ['adi_add_admin_bar_link_adi'],
            'admin_menu' => ['adivaha_main_menu'],
            'init' => ['adivaha_booking_engine'],
            'wp_ajax_updateEmail' => ['updateEmail'],
            'wp_ajax_nopriv_updateEmail' => ['updateEmail'],
        );

        AddActionParser::getInstance()->readActionsMapFromFile("./add-actions-map.json");
        $actualActionsMap = AddActionParser::getInstance()->getActionsMap();

        foreach ($expectedActionsMap as $expectedKey => $expectedValue) {
            $actualValue = $actualActionsMap[$expectedKey];

            foreach ($expectedValue as $value) {
                $this->assertTrue(in_array($value, $actualValue));
            }
        }
    }

    public function testPluginAddActionWorksWithTwoPlugins() {
        $pluginSlugs = ["adiaha-hotel", "audio-record"];

        foreach ($pluginSlugs as $pluginSlug) {
            Util::changePsalmProjectDir("./tests/res/psalm/plugins/" . $pluginSlug, "./psalm.xml");
            exec("./vendor/bin/psalm --taint-analysis");
        }

        $expectedActionsMap = array(
            'admin_bar_menu' => ['adi_add_admin_bar_link_adi'],
            'admin_menu' => ['adivaha_main_menu'],
            'init' => ['adivaha_booking_engine', 'ar_create_post_type_audio_record'],
            'wp_ajax_updateEmail' => ['updateEmail'],
            'wp_ajax_nopriv_updateEmail' => ['updateEmail'],
            'wp_enqueue_scripts' => ['ar_enqueue_js_record'],
            'wp_ajax_save_record' => ['save_record_callback'],
            'wp_ajax_nopriv_save_record' => ['save_record_callback'],
            'manage_audiorecord_posts_custom_column' => ['ar_custom_columns_record_audio'],
            'admin_init' => ['ar_codex_admin_init'],
            'before_delete_post' => ['ar_detele_post_delete_audio_also']
        );

        AddActionParser::getInstance()->readActionsMapFromFile("./add-actions-map.json");
        $actualActionsMap = AddActionParser::getInstance()->getActionsMap();



        foreach ($expectedActionsMap as $expectedKey => $expectedValue) {
            $actualValue = $actualActionsMap[$expectedKey];

            foreach ($expectedValue as $value) {
                $this->assertTrue(in_array($value, $actualValue));
            }
        }

    }

    protected function setUp(): void
    {
        file_put_contents("./add-actions-map.json", "");
    }
}
