<?php
/**
 * Plugin Settings.
 */

function alog_set_default_options() {
  alog_get_options();
}

function alog_get_options() {
  // Get current options
  $options = get_option( 'alog_options', array() );

  // Default plugin options
  $new_options[ 'target_word_count' ] = 500;
  $new_options[ 'allow_editing' ] = false;
  $new_options[ 'allow_multiple_posts_per_day' ] = false;
  $new_options[ 'allow_future_posts' ] = false;
  $new_options[ 'allow_past_posts' ] = false;
  $new_options[ 'post_visibility' ] = 'private';

  // wp_parse_args compares the $options retrieved from our DB with the default $new_options
  $merged_options = wp_parse_args( $options, $new_options );

  // Check if we added new options, or if anything changed
  // And if so, update the DB
  $compare_options = array_diff_key( $new_options, $options );
  if ( empty( $options ) || ! empty( $compare_options ) ) {
    update_option( 'alog_options', $merged_options );
  }

  return $merged_options;
}

/**
 * Admin Settings Page.
 */
add_action( 'admin_menu', 'alog_settings_menu' ); // number indicates menu priority
function alog_settings_menu() {

  // Create Submenu item in Adventure Logs CPT
  add_submenu_page(
      'edit.php?post_type=alog',                        // $parent_slug
      'Adventure Log Settings',  // $page_title
      'ALog Settings',                // $menu_title
      'manage_options',                                 // $capability (required of the user)
      'alog_settings',                                  // $menu_slug
      'alog_build_settings_page'                        // $function (page builder callback)
  );

  // Create a Submenu item to an external page.
  global $submenu;
  $url = 'https://aaronsnowberger.com';
  $submenu['edit.php?post_type=alog'][] = array( 'Aaron', 'manage_options', $url ); 

  // Create a new page under "Settings"
  // add_options_page( $page_title, $menu_title, $user_capability, $menu_slug, $callback_function )
  // add_options_page( __( 'Adventure Log Settings', 'adventure-log'),
  //                   __( 'ALog Settings', 'adventure-log' ),
  //                   'manage_options',
  //                   'alog_settings',
  //                   'alog_build_settings_page'
  //                 );
}

/**
 * Display callback for the submenu page.
 */
function alog_build_settings_page() {
  // Retrieve plugin options from the DB
  $options = alog_get_options();
  ?>

  <div id="alog-main-options" class="wrap">
    <h1><?php _e( 'Adventure Log Settings', 'adventure-log' ); ?></h1>
    <p><?php _e( 'Adjust sitewide default user settings. These can also override personal user settings if desired.', 'adventure-log' ); ?></p>
  
    <form method="post" action="admin-post.php">
      <input type="hidden" name="action" value="save_alog_options" />

      <!-- Add security through hidden referrer field -->
      <?php wp_nonce_field( 'alog_options' ); ?>
      Daily Word Count Target: <input type="text" name="alog_word_count" value="<?php echo esc_attr( $options[ 'target_word_count' ] ); ?>" />

      <input type="submit" value="Save" class="button-primary" />
    </form>
  </div>
  <?php
}