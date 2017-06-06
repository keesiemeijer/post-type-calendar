<?php
namespace keesiemeijer\Post_Type_Calendar;

/**
 * Gets the calendar year and month depending on the current page.
 * Returns the current date for non date archive pages.
 * Returns the date archive date for date archive pages.
 *
 * @since 1.0.0
 *
 * @return array|false Array with date attributes or false if no date was found.
 */
function get_current_calendar_date() {
	global $wpdb, $m, $monthnum, $year;

	if ( isset( $_GET['w'] ) ) {
		$w = (int) $_GET['w'];
	}

	$ts = current_time( 'timestamp' );

	// Let's figure out when we are.
	if ( ! empty( $monthnum ) && ! empty( $year ) ) {
		$thismonth = zeroise( intval( $monthnum ), 2 );
		$thisyear = (int) $year;
	} elseif ( ! empty( $w ) ) {
		// We need to get the month from MySQL.
		$thisyear = (int) substr( $m, 0, 4 );
		// It seems MySQL's weeks disagree with PHP's.
		$d = ( ( $w - 1 ) * 7 ) + 6;
		$thismonth = $wpdb->get_var( "SELECT DATE_FORMAT((DATE_ADD('{$thisyear}0101', INTERVAL $d DAY) ), '%m')" );
	} elseif ( ! empty( $m ) ) {
		$thisyear = (int) substr( $m, 0, 4 );
		if ( strlen( $m ) < 6 ) {
			$thismonth = '01';
		} else {
			$thismonth = zeroise( (int) substr( $m, 4, 2 ), 2 );
		}
	} else {
		$thisyear = gmdate( 'Y', $ts );
		$thismonth = gmdate( 'm', $ts );
	}

	return get_date_attributes( $thisyear, $thismonth );
}

/**
 * Returns date attributes.
 *
 * @since 1.0.0
 *
 * @param int    $year   Year.
 * @param int    $month  Month.
 * @param int    $day    Day. Default 0.
 * @param string $format PHP date format.
 * @return array|false Array with date attributes or false if no date was found.
 */
function get_date_attributes( $year, $month, $day = 0, $format = '' ) {

	$_day = absint( $day );

	// If day is not provided use the first day of the month.
	$_day = $_day ? $day  : 1;

	// Validate date.
	if ( ! checkdate( (int) $month, $_day, (int) $year ) ) {
		return false;
	}

	$timestamp = mktime( 0, 0, 0, (int) $month, $_day, (int) $year );

	$date = array(
		'timestamp' => $timestamp,
		'last_day'  => date( 't', $timestamp ),
		'year'      => (int) $year,
		'month'     => zeroise( (int) $month, 2 ),
	);

	if ( absint( $day ) ) {
		$date['day'] = zeroise( absint( $day ), 2 );
	}

	$date['formatted'] = date_i18n( get_option( 'date_format' ), $timestamp );
	if ( $format && is_string( $format ) ) {
		$date['formatted'] = date_i18n( $format, $timestamp );
	}

	return $date;
}

/**
 * Returns date attributes from a post.
 *
 * @since 1.0.0
 *
 * @param object $post   Post object.
 * @param string $format PHP date format.
 * @return array         Array with date attributes.
 */
function get_date_from_post( $post, $format = '' ) {
	if ( ! isset( $post->post_date ) && $post->post_date ) {
		return false;
	}

	$post_date = getdate( strtotime( $post->post_date ) );
	$date      = get_date_attributes( $post_date['year'], $post_date['mon'], $post_date['mday'], $format );
	if ( ! $date ) {
		return false;
	}

	return $date;
}

/**
 * Returns calendar html.
 *
 * @since 1.0.0
 *
 * @param array        $posts Array with post objects to show in the calendar.
 * @param string|array $args  {
 *     Optional. An array of arguments.
 *
 *     @type int    $start_of_week Day to start on, 0-6 where 0 is Sunday.
 *     @type string $linkto        Link to date archives or posts.
 *                                 Accepts: 'date_archives', 'posts' or empty string.
 *                                 Default 'date_archives'. Use empty string to not link.
 *     @type string $day_names     Type of weekday names.
 *                                 Accepts: 'abbreviation', 'weekday' or 'initial'.
 *                                 Default 'abbreviation'.
 * }
 * @return string Calendar HTML or empty string if $posts was empty or had no post objects.
 */
function get_calendar( $posts, $args = '' ) {

	$defaults = array(
		'start_of_week' => (int) get_option( 'start_of_week' ),
		'linkto'        => 'date_archive', // 'date_archive', 'post', or empty string.
		'day_names'     => '',
	);

	$args  = wp_parse_args( $args, $defaults );
	$posts = array_values( $posts );

	// Get the date from the first post as year and month for the calendar.
	$date = isset( $posts[0] ) ? get_date_from_post( $posts[0] ) : '';

	if ( ! $date ) {
		return '';
	}

	$calendar = new Calendar( $posts[0]->post_date );
	$calendar->setStartOfWeek( $args['start_of_week'] );
	$calendar->setWeekdayNames( $args['day_names'] );

	foreach ( $posts as $key => $post ) {
		$url       = get_permalink( $post );
		$title     = get_the_title( $post );
		$html      = '';

		$post_date = get_date_from_post( $post );
		if ( ! $post_date ) {
			continue;
		}

		// Check if posts are from the same month and year.
		if ( $date['year'] . $date['month'] !== $post_date['year'] . $post_date['month'] ) {
			continue;
		}

		if ( $args['linkto'] ) {

			if ( ( 'date_archive' === $args['linkto'] ) && ( 'post' === $post->post_type ) ) {
				// Only post type `post` has date archives.
				$url = get_day_link( $post_date['year'], $post_date['month'], $post_date['day'] );
			}
			$html = "<a href='{$url}'>{$title}</a>";
		}

		if ( ! $html ) {
			$html = $title;
		}

		/**
		 * Filter the daily HTML used in the calendar.
		 *
		 * @since  1.0.0
		 *
		 * @param string $html Daily HTML.
		 * @param object $post Post.
		 * @param array  $args Calendar arguments.
		 */
		$html = apply_filters( 'post_type_calendar_daily_html', $html, $post, $args );

		$calendar->addDailyHtml( $html, $post->post_date );
	}

	$calendar->show();
}
