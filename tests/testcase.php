<?php
/**
 * Class SampleTest
 *
 * @package Tosti
 */

namespace keesiemeijer\Post_Type_Calendar;

class Post_Type_Calendar_UnitTestCase extends \WP_UnitTestCase {

	/**
	 * Creates posts with decreasing timestamps.
	 *
	 * @param string  $post_type      Post type.
	 * @param integer $posts_per_page How may posts to create.
	 * @return array                  Array with posts.
	 */
	function create_posts( $post_type = 'post', $posts_per_page = 5 ) {

		_delete_all_posts();

		if ( ! defined( 'MONTH_IN_SECONDS' ) ) {
			define( 'MONTH_IN_SECONDS',  30 * DAY_IN_SECONDS    );
		}

		// create posts with 4 months decreasing timestamp
		$posts = array();
		$now = time();
		foreach ( range( 0, ( ( $posts_per_page - 1 ) * 4 ), 4 ) as $i ) {
			$this->factory->post->create(
				array(
					'post_date' => date( 'Y-m-d H:i:s', $now - ( $i * MONTH_IN_SECONDS ) ),
					'post_type' => $post_type,
				) );
		}

		// Return posts by desc date.
		$posts = get_posts(
			array(
				'posts_per_page' => -1,
				'post_type'      => $post_type,
				//'fields'         => 'ids',
				'order'          => 'DESC',
				'orderby'        => 'date',
			) );

		return $posts;
	}
}
