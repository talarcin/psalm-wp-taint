<?php

add_action("admin_menu", "example_admin_menu_callback");

add_action(
    "wp_ajax",
    array(
        "some_class",
        "example_wp_ajax_callback"
    )
);

if (isset($_POST['adrotate_group_submit'])) add_action('init', 'adrotate_insert_group');