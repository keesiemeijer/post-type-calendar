<?php
namespace keesiemeijer\Post_Type_Calendar;

/**
 * Calendar shortcode.
 *
 * @since  1.1.0
 * @param array $args Shortcode attributes.
 * @return string Calendar HTML or empty string.
 */
function add_calendar_shortcode( $args ) {
	$defaults = array(
		'post_type'     => 'post',
		'date'          => 'now',
		'post_ids'      => '',
		'start_of_week' => (int) get_option( 'start_of_week' ),
		'linkto'        => 'post',
		'day_names'     => '',
	);

	$args = wp_parse_args( $args, $defaults );
	$args = shortcode_atts( $defaults, $args, 'post_type_calendar' );
	$args = array_merge( $defaults, (array) $args );
	$args = validate_attributes( $args );

	if ( ! $args['post_type'] || ! $args['query_args'] ) {
		return '';
	}

	$args['query_args']['post_type'] = $args['post_type'];
	$args['query_args']['posts_per_page'] = -1;
	$calendar_posts = get_posts( $args['query_args'] );
	if ( ! $calendar_posts ) {
		return '';
	}

	unset( $args['post_type'], $args['date'], $args['post_ids'], $args['query_args'] );

	return get_calendar( $calendar_posts, $args );
}

/**
 * Validates shortcode attributes.
 *
 * @since 1.1.0
 *
 * @param array $args Array with shortcode attributes
 * @return array       Array with validated shortcode attributes.
 */
function validate_attributes( $args ) {
	$args['query_args'] = array();

	if ( 'now' === $args['date'] ) {
		$date = get_current_calendar_date();
		if ( $date ) {
			$args['date'] = "{$date['year']}-{$date['month']}";
		} else {
			$args['date'] = '';
		}
	}

	if ( $args['date'] && ( false !== strpos( $args['date'], '-' ) ) ) {
		$args['date'] = explode( '-', $args['date'] );
		$args['date'] = array_filter( $args['date'], function( $val ) {
				return absint( $val );
			} );
		$args['date'] = array_values( $args['date'] );
		if ( 2 === count( $args['date'] ) ) {
			$date = get_date_attributes( $args['date'][0], $args['date'][1] );
			if ( $date ) {
				$args['query_args'] = array(
					'year'     => $date['year'],
					'monthnum' => (int) $date['month'],
				);
			}
		}
	}

	$args['post_type'] = get_comma_separated_values( $args['post_type'] );
	$args['post_type'] = array_filter( $args['post_type'], function( $val ) {
			return post_type_exists( $val ) && is_post_type_viewable( $val );
		} );
	$args['post_type'] = array_values( $args['post_type'] );

	$args['post_ids'] = get_comma_separated_values( $args['post_ids'] );
	$args['post_ids'] = array_map( 'intval', $args['post_ids'] );
	if ( $args['post_ids'] ) {
		$args['query_args'] = array(
			'post__in'  => $args['post_ids'],
		);
	}

	$args['start_of_week'] = absint( $args['start_of_week'] );
	$args['linkto']        = is_string( $args['linkto'] ) ? $args['linkto'] : 'post';
	$args['day_names']     = is_string( $args['day_names'] ) ? $args['day_names'] : '';

	return $args;
}
