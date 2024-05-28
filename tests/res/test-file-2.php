<?php

add_action("admin_menu", "example_admin_menu_callback");


add_action(
    "wp_ajax",
    array(
        "some_class",
        "example_wp_ajax_callback"
    )
);