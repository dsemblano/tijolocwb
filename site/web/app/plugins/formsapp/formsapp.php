<?php

/*
    Plugin Name: forms.app
    Description: forms.app is an online form builder app and survey maker that enables you to create online forms and surveys easily.
    Version: 0.8.0
    Author: forms.app
    Author URI: https://forms.app

    Copyright 2018 forms.app (email: info@forms.app)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
*/

require_once('shortcode.php');
require_once('assets.php');
require_once('views/application.php');

add_action('admin_menu', 'formsapp_register_admin_settings');

function formsapp_register_admin_settings()
{
    add_menu_page('forms.app', 'forms.app', 'manage_options', 'formsapp', 'formsapp_run_application', plugins_url('/images/icon.png', __FILE__), 6);
}