<?php
/**
 * 
 */

function alog_is_home_url() {
  // Get today's date from the archive URL (allows navigation to ANY date)
  $full_url = $_SERVER[ 'REQUEST_URI' ];
  if ( substr( $full_url, -6 ) == '/alog/' || substr( $full_url, -5 ) == '/alog' ) {
    return true;
  }
  return false;
}

/**
 * Ajax Login Function
 * @see http://natko.com/wordpress-ajax-login-without-a-plugin-the-right-way/
 */
function alog_ajax_login() {

  // First, check nonce
  check_ajax_referer( 'ajax-login-nonce', 'security' );

  // Nonce is checked, get POST data and sign on
  $info = array();
  $info['user_login'] = $_POST['username'];
  $info['user_password'] = $_POST['password'];
  $info['remember'] = true;

  $user_signon = wp_signon( $info, false );
  if ( is_wp_error( $user_signon ) ) {
    echo json_encode( array( 'loggedin' => false, 'message' => __( 'Wrong username or password.', 'adventure-log' ) ) );
  } else {
    echo json_encode( array( 'loggedin' => true, 'message' => __( 'Login successful, redirecting... ', 'adventure-log' ) ) );
  }

  die();
}

/**
 * Function to retrieve the current URI value and parse it to find the date page
 * 
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
      'year'    => $today['year'],
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
  // if ( $date[0] > $today['year'] ||
  //       ( $date[0] > $today['year'] && $date[1] > $today['monnum'] ) || 
  //       ( $date[0] > $today['year'] && $date[1] > $today['monnum'] && $date[2] > $today['day'] ) ) {
  //   wp_redirect( esc_url( home_url() . '/alog/' ) );
  //   exit;
  // }

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
  return date( 't', mktime(0,0,0,(int)$month,1,(int)$year) );
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
  if ( is_int( $url_date['day'] ) )
    return mktime(0, 0, 0, (int)$url_date['monnum'], (int)$url_date['day'], (int)$url_date['year'] );
  else
    return current_time( 'timestamp' );
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

function get_alog_day_link( $year, $month, $day ) {
  return esc_url( home_url() . "/alog/$year/$month/$day/" );
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
 * Add TinyMCE WordCount Plugin
 * @see https://wordpress.org/support/topic/how-to-display-wp-editor-word-count/#post-7538501
 */
add_filter( 'mce_external_plugins', 'alog_tinymce_plugins' );
function alog_tinymce_plugins() {
  $plugins = array( 'wordcount' ); 
  $plugins_array = array();

  // Build the response - the key is the plugin name
  foreach ( $plugins as $plugin ) {
    $plugins_array[ $plugin ] = plugins_url( '../js/wordcount.min.js', __FILE__ );
  }
  return $plugins_array;
}

/**
 * Alog Word Count (Number)
 */
function alog_word_count_numeric() {
  return str_word_count( strip_tags( get_post_field( 'post_content', get_the_ID() ) ) );
}

function adventure_logs_last_year() {
  $dates = array();

  $args = array(
    'post_type' => 'alog',
    'date_query'  => array(
        'column'  => 'post_date_gmt',
        'after'   => '1 year ago'
      )
  );

  $query = new WP_Query( $args );
  if ( $query->have_posts() ) {
    while ( $query->have_posts() ) {
      $dates[] = $query->the_post();
    }
  }

  return $dates;
}

/**
 * Alog Calendar
 * @see https://jennifer.blog/2010/07/15/adding-custom-post-types-to-get_calendar-and-the-calendar-widget
 * 
 * alog_get_calendar() :: Extends get_calendar() by including the ALog CPT.
 * Derived from get_calendar() code in /wp-includes/general-template.php
 */
