<?php
namespace keesiemeijer\Post_Type_Calendar;

// Composer autoloading
$composer = file_exists( POST_TYPE_CALENDAR_PLUGIN_DIR  . 'vendor/autoload.php' );

// This provides the necessary shims if the PHP calendar extention is not installed.
// This file can't be autoloaded because it creates (expected) conflicts when using Mozart by Coen Jacobs
// https://github.com/coenjacobs/mozart
$shim = file_exists( POST_TYPE_CALENDAR_PLUGIN_DIR . "src/Dependencies/Fisharebest/ExtCalendar/shims.php" );

if ( $composer && $shim ) {
	// Mozart did it's work.

	require POST_TYPE_CALENDAR_PLUGIN_DIR . 'vendor/autoload.php';
	require POST_TYPE_CALENDAR_PLUGIN_DIR . 'src/Dependencies/Fisharebest/ExtCalendar/shims.php';
	add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue__scripts' );
}

function enqueue__scripts() {
	wp_enqueue_style( 'simple-calendar', POST_TYPE_CALENDAR_PLUGIN_URL . 'src/Dependencies/css/SimpleCalendar.css' );
}
