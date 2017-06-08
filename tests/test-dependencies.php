<?php
/**
 * Class Test_Dependencies
 *
 * @package Post_Type_Calendar
 */

namespace keesiemeijer\Post_Type_Calendar;

/**
 * Dependency tests.
 */
class Test_Dependencies extends Post_Type_Calendar_UnitTestCase {

	/**
	 * Test dependency donatj/simplecalendar.
	 */
	function test_SimpleCalendar_class_exists_under_plugin_namespace() {
		$this->assertTrue( class_exists(  __NAMESPACE__ . '\\Dependencies\donatj\SimpleCalendar' ) );
	}

	/**
	 * Test dependency fisharebest/ext-calendar.
	 */
	function test_ExtCalendar_Shim_class_exists_under_plugin_namespace() {
		$this->assertTrue( class_exists(  __NAMESPACE__ . '\\Dependencies\Fisharebest\ExtCalendar\Shim' ) );
	}

	/**
	 * Test dependency wpupdatephp/wp-update-php.
	 */
	function test_WPUpdatePhp_class_exists_with_plugin_prefix() {
		$this->assertTrue( class_exists(  'PTC_WPUpdatePhp' ) );
	}
}
