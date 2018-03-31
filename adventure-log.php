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
 * Register Adventure Log Post Type.
 */
require_once plugin_dir_path( __FILE__ ) . 'inc/posttypes.php';
register_activation_hook( __FILE__, 'adventure_log_rewrite_flush_init' );

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
  if ( ! is_admin() && is_single() ) {

    // Make sure only logged in and authorized users (can edit their own posts) can use this function
    if ( is_user_logged_in() && current_user_can( 'edit_logs' ) ) {

      // Enqueue and localize our script (pass our REST url, nonce, and current Post ID to JS)
      wp_enqueue_script( 'adventure_log_script', plugin_dir_url( __FILE__ ) . 'js/frontend.ajax.js', array( 'jquery' ), '20180330', true );
      wp_localize_script( 'adventure_log_script', 'WP_API_settings', array(
        'root'        => esc_url_raw( rest_url() ),
        'nonce'       => wp_create_nonce( 'wp_rest' ), /* nonce MUST be 'wp_rest' in order to work properly */
        'current_ID'  => get_the_ID()
      ));

    } // END if ( is_user_logged_in() ... )

  } // END if ( ! is_admin() ... )

} // END adventure_log_scripts()
add_action( 'wp_enqueue_scripts', 'adventure_log_scripts' );