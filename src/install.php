<?php
namespace keesiemeijer\Post_Type_Calendar;

// Composer autoloading.
$composer = file_exists( POST_TYPE_CALENDAR_PLUGIN_DIR . 'vendor/autoload.php' );

// ExtCalendar provides the necessary shims if the PHP calendar extention is not installed.
$shim = file_exists( POST_TYPE_CALENDAR_PLUGIN_DIR . 'src/Dependencies/Fisharebest/ExtCalendar/shims.php' );

if ( $composer && $shim ) {
	// Mozart did it's work.

	require POST_TYPE_CALENDAR_PLUGIN_DIR . 'vendor/autoload.php';
	require POST_TYPE_CALENDAR_PLUGIN_DIR . 'src/Dependencies/Fisharebest/ExtCalendar/shims.php';
	// Add the calendar stylesheet.
	add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue__scripts' );

	// Add the calendar shortcode.
	add_shortcode( 'pt_calendar', __NAMESPACE__ . '\\add_calendar_shortcode' );

}

/**
 * Enqueue Simple Calendar's stylesheet.
 *
 * @since  1.0.0
 */
function enqueue__scripts() {
	wp_enqueue_style( 'simple-calendar', POST_TYPE_CALENDAR_PLUGIN_URL . 'src/Dependencies/css/SimpleCalendar.css' );
}
