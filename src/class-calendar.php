<?php
namespace keesiemeijer\Post_Type_Calendar;

class Calendar extends Dependencies\donatj\SimpleCalendar {
	/**
	 * Array of Week Day Names
	 *
	 * @var array
	 */
	public $wday_names = false;

	public function __construct( $date_string = null ) {
		$this->setWeekdayNames();
		parent::__construct( $date_string );
	}

	public function setWeekdayNames( $type = 'abbreviation' ) {
		global $wp_locale;

		$wday_names = array();

		for ( $wdcount = 0; $wdcount <= 6; $wdcount++ ) {
			$wday_names[] = $wp_locale->get_weekday( $wdcount );
		}

		$names = array_map( array( $wp_locale, 'get_weekday_abbrev' ), $wday_names );

		if ( 'weekday' === $type ) {
			$names = $wday_names;
		} elseif ( 'initial' === $type ) {
			$names = array_map( array( $wp_locale, 'get_weekday_initial' ), $wday_names );
		}

		$this->wday_names = $names;
	}
}