function alog_get_calendar( $post_types = '', $initial = true, $echo = true, $year = '' ) {
  global $wpdb, $m, $monthnum, $year, $wp_locale, $posts;

  if ( empty( $post_types ) || ! is_array( $post_types ) ) {
    $args = array(
      'public' => true,
      'private' => true,
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

  /**
   * Calendar Output Begins
   */
  /* translators: Calendar caption: 1: month name, 2: 4-digit year */
  $calendar_caption = _x( '%1$s %2$s', 'calendar caption' );
  $calendar_output = '<div id="alog-calendar" summary="' . esc_attr__( 'Adventure Log Calendar', 'adventure-log' ) . '">';
  
  // Begin calendar
  $calendar_output .= '<ul class="alog-date-boxes">';

  // Previous button - if posts exist last month
  if ( $previous ) {
    $calendar_output .= '<li id="alog-calendar-prev"><a href="' . get_alog_month_link( $previous->year, $previous->month ) . '" title="' . sprintf( __( 'View posts for %1$s %2$s' ), $wp_locale->get_month( $previous->month ), date( 'Y', mktime( 0,0,0, $previous->month, 1, $previous->year ) ) ) . '">&laquo; ' . $wp_locale->get_month_abbrev( $wp_locale->get_month( $previous->month ) ) . '</a></li>';
  }

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

  // Create titles for posts (visible on hover)
  if ( strpos( $_SERVER['HTTP_USER_AGENT'] , 'MSIE' ) !== false || stripos( $_SERVER['HTTP_USER_AGENT'] , 'camino' ) !== false || stripos( $_SERVER['HTTP_USER_AGENT'] , 'safari' ) !== false )
    $ak_title_separator = "\n";
  else
    $ak_title_separator = ', ';

  $ak_titles_for_day = array();
  $ak_post_titles = $wpdb->get_results( "SELECT ID, post_title, post_content, DAYOFMONTH( post_date ) as dom "
      . "FROM $wpdb->posts "
      . "WHERE YEAR( post_date ) = '$thisyear' "
      . "AND MONTH( post_date ) = '$thismonth' "
      . "AND post_date < '" . current_time( 'mysql' ) . "' "
      . "AND post_type IN ( $post_types ) AND post_status = 'publish'"
  );

  $wordcount_for_day = array();
  $options = alog_get_options();
  $target_wordcount = $options['target_word_count'];

  if ( $ak_post_titles ) {
    foreach ( (array) $ak_post_titles as $ak_post_title ) {

      $post_title = esc_attr( apply_filters( 'the_title', $ak_post_title->post_title, $ak_post_title->ID ) );
      $post_content = esc_attr( apply_filters( 'the_content', $ak_post_title->post_content, $ak_post_title->ID ) );
      $post = strip_tags( $post_content );
      $post = explode( ' ', $post );
      $count = count( $post );

      $post_wordcount = str_word_count( strip_tags( esc_attr( apply_filters( 'the_content', $ak_post_title->post_content, $ak_post_title->ID ) ) ) );

      if ( empty( $ak_titles_for_day[ 'day_' . $ak_post_title->dom ] ) ) :
        $ak_titles_for_day[ 'day_'.$ak_post_title->dom ] = '';
      endif;

      if ( empty( $ak_titles_for_day[ "$ak_post_title->dom" ] ) ) : // first one
        $ak_titles_for_day[ "$ak_post_title->dom" ] = $post_title;
        $wordcount_for_day[ "$ak_post_title->dom" ] = (int) $count;
      else :
        $ak_titles_for_day[ "$ak_post_title->dom" ] .= $ak_title_separator . $post_title;
        $wordcount_for_day[ "$ak_post_title->dom" ] .= (int) $count;
      endif;
      
    }
  }

  $daysinmonth = intval( date( 't' , $unixmonth ) );
  $points = 0;

  for ( $day = 1 ; $day <= $daysinmonth ; ++$day ) {

    // Check if a FUTURE date
    if ( $day > gmdate( 'j' , current_time( 'timestamp' ) ) && $thismonth == gmdate( 'm' , current_time( 'timestamp' ) ) && $thisyear == gmdate( 'Y' , current_time( 'timestamp' ) ) ) :
      $calendar_output .= '<li class="alog-future">' . $day;
    
    // Check if date HAS POST
    elseif ( in_array( $day , $daywithpost ) ) : // any posts today?
      $calendar_output .= '<li class="alog-has-post';

      // Check if TODAY (has posts)
      if ( $day == gmdate( 'j' , current_time( 'timestamp' ) ) && $thismonth == gmdate( 'm' , current_time( 'timestamp' ) ) && $thisyear == gmdate( 'Y' , current_time( 'timestamp' ) ) )
        $calendar_output .= ' alog-today';

      // Here we check our word count for the day
      if ( $wordcount_for_day[$day] > $target_wordcount ) : // 100% done
        $calendar_output .= ' success-100';
        $points += 8;
      elseif ( $wordcount_for_day[$day] > $target_wordcount * (4/5) ) : // 80% done
        $calendar_output .= ' success-80';
        $points += 5;
      elseif ( $wordcount_for_day[$day] > $target_wordcount * (3/5) ) : // 60% done
        $calendar_output .= ' success-60';
        $points += 4;
      elseif ( $wordcount_for_day[$day] > $target_wordcount * (2/5) ) : // 40% done
        $calendar_output .= ' success-40';
        $points += 3;
      elseif ( $wordcount_for_day[$day] > $target_wordcount * (1/5) ) : // 20% done
        $calendar_output .= ' success-20';
        $points += 2;
      else : // < 20% done
        $calendar_output .= ' success-0';
        $points += 1;
      endif; 
      
      $calendar_output .= '"><a class="alog-post" href="' . get_alog_day_link( $thisyear , $thismonth , $day ) . "\" title=\"" . esc_attr( $ak_titles_for_day[$day] ) . "\">$day</a>";

    // Check if TODAY (no posts)
    elseif ( $day == gmdate( 'j' , current_time( 'timestamp' ) ) && $thismonth == gmdate( 'm' , current_time( 'timestamp' ) ) && $thisyear == gmdate( 'Y' , current_time( 'timestamp' ) ) ) :
      $logged_in = is_user_logged_in() ? '?new=true' : '';
      $calendar_output .= '<li class="alog-today"><a href="' . get_alog_day_link( $thisyear , $thismonth , $day ) . $logged_in . '">' . $day . '</a></li>';
    
    // Or, just output the <li>
    else :
      $calendar_output .= '<li>' . $day;
    endif;

    // End with the date and closing </li>
    $calendar_output .= '</li>';

  }

  // Next button
  if ( $next ) {    
    $calendar_output .= '<li id="alog-calendar-next"><a href="' . get_alog_month_link( $next->year , $next->month ) . '" title="' . esc_attr( sprintf( __( 'View posts for %1$s %2$s' ) , $wp_locale->get_month( $next->month ) , date( 'Y' , mktime( 0 , 0 , 0 , $next->month , 1 , $next->year ) ) ) ) . '">' . $wp_locale->get_month_abbrev( $wp_locale->get_month( $next->month ) ) . ' &raquo;</a></li>';
  } 

  $calendar_output .= '</ul>';

  $calendar_output .= '<footer class="alog-calendar-footer">';
  // Navigation buttons

  // Previous button - if posts exist last month
  // if ( $previous ) {
  //   $calendar_output .= '<button id="alog-calendar-prev"><a href="' . get_alog_month_link( $previous->year, $previous->month ) . '" title="' . sprintf( __( 'View posts for %1$s %2$s' ), $wp_locale->get_month( $previous->month ), date( 'Y', mktime( 0,0,0, $previous->month, 1, $previous->year ) ) ) . '">&laquo; ' . $wp_locale->get_month_abbrev( $wp_locale->get_month( $previous->month ) ) . '</a></button>';
  // }
  // // Next button
  // if ( $next ) {    
  //   $calendar_output .= '<button id="alog-calendar-next"><a href="' . get_alog_month_link( $next->year , $next->month ) . '" title="' . esc_attr( sprintf( __( 'View posts for %1$s %2$s' ) , $wp_locale->get_month( $next->month ) , date( 'Y' , mktime( 0 , 0 , 0 , $next->month , 1 , $next->year ) ) ) ) . '">' . $wp_locale->get_month_abbrev( $wp_locale->get_month( $next->month ) ) . ' &raquo;</a></button>';
  // } 

  // Calendar Meta info
  $calendar_output .= '<ul class="alog-calendar-meta"><li>';
  // $calendar_output .= '<h3 class="alog-calendar-title">' . sprintf( $calendar_caption, $wp_locale->get_month( $thismonth ), date( 'Y', $unixmonth ) ) . '</h3></li>';

  // Display points accumulated if logged in
  if ( is_user_logged_in() ) :
    $calendar_output .= '<li><pre><span class="alog-calendar-points">' . $points . ' points this month</span>';
    $calendar_output .= '<span class="alog-calendar-points">' . $points . ' total points</span></pre></li>';
  endif;

  $calendar_output .= '</ul></footer><!-- .calendar-meta -->';
  $calendar_output .= '</div><!-- #alog-calendar -->';

  $cache[$key] = $calendar_output;
  wp_cache_set( 'get_calendar' , $cache, 'calendar' );

  remove_filter( 'get_calendar' , 'alog_get_calendar_filter' );
  $output = apply_filters( 'get_calendar',  $calendar_output );
  add_filter( 'get_calendar' , 'alog_get_calendar_filter' );

  if ( $echo )
    echo $output;
  else
    return $output;
}

// function alog_get_calendar_filter( $content ) {
//   $output = alog_get_calendar( '', '', false );
//   return $output;
// }
// add_filter( 'get_calendar', 'alog_get_calendar_filter', 10, 2 );

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