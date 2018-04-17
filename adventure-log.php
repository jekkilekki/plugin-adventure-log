<?php
/**
 * @since           1.0.0
 * @package         Adventure_Log
 * @author          Aaron Snowberger <jekkilekki@gmail.com>
 * 
 * @wordpress-plugin
 * Plugin Name:     Adventure Log
 * Plugin URI:      https://github.com/jekkilekki/plugin-adventure-log
 * Description:     A simple plugin that tracks your daily writing and sets a minimum daily writing goal.
 * Version:         1.0.0
 * Author:          Aaron Snowberger
 * Author URI:      http://www.aaronsnowberger.com
 * Text Domain:     adventure-log
 * Domain Path:     /languages/
 * License:         GPL2
 * 
 * Requires at least: 4.0
 * Tested up to:    4.9.4
 */
/**
 * Adventure Log tracks your daily writing and sets a minimum goal.
 * Copyright (C) 2018  AARON SNOWBERGER (email: JEKKILEKKI@GMAIL.COM)
 * 
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 * Or go to: https://www.gnu.org/licenses/gpl.html
 */

/**
 * Include our functions file.
 */
require_once plugin_dir_path( __FILE__ ) . 'inc/functions.php';
require_once plugin_dir_path( __FILE__ ) . 'inc/template-tags.php';
// require_once plugin_dir_path( __FILE__ ) . 'inc/template-functions.php';

/**
 * Set default options.
 */
require_once plugin_dir_path( __FILE__ ) . 'inc/settings.php';
require_once plugin_dir_path( __FILE__ ) . 'inc/settings.api.php';
register_activation_hook( __FILE__, 'alog_set_default_options' );

/**
 * Register Adventure Log Post Type.
 */
require_once plugin_dir_path( __FILE__ ) . 'inc/posttypes.php';
register_activation_hook( __FILE__, 'adventure_log_flush_rewrite_init' );

/**
 * Register Adventurer Role.
 */
require_once plugin_dir_path( __FILE__ ) . 'inc/roles.php';
register_activation_hook( __FILE__, 'adventure_log_register_role' );
register_deactivation_hook( __FILE__, 'adventure_log_remove_role' );

/**
 * Register/remove new Capabilities for Adventurers.
 */
register_activation_hook( __FILE__, 'adventure_log_add_capabilities' );
register_deactivation_hook( __FILE__, 'adventure_log_remove_capabilities' );

function adventure_log_scripts() {

  // This should only happen on non-admin, single posts or pages
  if ( ! is_admin() && ( is_single() || is_archive() ) ) {

    // Make sure only logged in and authorized users (can edit their own posts) can use this function
    // if ( is_user_logged_in() && current_user_can( 'edit_alogs' ) ) {

      // Enqueue and localize our script (pass our REST url, nonce, and current Post ID to JS)
      // wp_enqueue_script( 'alog_tinymce_js', includes_url( 'js/tinymce/' ) . 'wp-tinymce.php', array( 'jquery' ), '20140415', true );
      // wp_enqueue_script( 'alog_front_tinymce', plugin_dir_url( __FILE__ ) . 'js/tinymce.frontend.js', array( 'jquery', 'alog_tinymce_js' ), '20140415', true );
      wp_enqueue_script( 'adventure_log_script', plugin_dir_url( __FILE__ ) . 'js/frontend.ajax.js', array( 'jquery' ), '20180330', true );
      wp_localize_script( 'adventure_log_script', 'WP_API_settings', array(
        'root'        => esc_url_raw( rest_url() ),
        'nonce'       => wp_create_nonce( 'wp_rest' ), /* nonce MUST be 'wp_rest' in order to work properly */
        'current_ID'  => get_the_ID(),
        'new_post'    => strpos( $_SERVER[ 'REQUEST_URI' ], '/?new=true' ) && is_day(),
        'singular'    => is_singular()
      ));

      // Enqueue our stylesheet for frontend editing
      wp_enqueue_style( 'adventure_log_style', plugins_url( 'css/style.css', __FILE__ ) );

      // Enqueue RPG Awesome icon font
      wp_enqueue_style( 'adventure_log_fonts', plugins_url( 'fonts/rpg-awesome.css', __FILE__ ) );
      
      // Enqueue D3 for our calendar
      // wp_enqueue_script( 'adventure_log_d3', '//d3js.org/d3.v4.min.js' );
      // wp_enqueue_script( 'adventure_log_d3_calendar', plugin_dir_url( __FILE__ ) . 'js/calendar.d3.js', array( 'adventure_log_d3' ), '20180403', true );
      // wp_localize_script( 'adventure_log_d3_calendar', 'alog_calendar', array(
      //   'data'  => adventure_logs_last_year()
      // ));

    // } // END if ( is_user_logged_in() ... )

  } // END if ( ! is_admin() ... )

} // END adventure_log_scripts()
add_action( 'wp_enqueue_scripts', 'adventure_log_scripts' );

