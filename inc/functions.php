<?php
/**
 * Function to retrieve the current URI value and parse it to find the date page
 * @return array $url_date_array
 */
function get_url_date_array() {
  // Get today's date from the archive URL (allows navigation to ANY date)
  $full_url = $_SERVER[ 'REQUEST_URI' ];

  echo $full_url . '<br>';

  // Get rid of the first part of the URL to focus only on the date part
  $date_url = explode( '/alog/', $full_url );

  // Get today's date from WordPress for error handling
  $today = get_wp_current_date();
  $date_array = array(
      'days_this_month' =>  $today['days_this_month'],
      'day'     => $today['day'],
      'monnum'  => $today['monnum'],
      'month'   => $today['month'],
      'year'    => $today['year']
    );

  if ( substr( $full_url, -6 ) == '/alog/' || substr( $full_url, -5 ) == '/alog' ) {
    return $date_array;
  }

  $date = explode( '/', $date_url[1] );

  echo '<pre>';
  var_dump( $date );
  echo '</pre>';

  // Error handling
  if ( count( $date) > 1 ) {
    $date_array['year'] = $date[0];
    $date_array['monnum'] = 1;
    $date_array['day'] = 1;
    return $date_array;
  }

  // Month Name ("March" etc) 
  // $date_obj = DateTime::createFromFormat( '!m', $mon_num );
  // $mon_name = $date_obj->format( 'F' );

  $url_date_array = array(
    'days_this_month' => date( 't', mktime(0,0,0,$mon_num,$day,$year) ),
    'day'     => $day,
    'monnum'  => $mon_num,
    'month'   => $mon_name,
    'year'    => $year
  );

  return $url_date_array; // [day, month_num, month_name, year]
}

/**
 * Function that returns the English readable Date string based on the current URI
 * @return string In the format "April 4, 2018"
 */
function get_url_date_string( $year = '', $month = '', $day = '' ) {

  $url_date = get_url_date_array();
  if ( $year == '' ) {
    $year = $url_date['year'];
  } 
  if ( $month == '' ) {
    $month = $url_date['month'];
  }
  if ( $day == '' ) {
    $day = $url_date['day'];
  }
  
  return $month . ' ' . $day . ', ' . $year;
} 

/**
 * Function that just returns the year from the current URI
 * @return $year
 */
function get_url_year() {
  $url_date = get_url_date_array();
  return $url_date['year'];
}

/**
 * Function that returns the month number from the current URI
 * @return $monnum
 */
function get_url_monnum() {
  $url_date = get_url_date_array();
  return $url_date['monnum'];
}

/**
 * Function that returns the English month name of the current URI
 * @return $month
 */
function get_url_month() {
  $url_date = get_url_date_array();
  return $url_date['month'];
}

/**
 * Function that returns the day from the current URI
 * @return $day
 */
function get_url_day() {
  $url_date = get_url_date_array();
  return $url_date['day'];
}

function get_url_timestamp() {
  $url_date = get_url_date_array();
  return mktime(0, 0, 0, $url_date['monnum'], $url_date['day'], $url_date['year'] );
}

function get_wp_current_date() {
  // Get all values for today's date and set variable
  // Grabs the current timestamp according to WordPress's set UTC offset
  $wp_timestamp = current_time( 'timestamp' ); 

  // Then, passes WordPress's UTC offset timestamp into PHP's date function
  $today = date( $wp_timestamp );
  $year = date( 'Y', $wp_timestamp );
  $mon_num = date( 'n', $wp_timestamp );
  $mon_name = date( 'F', $wp_timestamp );
  $day = date( 'j', $wp_timestamp );
  $days_this_month = date( 't' );

  $wp_date_array = array(
    'days_this_month' => $days_this_month,
    'day'     => $day,
    'monnum'  => $mon_num,
    'month'   => $mon_name,
    'year'    => $year
  );

  return $wp_date_array; // [day, month_num, month_name, year]
}

/**
 * Function that returns TRUE or FALSE if the URI is today
 * 
 * @return bool $is_today whether or not the URI is for today
 */
function is_today() {
  $url_date = get_url_date_array();

  // Do some more logic before getting here.
  return false;
}

function adventure_log_date_url( $year, $month, $day ) {
  return '/alog/' . $year . '/' . $month . '/' . $day . '/';
}

function adventure_log_get_month() {

}

function adventure_log_get_mon() {
  
}