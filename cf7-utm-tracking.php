<?php
/*
Plugin Name: UTM tags + Landing page + "gclid" tracking for Contact Form 7
Plugin URI: http://maxim-kaminsky.com/cf7-utm-tracking/
Description: This plugin will save a "UTM" tags + Landing (first) page + gclid to the cookies on first user visit and on the CF7 submit adds this info to the mail body.
Author: Maxim K
Version: 1.4
Author URI: http://maxim-kaminsky.com/

*/

// If this file is called directly, abort.
if (!class_exists('WP')) {
	die();
}

if ( $_SERVER['SCRIPT_FILENAME'] == __FILE__ ) {
	die( 'Access denied.' );
}

require_once 'cf7utm-class-main.php';
require_once 'cf7utm-class-admin.php';

add_action("plugins_loaded", "cf7utm_run", 9);

function cf7utm_run() {
    CF7_UTM_Tracking::instance();    
}

/**
 * The code that runs during plugin activation.
 */
register_activation_hook( __FILE__, array('CF7_UTM_Tracking', 'activate' ) );
/**
 * The code that runs during plugin deactivation.
 */
register_deactivation_hook( __FILE__, array('CF7_UTM_Tracking', 'deactivate' ) );