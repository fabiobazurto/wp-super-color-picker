<?php
/*
Plugin Name: Super Color Picker Plugin
Description: Enable the WP Color Picker in the frontend
Author: Fabio A. Bazurto Blacio<35inputs>
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

defined( 'WPINC' ) || die;

//require_once plugin_dir_path(__FILE__) . 'includes/picker-functions.php';

//include_once 'includes/picker-functions.php'; //class-picker-backend.php';
include_once 'backend/class-picker-backend.php';
include_once 'public/class-picker-display.php';
 
add_action( 'plugins_loaded', 'super_color_picker_start' );

/**
 * Start the plugin.
 */
function super_color_picker_start() {
 
    if ( is_admin() ) {
        $admin = new Super_Color_Picker_Field( 'color_picker_field' );
        $admin->init();
    } else {
 
        $plugin = new Super_Color_Picker_Display( 'color_picker_field' );
        $plugin->init();
    }
}

