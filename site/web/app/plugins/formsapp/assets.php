<?php

include ('gutenberg_block/init.php');

function formsapp_admin_load_assets()
{
    wp_enqueue_style('formsapp_css', plugins_url('/css/style.css', __FILE__));
}

add_action('admin_enqueue_scripts', 'formsapp_admin_load_assets');