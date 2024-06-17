<?php
/*
Plugin Name: Affiliates, OTA, GDS Travel XML API Plugin
Plugin URI: http://www.adivaha.com
Description: GDS &amp; OTA go-LIVE Solution - Amadeus, Travelport (Galileo), Hotelbeds, TBO, Rezlive, Restel and 150+ integrated suppliers. Plug &amp; Play! Have no APIs? No worries, use adivaha&reg; integrated affiliates and GDS bookings APIs and update as you grow!
Author: adivaha&reg; - Travel Tech Company
Version: 2.3
Author URI: http://www.adivaha.com
*/
// define( 'WP_DEBUG', true );
// define( 'WP_DEBUG_LOG', true );
// define( 'WP_DEBUG_DISPLAY', false );

define('ADIVAHA__PLUGIN_DIR', plugin_dir_path(__FILE__));
$site_url = get_site_url();
define('ADIVAHA__PLUGIN_SITE_URL', $site_url); // mine
define('ADIVAHA__PLUGIN_URL', $site_url . "/wp-content/plugins/adiaha-hotel/"); // mine
define('DEFAULT_SETTING_URL', 'http://www.abengines.com/wp-content/themes/pluginPageUrl.php');

// error_reporting(0);

$action_url = getActionUrl();

global $current_user, $action_url, $user_email;

require(ABSPATH . WPINC . '/pluggable.php');

if (!function_exists('wp_get_current_user')) {
    echo 'Function not set';
    function wp_get_current_user()
    {
        global $current_user;
        get_currentuserinfo();
        return $current_user;
    }
}
$current_user = wp_get_current_user();
$user_id = $current_user->ID;
$user_email = $current_user->user_email;

// Add Toolbar Menus
if (!function_exists('adi_add_admin_bar_link_adi')) {
    function adi_add_admin_bar_link_adi()
    {
        global $wp_admin_bar;
        if (!is_super_admin() || !is_admin_bar_showing())
            return;
        $wp_admin_bar->add_menu(array(
            'id' => 'unq_adivaha',
            'title' => __('adivaha&reg; Plugin', 'adi_framework'),
            'href' => admin_url('admin.php?page=unq_adivaha'),
            'meta'   => array(
                'class'    => 'adi-item2',
            ),
        ));
    }
}
add_action('admin_bar_menu', 'adi_add_admin_bar_link_adi', 26);

add_action('admin_menu', 'adivaha_main_menu');
function adivaha_main_menu()
{
    ob_start();
    global $wpdb;
    global $files;
    global $Plugin_Path;
    $parent_slug = "unq_adivaha";
    add_menu_page("adivaha&reg; Plugin", "adivaha&reg; Plugin", 'manage_options', $parent_slug, "adivaha_dashboard_output", ADIVAHA__PLUGIN_URL . "asset/images/icon.png");
    function adivaha_dashboard_output()
    {
        include(ADIVAHA__PLUGIN_DIR . 'apps/index.php');
    }
}

add_shortcode('adivaha_searchBox', 'searchBox');
function adivaha_booking_engine()
{
    function searchBox()
    {
        ob_start();
        global $user_email;

        if ($user_email) {
            echo '<div id="adivaha-wrapper"><script charset="utf-8" type="text/javascript" src="//www.abengines.com/ui/V2/77A90664/combo/"></script></div>';
        }

        return ob_get_clean();
    }
}

register_activation_hook(__FILE__, 'adivha_pro_install_portal');

add_action('init', 'adivaha_booking_engine');
function adivha_pro_install_portal()
{
    global $user_email;
    init_db_myplugin();

    $site_url = get_site_url();
    updateAdminEmail(array("site_url" => $site_url, "user_email" => $user_email));
}

// Initialize DB Tables
function init_db_myplugin()
{
    // WP Globals
    global $table_prefix, $wpdb, $user_email;
    // Customer Table
    $customerTable = $table_prefix . 'custom_plugin';
    // Create Customer Table if not exist
    if ($wpdb->get_var("show tables like '$customerTable'") != $customerTable) {

        // Query - Create Table
        $sql = "CREATE TABLE `$customerTable` (";
        $sql .= " `id` int(11) NOT NULL auto_increment, ";
        $sql .= " `pid` varchar(25) NOT NULL, ";
        $sql .= " PRIMARY KEY (`id`) ";
        $sql .= ") ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";

        // Include Upgrade Script
        require_once(ABSPATH . '/wp-admin/includes/upgrade.php');
        // Create Table
        dbDelta($sql);
    }
}

function getActionUrl()
{
    $url = DEFAULT_SETTING_URL;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_POST, true);
    $result = curl_exec($ch);
    curl_close($ch);
    return "http://www.abengines.com/wp-content/themes/" . $result;
}

function getDetails($site_url)
{
    global $action_url;
    $data = array('site_url' => $site_url);
    $url = $action_url . "?action=getDetails";
    $send = json_encode($data);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $send);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

function updateAdminEmail($data)
{
    // global $action_url;
    // $url = $action_url . "?action=updateAdminEmail";
    $url = "http://www.abengines.com/wp-content/themes/pluginPage.php?action=updateAdminEmail";
    $send = json_encode($data);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $send);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_VERBOSE, true);
    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        $error_msg = curl_error($ch);
    }
    curl_close($ch);

    return $result;
}

$getDetailsResp = getDetails($site_url);
if ($getDetailsResp) $user_email = $getDetailsResp;

if (is_admin()) {

    add_action('wp_ajax_updateEmail', 'updateEmail');
    add_action('wp_ajax_nopriv_updateEmail', 'updateEmail');

    function updateEmail($email = NULL)
    {
        global $wpdb, $site_url;

        $user_email = ($_REQUEST['user_email'] ? $_REQUEST['user_email'] : $email);
        $flag = 0;
        if ($user_email != "") {
            $flag = updateAdminEmail(array("site_url" => $site_url, "user_email" => $user_email));
        }
        echo $flag;
        die;
    }
}
