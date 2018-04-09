<?php
/**
 * Function to retrieve the current URI value and parse it to find the date page
 * @return array $url_date_array
 */
function get_url_date_array() {
  // Get today's date from the archive URL (allows navigation to ANY date)
  $full_url = $_SERVER[ 'REQUEST_URI' ];

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

  // Break apart remaining URI into separate numbers
  $date = explode( '/', $date_url[1] );

  // We should ALWAYS have a $year after that first check above
  $date_array['year'] = $date[0];

  // If trying to navigate into the future, redirect to alog homepage
  // @TODO: So far month and day checking aren't working, though future year works
  if ( $date[0] > $today['year'] ||
        ( $date[0] > $today['year'] && $date[1] > $today['monnum'] ) || 
        ( $date[0] > $today['year'] && $date[1] > $today['monnum'] && $date[2] > $today['day'] ) ) {
    wp_redirect( esc_url( home_url() . '/alog/' ) );
    exit;
  }

  // Error handling
  if ( $date[1] == '' ) {
    $date_array['monnum'] = 1;
    $date_array['month'] = get_month_name(1);
    $date_array['days_this_month'] = get_days_this_month( 1, $date[0] );;
    $date_array['day'] = 1;
    return $date_array;
  } elseif ( $date[2] == '' ) {
    $date_array['monnum'] = $date[1];
    $date_array['month'] = get_month_name($date[1]);
    $date_array['days_this_month'] = get_days_this_month( $date[1], $date[0] );
    $date_array['day'] = 1;
    return $date_array;
  } else {
    $date_array['monnum'] = $date[1];
    $date_array['month'] = get_month_name($date[1]);
    $date_array['days_this_month'] = get_days_this_month( $date[1], $date[0] );
    $date_array['day'] = $date[2];
    return $date_array;
  }

  // $url_date_array = array(
  //   'days_this_month' => date( 't', mktime( 0,0,0,$mon_num,$day,$year ) ),
  //   'day'     => $day,
  //   'monnum'  => $mon_num,
  //   'month'   => $mon_name,
  //   'year'    => $year
  // );

  // return $url_date_array; // [day, month_num, month_name, year]
}

function get_month_name( $num ) {
  // Month Name ("March" etc) 
  $date_obj = DateTime::createFromFormat( '!m', $num );
  return $date_obj->format( 'F' );
}

