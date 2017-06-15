<?php
/**
 * Class SampleTest
 *
 * @package Post_Type_Calendar
 */

namespace keesiemeijer\Post_Type_Calendar;

/**
 * Sample test case.
 */
class Test_Calendar extends Post_Type_Calendar_UnitTestCase {

	/**
	 * Test calendar linking to posts
	 */
	function test_calendar_linkto_post() {
		$posts     = $this->create_posts();
		$permalink = get_permalink( $posts[0] );
		$calendar  = get_calendar( $posts );
		$this->assertTrue( ( 1 === substr_count( $calendar, '<a ' ) ) );
		$this->assertContains( $permalink, $calendar );
	}

	/**
	 * Test calendar linking to date archives.
	 */
	function test_calendar_linkto_date_archives() {
		$posts     = $this->create_posts();
		$date      = get_date_from_post( $posts[0] );
		$permalink = get_home_url() . "/?m={$date['year']}{$date['month']}{$date['day']}";
		$calendar  = get_calendar( $posts, array( 'linkto' => 'date_archive' ) );
		$this->assertTrue( ( 1 === substr_count( $calendar, '<a ' ) ) );
		$this->assertContains( $permalink, $calendar );
	}

	/**
	 * Test calendar without linking.
	 */
	function test_calendar_no_linking() {
		$posts     = $this->create_posts();
		$permalink = get_permalink( $posts[0] );
		$calendar  = get_calendar( $posts, array( 'linkto' => '' ) );
		$this->assertContains( $posts[0]->post_title, $calendar );
		$this->assertNotContains( '<a', $calendar );
	}

	/**
	 * Test calendar linking to post types.
	 */
	function test_calendar_multiple_post_type_posts() {
		_delete_all_posts();
		$posts         = $this->create_posts( 'post', 5, false);
		$posts_cpt     = $this->create_posts( 'cpt', 5, false );
		$permalink     = get_permalink( $posts[0] );
		$permalink_cpt = get_permalink( $posts_cpt[0] );
		$posts         = array_merge($posts, $posts_cpt);
		$calendar      = get_calendar( $posts );
		$this->assertContains( $permalink, $calendar );
		$this->assertContains( $permalink_cpt, $calendar );
		$this->assertTrue( ( 2 === substr_count( $calendar, '<a ' ) ) );
	}


	/**
	 * Test calendar Day names abbreviation.
	 */
	function test_calendar_day_names_abbreviation() {
		$posts     = $this->create_posts();
		$calendar  = get_calendar( $posts );
		$expected  = "<tr><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th><th>Sun</th></tr>";
		$this->assertContains( $expected, $calendar );
	}

	/**
	 * Test calendar Day names weekday.
	 */
	function test_calendar_day_names_weekday() {
		$posts     = $this->create_posts();
		$calendar  = get_calendar( $posts, array( 'day_names' =>'weekday' ) );
		$expected  = "<tr><th>Monday</th><th>Tuesday</th><th>Wednesday</th><th>Thursday</th><th>Friday</th><th>Saturday</th><th>Sunday</th></tr>";
		$this->assertContains( $expected, $calendar );
	}

	/**
	 * Test calendar Day names initial.
	 */
	function test_calendar_day_names_initial() {
		$posts     = $this->create_posts();
		$calendar  = get_calendar( $posts, array( 'day_names' =>'initial' ) );
		$expected  = "<tr><th>M</th><th>T</th><th>W</th><th>T</th><th>F</th><th>S</th><th>S</th></tr>";
		$this->assertContains( $expected, $calendar );
	}

	/**
	 * Test calendar start of week friday.
	 */
	function test_calendar_start_of_week_friday() {
		$posts     = $this->create_posts();
		$calendar  = get_calendar( $posts, array( 'start_of_week' =>5 ) );
		$expected  = "<tr><th>Fri</th><th>Sat</th><th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th></tr>";
		$this->assertContains( $expected, $calendar );
	}
}
