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
 */

function adventure_log_scripts() {
  if ( ! is_admin() && is_single() ) {
    if ( is_user_logged_in() && current_user_can( 'edit_posts' ) ) {
      wp_enqueue_script( 'adventure_log_script', plugin_dir_url( __FILE__ ) . 'js/frontend.ajax.js', array( 'jquery' ), '20180330', true );
      wp_localize_script( 'adventure_log_script', 'WPsettings', array(
        'root'        => esc_url_raw( rest_url() ),
        'nonce'       => wp_create_nonce( 'adventure' ),
        'current_ID'  => get_the_ID()
      ));
    }
  }
}
add_action( 'wp_enqueue_scripts', 'adventure_log_scripts' );