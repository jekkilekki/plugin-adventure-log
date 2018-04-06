<?php 
/**
 * Uninstall the plugin and delete the plugin options.
 */

// Check that code was called from WordPress with 
// the uninstallation constant declared
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
  exit;
}

// Check if options exist and delete them if present
if ( false != get_option( 'alog_options' ) ) {
  delete_option( 'alog_options' );
}