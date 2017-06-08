<?php
/**
 * Class Test_Dependencies
 *
 * @package Post_Type_Calendar
 */

namespace keesiemeijer\Post_Type_Calendar;

/**
 * Sample test case.
 */
class Test_Date_Functions extends Post_Type_Calendar_UnitTestCase {

	/**
	 * Test get_date_attributes() with invalid date.
	 */
	function test_function_get_date_attributes_invalid_date() {
		$this->assertFalse( get_date_attributes( 2017, 13 ) );
	}

	/**
	 * Test get_date_attributes() return value.
	 */
	function test_function_get_date_attributes() {
		$expected = array(
			'timestamp',
			'last_day',
			'year',
			'month',
			'formatted',
		);
		$attributes = array_keys( get_date_attributes( 2017, 12 ) );
		$this->assertEquals( $expected, $attributes );
	}

	/**
	 * Test get_date_from_post() function.
	 *
	 * @depends test_function_get_date_attributes
	 */
	function test_function_get_date_from_post() {
		$posts = $this->create_posts();

		$post_date = explode( ' ', $posts[3]->post_date );
		list( $year, $month, $day ) = explode( '-', $post_date[0] );
		$expected = get_date_attributes( $year, $month, $day );

		$this->assertEquals( $expected, get_date_from_post( $posts[3] ) );
	}


	/**
	 * Test function get_current_calendar_date() in a date archive.
	 *
	 * @depends test_function_get_date_from_post
	 */
	function test_function_get_current_calendar_date_in_date_archive() {
		$posts = $this->create_posts();
		$date = get_date_from_post( $posts[4] );
		$this->go_to( "/?m={$date['year']}{$date['month']}{$date['day']}" );
		$this->assertQueryTrue( 'is_date', 'is_archive', 'is_day' );
		$curr_date = get_current_calendar_date();
		$this->assertEquals( $date['year'] . $date['month'], $curr_date['year'] . $curr_date['month'] );
	}

	/**
	 * Test function get_current_calendar_date() on the home page.
	 *
	 * @depends test_function_get_date_from_post
	 */
	function test_function_get_current_calendar_date_in_home_page() {
		$posts = $this->create_posts();
		$date  = get_date_from_post( $posts[4] );

		$this->go_to( "/" );
		$this->assertQueryTrue( 'is_front_page', 'is_home' );
		$date = getdate();
		$date['mon'] =  zeroise( $date['mon'], 2 );
		$curr_date = get_current_calendar_date();

		$this->assertEquals( $date['year'] . $date['mon'], $curr_date['year'] . $curr_date['month'] );
	}

}