/**
 * Ajax Login Script
 */
function alog_ajax_login_init() {

  // Enqueue and localize our script (pass our REST url, nonce, and current Post ID to JS)
  wp_enqueue_script( 'alog_ajax_login_script', plugin_dir_url( __FILE__ ) . 'js/login.ajax.js', array( 'jquery' ), '20180413', true );
  wp_localize_script( 'alog_ajax_login_script', 'ajax_login_object', array(
    'ajax_url'      => esc_url( admin_url( 'admin-ajax.php' ) ),
    'redirect_url'  => esc_url( $_SERVER[ 'REQUEST_URI' ] ), // login on whatever page you're on
    'message'       => __( 'Checking user credentials...', 'adventure-log' ),
  ));

  // Enable the user with no privileges to run ajax_login() in AJAX
  add_action( 'wp_ajax_nopriv_ajaxlogin', 'alog_ajax_login' );
}

// function alog_user_logged_in() {
  // Execute the action only if the user isn't logged in
  // if ( ! is_user_logged_in() ) {
    add_action( 'init', 'alog_ajax_login_init' );
  // }
// }
// add_action( 'init', 'alog_user_logged_in' );

function adventure_log_admin_scripts( $hook ) {
  global $post, $settings_page;

  if ( $hook == 'post-new.php' || $hook == 'post.php' || $hook == 'edit.php'
      || $hook === $settings_page ) {
      // Enqueue RPG Awesome icon font
      wp_enqueue_style( 'adventure_log_admin_fonts', plugins_url( 'fonts/rpg-awesome.css', __FILE__ ) );
  }
}
add_action( 'admin_enqueue_scripts', 'adventure_log_admin_scripts' );

/**
 * Redirect user after successful login.
 * 
 * @see https://developer.wordpress.org/reference/hooks/login_redirect/
 * 
 * @param string $redirect_to URL to redirect to.
 * @param string $request URL the user is coming from.
 * @param object $user Logged user's data.
 * @return string
 */
function adventure_login_redirect( $redirect_to, $request, $user ) {
  // is there a user to check?
  if ( isset( $user->roles ) && is_array( $user->roles ) ) {
    // check for subscribers 
    if ( ! is_admin() && in_array( 'adventurer', $user->roles ) ) {
      $wp_timestamp = current_time( 'timestamp' );
      $today = date( $wp_timestamp );
      $year = date( 'Y', $wp_timestamp );
      $monnum = date( 'n', $wp_timestamp );
      $month = date( 'F', $wp_timestamp );
      $day = date( 'j', $wp_timestamp );
      $days_this_month = date( 't' );
      // redirect to the archive page
      $redirect_to = home_url() . '/alog/';
    }
  }
  return $redirect_to;
}
add_filter( 'login_redirect', 'adventure_login_redirect', 10, 3 );

/**
 * Create a custom ARCHIVE page for Adventure Logs
 * @see https://codex.wordpress.org/Plugin_API/Filter_Reference/archive_template
 */
function adventure_log_archive_page( $template ) {
  global $post;

  if ( is_post_type_archive( 'alog' ) ) {
    $template = dirname( __FILE__ ) . '/templates/archive-alog.php';
  }
  return $template;
}
add_filter( 'archive_template', 'adventure_log_archive_page' );

/**
 * Create a custom SINGLE page for Adventure Logs
 * @see https://codex.wordpress.org/Plugin_API/Filter_Reference/archive_template
 */
function adventure_log_single_page( $template ) {
  global $post;

  if ( $post->post_type == 'alog' ) {
    $template = dirname( __FILE__ ) . '/templates/single-alog.php';
  }
  return $template;
}
add_filter( 'single_template', 'adventure_log_single_page' );
