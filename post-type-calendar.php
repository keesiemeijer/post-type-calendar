<?php
/*
Plugin Name: Post Type Calendar
Version: 1.1.0
Plugin URI:
Description: Display a calendar with WordPress post titles.
Author: keesiemijer
Author URI:
License: GPL v2+
*/


// Plugin Folder Path.
if ( ! defined( 'POST_TYPE_CALENDAR_PLUGIN_DIR' ) ) {
	define( 'POST_TYPE_CALENDAR_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}
// Plugin Folder URL.
if ( ! defined( 'POST_TYPE_CALENDAR_PLUGIN__URL' ) ) {
	define( 'POST_TYPE_CALENDAR_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

if ( file_exists( POST_TYPE_CALENDAR_PLUGIN_DIR . 'src/Dependencies/classes/wpupdatephp/wp-update-php/src/WPUpdatePhp.php' ) ) {

	require POST_TYPE_CALENDAR_PLUGIN_DIR . 'src/Dependencies/classes/wpupdatephp/wp-update-php/src/WPUpdatePhp.php';

	$updatePhp = new PTC_WPUpdatePhp( '5.6' );
	$updatePhp->set_plugin_name( 'Post Type Calendar' );

	if ( $updatePhp->does_it_meet_required_php_version() ) {

		// Get this plugin runnning.
		require POST_TYPE_CALENDAR_PLUGIN_DIR . 'src/install.php';
	}
}
