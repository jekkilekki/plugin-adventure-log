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
  $new_options[ 'override_user_settings' ] = false;
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
      'Settings',                // $menu_title
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

add_action( 'admin_init', 'alog_admin_init' );
function alog_admin_init() {
  // admin_post_ is the start 'save_alog_options' is the value in our hidden <input> field on the settings page
  add_action( 'admin_post_save_alog_options', 'alog_process_options' );
}

function alog_process_options() {

  // Check that user has proper security access
  if ( ! current_user_can( 'manage_options' ) ) 
    wp_die( 'Sorry, you are not allowed to do that.' );

  // Check nonce field
  check_admin_referer( 'alog_options' );

  // Retrieve original plugin options array
  $options = alog_get_options();

  // Cycle through all text and radio values and store their options
  foreach( array( 'alog_wordcount' ) as $option_name ) {
    if ( isset( $_POST[ $option_name ] ) ) {
      $options[ $option_name ] = sanitize_text_field( $_POST[ $option_name ] );
    }
  }

  // Cycle through all checkbox values and set the options to true or false
  foreach( array( 'alog_override', 'alog_editing', 'alog_multiple', 'alog_future', 'alog_past' ) as $option_name ) {
    if ( isset( $_POST[ $option_name ] ) ) {
      $options[ $option_name ] = true;
    } else {
      $options[ $option_name ] = false;
    }
  }

  // Post Visibility radio button
  if ( isset( $_POST[ 'alog_visibility' ] ) && 
       array_key_exists( $_POST[ 'alog_visibility' ], array( 'public', 'private' ) ) ) {
    $options[ 'alog_visibility' ] = $_POST[ 'alog_visibility' ];
  } else {
    $options[ 'alog_visibility' ] = 'private'; // The default option
  }

  // Store updated options array to DB
  update_option( 'alog_options', $options );

  // Redirect to the configuration form
  wp_redirect( add_query_arg( 
                  array( 'page' => 'alog_settings',
                         'post_type' => 'alog',  
                         'message' => '1' ), 
                  admin_url( 'edit.php' ) ) );
  exit;
}

/**
 * Display callback for the submenu page.
 */
function alog_build_settings_page() {
  // Retrieve plugin options from the DB
  $options = alog_get_options();
  ?>

  <div id="alog-main-options" class="wrap">
    <h1><i class="ra ra-sword"></i><?php _e( 'Adventure Log Settings', 'adventure-log' ); ?></h1>
    
    <?php if ( isset( $_GET[ 'message' ] ) && $_GET[ 'message' ] == '1' ) : ?>
      <div id='message' class='updated fade'>
        <p><strong>Settings Saved</strong></p>
      </div>
    <?php endif; ?>
    
    <p><?php _e( 'Adjust sitewide default user settings. These can also override personal user settings if desired.', 'adventure-log' ); ?></p>
  
    <!-- <h2 class="nav-tab-wrapper">
      <a href="#" class="nav-tab nav-tab-active">Tab 1</a>
      <a href="#" class="nav-tab">Tab 2</a>
    </h2> -->

    <form method="post" action="admin-post.php">
      <input type="hidden" name="action" value="save_alog_options" />

      <!-- Add security through hidden referrer field -->
      <!-- wp_nonce_field( $action, $name, $referer, $echo ); -->
      <?php wp_nonce_field( 'alog_options' ); ?>

      <table class="form-table">
        <tbody>
          <tr valign="top">
            <th scope="row">
              <label>Override</label>
            </th>
            <td>
            <label>
              <input type="checkbox" id="alog_override" name="alog_override" value="<?php echo $options[ 'override_user_settings' ] ? 1 : 0; ?>" />
              Override individual user settings
            </label>
            </td>
          </tr>
          <tr valign="top">
            <th scope="row">
              <label for="alog_wordcount">Daily Word Count Target</label>
            </th>
            <td>
              <input type="text" id="alog_wordcount" name="alog_wordcount" value="<?php echo esc_attr( $options[ 'target_word_count' ] ); ?>" />
            </td>
          </tr>
          <tr valign="top">
            <th scope="row">
              <label>Log Writing Options</label>
            </th>
            <td>
              <fieldset>
                <label>
                  <input type="checkbox" id="alog_editing" name="alog_editing" value="<?php echo $options[ 'allow_editing' ] ? 1 : 0; ?>" />
                  Allow editing
                </label>
                <br>
                <label>
                  <input type="checkbox" id="alog_multiple" name="alog_multiple" value="<?php echo $options[ 'allow_multiple_posts_per_day' ] ? 1 : 0; ?>" />
                  Allow multiple Logs per day
                </label>
                <br>
                <label>
                  <input type="checkbox" id="alog_future" name="alog_future" value="<?php echo $options[ 'allow_future_posts' ] ? 1 : 0; ?>" />
                  Allow writing Future Logs
                </label>
                <br>
                <label>
                  <input type="checkbox" id="alog_past" name="alog_past" value="<?php echo $options[ 'allow_past_posts' ] ? 1 : 0; ?>" />
                  Allow writing Past Logs
                </label>
              </fieldset>
            </td>
          </tr>
          <tr valign="top">
            <th scope="row">
              <label>Log Visibility</label>
            </th>
            <td>
              <fieldset>
                <legend class="screen-reader-text">
                  <span>Log Visibility</span>
                </legend>
                <label>
                  <input type="radio" name="alog_visibility" value="Public" checked="<?php echo $options[ 'post_visiblity' ] === 'public' ? 'checked' : ''; ?>">
                  <span>Public</span>
                </label>
                <br>
                <label>
                  <input type="radio" name="alog_visibility" value="Private" checked="<?php echo $options[ 'post_visiblity' ] === 'public' ? '' : 'checked'; ?>">
                  <span>Private</span>
                </label>
              </fieldset>
            </td>
          </tr>
        </tbody>
      </table>

      <input type="submit" value="Save" class="button-primary" />
    </form>
  </div>
  <?php
  // Default plugin options
  // $new_options[ 'target_word_count' ] = 500;
  // $new_options[ 'allow_editing' ] = false;
  // $new_options[ 'allow_multiple_posts_per_day' ] = false;
  // $new_options[ 'allow_future_posts' ] = false;
  // $new_options[ 'allow_past_posts' ] = false;
  // $new_options[ 'post_visibility' ] = 'private';
}