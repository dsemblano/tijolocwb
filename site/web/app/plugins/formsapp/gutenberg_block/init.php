<?php

function formsapp_register_block()
{
    wp_register_script(
        'formsapp_script_editor',
        plugins_url('./build/index.js', __FILE__),
        array('wp-blocks', 'wp-element', 'wp-components', 'wp-block-editor')
    );

    register_block_type('formsapp/form-block', array(
        'editor_script' => 'formsapp_script_editor'
    ));
}

add_action('init', 'formsapp_register_block');

function formsapp_pass_params_to_wp_admin()
{
    wp_localize_script('formsapp_script_editor', 'backendData', [
        'gutenbergPluginRootFolder' => plugin_dir_url(__DIR__),
        'applicationPageUrl' => get_admin_url(null, 'admin.php?page=formsapp')
    ]);
}

add_action('admin_print_scripts', 'formsapp_pass_params_to_wp_admin');
