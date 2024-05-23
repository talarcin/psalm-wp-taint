<?php

add_action("admin_post", "example_admin_post_callback");


add_action("admin_post", array("some_class", "example_admin_post_callback"));

add_action(
    "admin_post",
    array(
        "some_class",
        "example_admin_post_callback"
    )
);


