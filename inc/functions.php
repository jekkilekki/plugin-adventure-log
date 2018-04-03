<?php
/**
 * Function to retrieve the current URI value and parse it to find the date page
 * 
 * @return array $url_date_array Contains day [0], month num [1], and year [2] values
 */
function get_url_date() {
  // Get today's date from the archive URL (allows navigation to ANY date)
  $todays_date_url = $_SERVER[ 'REQUEST_URI' ];
  $url_date = explode( "/", $todays_date_url );
  $url_date_array = array();

  // Retrieve from the end of the array, in order: day, month, year
  // Day $url_date_array[0]
  array_pop( $url_date ); 
  $url_date_array[] = end( $url_date );

  // Month $url_date_array[1]
  array_pop( $url_date );
  $url_date_array[] = end( $url_date );

  // Year $url_date_array[2]
  array_pop( $url_date );
  $url_date_array[] = end( $url_date );

  return $url_date_array;
}

/**
 * Function that just returns the year from the current URI date array
 * 
 * @return $year
 */
function get_url_year() {
  $url_date = get_url_date();

  return $url_date[2];
}

/**
 * Function that returns the month number from the current URI date array
 * 
 * @return $monnum
 */
function get_url_monnum() {
  $url_date = get_url_date();

  return $url_date[1];
}

/**
 * Function that converts the month number into an English month name
 * 
 * @return $month
 */
function get_url_month() {
  $url_date = get_url_date();

  $date_obj = DateTime::createFromFormat( '!m', $url_date[1] );
  $month = $date_obj->format( 'F' );

  return $month;
}

/**
 * Function that returns the day from the current URI date array
 * 
 * @return $day
 */
function get_url_day() {
  $url_date = get_url_date();

  return $url_date[0];
}

/**
 * Function that returns TRUE or FALSE if the URI is today
 * 
 * @return bool $is_today whether or not the URI is for today
 */
function is_today() {
  $url_date = get_url_date();

  // Do some more logic before getting here.
  return false;
}

function adventure_log_get_year() {

}

function adventure_log_get_month() {

}

function adventure_log_get_mon() {
  
}