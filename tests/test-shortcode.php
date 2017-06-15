<?php
/**
 * Class Calendar Shortcode
 *
 * @package Post_Type_Calendar
 */

namespace keesiemeijer\Post_Type_Calendar;

/**
 * Sample test case.
 */
class Test_Shortcode extends Post_Type_Calendar_UnitTestCase {

	/**
	 * Test if shortcode is registered.
	 */
	public function test_shortcode_is_registered() {
		global $shortcode_tags;
		$this->assertArrayHasKey( 'pt_calendar', $shortcode_tags );
	}

	/**
	 * Test shortcode calendar linking to posts.
	 */
	function test_shortcode_calendar_linkto_posts() {
		$posts     = $this->create_posts();
		$permalink = get_permalink( $posts[0] );
		$shortcode = do_shortcode( '[pt_calendar]' );
		$this->assertTrue( ( 1 === substr_count( $shortcode, '<a ' ) ) );
		$this->assertContains( $permalink, $shortcode );
	}

	/**
	 * Test shortcode calendar without linking.
	 */
	function test_shortcode_calendar_linkto_none() {
		$posts     = $this->create_posts();
		$permalink = get_permalink( $posts[0] );
		$shortcode = do_shortcode( '[pt_calendar linkto=""]' );
		$this->assertNotContains( '<a ', $shortcode );
		$this->assertContains( $posts[0]->post_title, $shortcode );	
	}

	/**
	 * Test shortcode calendar linking to post types.
	 */
	function test_shortcode_calendar_post_type_attribute() {
		_delete_all_posts();
		$posts         = $this->create_posts();
		$posts_cpt     = $this->create_posts( 'cpt', 5, false );
		$permalink     = get_permalink( $posts[0] );
		$permalink_cpt = get_permalink( $posts_cpt[0] );
		$shortcode     = do_shortcode( '[pt_calendar post_type="cpt,post"]' );
		$this->assertContains( $permalink, $shortcode );
		$this->assertContains( $permalink_cpt, $shortcode );
		$this->assertTrue( ( 2 === substr_count( $shortcode, '<a ' ) ) );
	}

	/**
	 * Test shortcode calendar linking to date archives.
	 */
	function test_shortcode_calendar_linkto_date_archives() {
		$posts     = $this->create_posts();
		$date      = get_date_from_post( $posts[0] );
		$permalink = get_home_url() . "/?m={$date['year']}{$date['month']}{$date['day']}";
		$shortcode = do_shortcode( '[pt_calendar linkto="date_archive"]' );
		$this->assertTrue( ( 1 === substr_count( $shortcode, '<a ' ) ) );
		$this->assertContains( $permalink, $shortcode );
	}

	/**
	 * Test shortcode calendar with date attribute.
	 */
	function test_shortcode_calendar_with_date_attribute() {
		$posts     = $this->create_posts();
		$date      = get_date_from_post( $posts[4] );
		$permalink = get_home_url() . "/?m={$date['year']}{$date['month']}{$date['day']}";
		$shortcode = do_shortcode( "[pt_calendar date='{$date['year']}-{$date['month']}' linkto='date_archive' ]" );
		$this->assertTrue( ( 1 === substr_count( $shortcode, '<a ' ) ) );
		$this->assertContains( $permalink, $shortcode );
	}

	/**
	 * Test shortcode calendar with different post months.
	 */
	function test_shortcode_calendar_posts_different_calendar_months() {
		$posts     = $this->create_posts();
		$shortcode = do_shortcode( "[pt_calendar post_ids='{$posts[2]->ID},{$posts[3]->ID},{$posts[4]->ID}']" );
		$this->assertTrue( ( 1 === substr_count( $shortcode, '?p=' ) ) );
		$permalink = get_permalink( $posts[2] );
		$this->assertTrue( ( 1 === substr_count( $shortcode, '<a ' ) ) );
		$this->assertContains( $permalink, $shortcode );
	}

	/**
	 * Test shortcode calendar with shortcode in post content.
	 */
	function test_shortcode_calendar_post_id_in_post_content() {
		$posts     = $this->create_posts();

		// Add a shortcode to post content.
		wp_update_post( array(
				'ID'          => $posts[3]->ID,
				'post_content' => '[pt_calendar post_ids="' . $posts[1]->ID . '"]',
			)
		);

		// Go to a single post page
		$this->go_to( '?p=' .  $posts[3]->ID );

		// Trigger loop.
		ob_start();
		the_post();
		the_content();
		$content = ob_get_clean();
		$this->assertContains( $posts[1]->post_title, $content );
		$this->assertTrue( ( 1 === substr_count( $content, '<a ' ) ) );
	}

	/**
	 * Test shortcode calendar Day names abbreviation.
	 */
	function test_shortcode_calendar_day_names_abbreviation() {
		$posts     = $this->create_posts();
		$shortcode = do_shortcode( '[pt_calendar]' );
		$expected  = "<tr><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th><th>Sun</th></tr>";
		$this->assertContains( $expected, $shortcode );
	}

	/**
	 * Test shortcode calendar Day names weekday.
	 */
	function test_shortcode_calendar_day_names_weekday() {
		$posts     = $this->create_posts();
		$shortcode = do_shortcode( '[pt_calendar day_names="weekday"]' );
		$expected  = "<tr><th>Monday</th><th>Tuesday</th><th>Wednesday</th><th>Thursday</th><th>Friday</th><th>Saturday</th><th>Sunday</th></tr>";
		$this->assertContains( $expected, $shortcode );
	}

	/**
	 * Test shortcode calendar Day names initial.
	 */
	function test_shortcode_calendar_day_names_initial() {
		$posts     = $this->create_posts();
		$shortcode = do_shortcode( '[pt_calendar day_names="initial"]' );
		$expected  = "<tr><th>M</th><th>T</th><th>W</th><th>T</th><th>F</th><th>S</th><th>S</th></tr>";
		$this->assertContains( $expected, $shortcode );
	}

	/**
	 * Test shortcode calendar start of week friday.
	 */
	function test_shortcode_calendar_start_of_week_friday() {
		$posts     = $this->create_posts();
		$shortcode = do_shortcode( '[pt_calendar start_of_week="5"]' );
		$expected  = "<tr><th>Fri</th><th>Sat</th><th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th></tr>";
		$this->assertContains( $expected, $shortcode );
	}
}