function get_days_this_month( $month, $year ) {
  return date( 't', mktime(0,0,0,$month,1,$year) );
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
function is_today( $today_array, $given_date_array, $given_day = '' ) {
  // Can't be today if the $given_day is different
  if ( $given_day != '' && $given_day != $today_array['day'] ) {
    return false;
  }
  if ( empty( array_diff( $today_array, $given_date_array ) ) ) {
    return true;
  }
  return false;
}

function adventure_log_date_url( $year, $month, $day ) {
  return '/alog/' . $year . '/' . $month . '/' . $day . '/';
}

function get_alog_month_link( $year, $month ) {
  return esc_url( home_url() . "/alog/$year/$month/" );
}

/**
 * Alog Word Count
 */
function alog_word_count() {
  return sprintf(
    __( '%s words', 'adventure-log' ),
    alog_word_count_numeric()
  );
}

/**
 * Alog Word Count (Number)
 */
function alog_word_count_numeric() {
  return str_word_count( strip_tags( get_post_field( 'post_content', get_the_ID() ) ) );
}

/**
 * Alog Calendar
 * @see https://jennifer.blog/2010/07/15/adding-custom-post-types-to-get_calendar-and-the-calendar-widget
 * 
 * alog_get_calendar() :: Extends get_calendar() by including the ALog CPT.
 * Derived from get_calendar() code in /wp-includes/general-template.php
 */
function alog_get_calendar( $post_types = '', $initial = true, $echo = true ) {
  global $wpdb, $m, $monthnum, $year, $wp_locale, $posts;

  if ( empty( $post_types ) || ! is_array( $post_types ) ) {
    $args = array(
      'public' => true,
      '_builtin' => false
    );
    $output = 'names';
    $operator = 'and';

    $post_types = get_post_types( $args, $output, $operator );
    $post_types = array_merge( $post_types, array( 'alog' ) );
  } else {
    // Trust but verify.
    $my_post_types = array();
    foreach( $post_types as $post_type ) {
      if ( post_type_exists( $post_type ) ) 
        $my_post_types[] = $post_type;
    }
    $post_types = $my_post_types;
  }
  $post_types_key = implode( '', $post_types );
  $post_types = "'" . implode( "', '", $post_types ) . "'";

  $cache = array();
  $key = md5( $m . $monthnum . $year . $post_types_key );

  if ( $cache = wp_cache_get( 'get_calendar', 'calendar' ) ) {
    if ( is_array( $cache ) && isset( $cache[$key] ) ) {
      remove_filter( 'get_calendar', 'alog_get_calendar_filter' );
      $output = apply_filters( 'get_calendar', $cache[$key] );
      add_filter( 'get_calendar', 'alog_get_calendar_filter' );
      if ( $echo ) {
        echo $output;
        return;
      } else {
        return $output;
      }
    }
  }

  if ( ! is_array( $cache ) )
    $cache = array();

  // Quick check. If we have no posts at all, abort!
  if ( ! $posts ) {
    $sql = "SELECT 1 as test FROM $wpdb->posts WHERE post_type IN ( $post_types ) AND post_status = 'publish' LIMIT 1";
    $gotsome = $wpdb->get_var( $sql );
    if ( ! $gotsome ) {
      $cache[$key] = '';
      wp_cache_set( 'get_calendar', $cache, 'calendar' );
      return;
    }
  }

  if ( isset( $_GET['w'] ) ) {
    $w = '' . intval( $_GET['w'] );
  }

  // week_begins = 0 stands for Sunday
  $week_begins = intval( get_option( 'start_of_week' ) );
  $ts = current_time( 'timestamp' );

  // Figure out WHEN we are
  if ( ! empty( $monthnum ) && ! empty( $year ) ) {
    $thismonth = '' . zeroise( intval( $monthnum ), 2 );
    $thisyear = '' . intval( $year );
  } elseif ( ! empty( $w ) ) {
    // Need to get the month from MySQL
    $thisyear = '' . intval( substr( $m, 0, 4 ) );
    $d = ( ( $w - 1 ) * 7 ) + 6; // it seems MySQL's weeks disagree with PHP's 
    $thismonth = $wpdb->get_var( "SELECT DATE_FORMAT( ( DATE_ADD( '${thisyear}0101', INTERVAL $d DAY ) ), '%m' )" );
  } elseif( ! empty( $m ) ) {
    $thisyear = '' . intval( substr( $m, 0, 4 ) );
    if ( strlen( $m ) < 6 )
      $thismonth = '01';
    else
      $thismonth = '' . zeroise( intval( substr( $m, 4, 2 ) ), 2 );
  } else {
    $thisyear = gmdate( 'Y', $ts );
    $thismonth = gmdate( 'm', $ts );
  }

  $unixmonth = mktime( 0,0,0, $thismonth, 1, $thisyear );
  $last_day = date( 't', $unixmonth );

  // Get the next and previous month and year with at least one post
  $previous = $wpdb->get_row( "SELECT DISTINCT MONTH( post_date ) AS month, YEAR( post_date ) AS year
      FROM $wpdb->posts
      WHERE post_date < '$thisyear-$thismonth-01'
      AND post_type IN ( $post_types ) AND post_status = 'publish'
        ORDER BY post_date DESC
        LIMIT 1" );
  $next = $wpdb->get_row( "SELECT DISTINCT MONTH( post_date ) AS month, YEAR( post_date ) AS year
      FROM $wpdb->posts
      WHERE post_date > '$thisyear-$thismonth-{$last_day} 23:59:59'
      AND post_type IN ( $post_types ) AND post_status = 'publish'
        ORDER BY post_date ASC
        LIMIT 1" );

  /* translators: Calendar caption: 1: month name, 2: 4-digit year */
  $calendar_caption = _x( '%1$s %2$s', 'calendar caption' );
  $calendar_output = '<div id="alog-calendar" summary="' . esc_attr__( 'ALog Calendar' ) . '">
      <h1 class="page-title">' . sprintf( $calendar_caption, $wp_locale->get_month( $thismonth ), date( 'Y', $unixmonth ) ) . '</h1>';

  $myweek = array();

  // for ( $wdcount = 0; $wdcount <= 6; $wdcount++ ) {
  //   $myweek[] = $wp_locale->get_weekday( ( $wdcount + $week_begins ) % 7 );
  // }

  // foreach ( $myweek as $wd ) {
  //   $day_name = (true == $initial ) ? $wp_locale->get_weekday_initial( $wd ) : $wp_locale->get_weekday_abbrev( $wd );
  //   $wd = esc_attr( $wd );
  //   $calendar_output .= "\n\t\t<th scope=\"col\" title=\"$wd\">$day_name</th>";
  // }

  // $calendar_output .= '
  //     </tr>
  //     </thead>
      
  //     <tfoot>
  //     <tr>';
  
  $calendar_output .= '<ul class="alog-date-boxes">';

  /**
   * Get DAYS with posts
   */
  $dayswithposts = $wpdb->get_results( "SELECT DISTINCT DAYOFMONTH( post_date )
      FROM $wpdb->posts WHERE MONTH( post_date ) = '$thismonth'
      AND YEAR( post_date ) = '$thisyear'
      AND post_type IN ( $post_types ) AND post_status = 'publish'
      AND post_date <'" . current_time( 'mysql' ) . '\'', ARRAY_N );
  if ( $dayswithposts ) {
    foreach ( (array) $dayswithposts as $daywith ) {
      $daywithpost[] = $daywith[0];
    }
  } else {
    $daywithpost = array();
  }

  if ( strpos( $_SERVER['HTTP_USER_AGENT'] , 'MSIE' ) !== false || stripos( $_SERVER['HTTP_USER_AGENT'] , 'camino' ) !== false || stripos( $_SERVER['HTTP_USER_AGENT'] , 'safari' ) !== false )
    $ak_title_separator = "\n";
  else
    $ak_title_separator = ', ';

  $ak_titles_for_day = array();
  $ak_post_titles = $wpdb->get_results( "SELECT ID, post_title, DAYOFMONTH( post_date ) as dom "
      . "FROM $wpdb->posts "
      . "WHERE YEAR( post_date ) = '$thisyear' "
      . "AND MONTH( post_date ) = '$thismonth' "
      . "AND post_date < '" . current_time( 'mysql' ) . "' "
      . "AND post_type IN ( $post_types ) AND post_status = 'publish'"
  );

  if ( $ak_post_titles ) {
    foreach ( (array) $ak_post_titles as $ak_post_title ) {

      $post_title = esc_attr( apply_filters( 'the_title' , $ak_post_title->post_title , $ak_post_title->ID ) );

      if ( empty( $ak_titles_for_day[ 'day_' . $ak_post_title->dom ] ) )
        $ak_titles_for_day[ 'day_'.$ak_post_title->dom ] = '';
      if ( empty( $ak_titles_for_day[ "$ak_post_title->dom" ] ) ) // first one
        $ak_titles_for_day[ "$ak_post_title->dom" ] = $post_title;
      else
        $ak_titles_for_day[ "$ak_post_title->dom" ] .= $ak_title_separator . $post_title;
    
    }
  }

  // See how much we should pad in the beginning
  // $pad = calendar_week_mod( date( 'w' , $unixmonth ) - $week_begins );
  // if ( 0 != $pad )
  //   $calendar_output .= "\n\t\t" . '<td colspan="' . esc_attr( $pad ) . '" class="pad">&nbsp;</td>';

  $daysinmonth = intval( date( 't' , $unixmonth ) );

  for ( $day = 1 ; $day <= $daysinmonth ; ++$day ) {
    // if ( isset( $newrow ) && $newrow )
    //   $calendar_output .= "\n\t</tr>\n\t<tr>\n\t\t";
    // $newrow = false;

    if ( $day == gmdate( 'j' , current_time( 'timestamp' ) ) && $thismonth == gmdate( 'm' , current_time( 'timestamp' ) ) && $thisyear == gmdate( 'Y' , current_time( 'timestamp' ) ) )
      $calendar_output .= '<li id="today" class="alog-today">';
    elseif ( $day > gmdate( 'j' , current_time( 'timestamp' ) ) && $thismonth == gmdate( 'm' , current_time( 'timestamp' ) ) && $thisyear == gmdate( 'Y' , current_time( 'timestamp' ) ) )
      $calendar_output .= '<li class="alog-future">';
    else
      $calendar_output .= '<li>';

    if ( in_array( $day , $daywithpost ) ) // any posts today?
        $calendar_output .= '<a class="alog-has-post" href="' . get_day_link( $thisyear , $thismonth , $day ) . "\" title=\"" . esc_attr( $ak_titles_for_day[$day] ) . "\">$day</a>";
    else
      $calendar_output .= $day;
    $calendar_output .= '</li>';

    // if ( 6 == calendar_week_mod( date( 'w' , mktime( 0 , 0 , 0 , $thismonth , $day , $thisyear ) ) - $week_begins ) )
    //   $newrow = true;
  }

  // $pad = 7 - calendar_week_mod( date( 'w' , mktime( 0 , 0 , 0 , $thismonth , $day , $thisyear ) ) - $week_begins );

  // if ( $pad != 0 && $pad != 7 )
  //   $calendar_output .= "\n\t\t" . '<td class="pad" colspan="' . esc_attr( $pad ) . '">&nbsp;</td>';

  $calendar_output .= '</ul>';

  if ( $previous ) {
    $calendar_output .= '<button id="alog-calendar-prev"><a href="' . get_alog_month_link( $previous->year, $previous->month ) . '" title="' . sprintf( __( 'View posts for %1$s %2$s' ), $wp_locale->get_month( $previous->month ), date( 'Y', mktime( 0,0,0, $previous->month, 1, $previous->year ) ) ) . '">&laquo; ' . $wp_locale->get_month_abbrev( $wp_locale->get_month( $previous->month ) ) . '</a></button>';
  } 
  // else {
  //   $calendar_output .= '<button id="alog-calendar-prev">&nbsp;</td>';
  // }
  // $calendar_output .= "\n\t\t" . '<td class="pad">&nbsp;</td>';
  
  if ( $next ) {    
    $calendar_output .= '<button id="alog-calendar-next"><a href="' . get_alog_month_link( $next->year , $next->month ) . '" title="' . esc_attr( sprintf( __( 'View posts for %1$s %2$s' ) , $wp_locale->get_month( $next->month ) , date( 'Y' , mktime( 0 , 0 , 0 , $next->month , 1 , $next->year ) ) ) ) . '">' . $wp_locale->get_month_abbrev( $wp_locale->get_month( $next->month ) ) . ' &raquo;</a></button>';
  } 
  // else {
  //   $calendar_output .= '<button id="alog-calendar-next" class="pad">&nbsp;</td>';
  // }

  $cache[$key] = $calendar_output;
  wp_cache_set( 'get_calendar' , $cache, 'calendar' );

  remove_filter( 'get_calendar' , 'ucc_get_calendar_filter' );
  $output = apply_filters( 'get_calendar',  $calendar_output );
  add_filter( 'get_calendar' , 'ucc_get_calendar_filter' );

  if ( $echo )
    echo $output;
  else
    return $output;
}

function alog_get_calendar_filter( $content ) {
  $output = alog_get_calendar( '', '', false );
  return $output;
}
add_filter( 'get_calendar', 'alog_get_calendar_filter', 10, 2 );

/**
 * Register a Custom Sidebar
 */
function alog_custom_sidebar() {
  register_sidebar( array(
    'name'          => __( 'Adventure Log Sidebar', 'adventure-log' ),
    'id'            => 'sidebar-alog',
    'description'   => __( 'Add a variety of Adventurous Widgets to your Adventure Log sidebar. These will not show up on any other page of your site.', 'adventure-log' ),
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget'  => '</aside>',
    'before_title'  => '<h1 class="widget-title">',
    'after_title'   => '</h1>'
  ) );
}
add_action( 'widgets_init', 'alog_custom_sidebar' );