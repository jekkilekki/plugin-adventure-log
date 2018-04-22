<?php
/**
 * Plugin Settings.
 */

function alog_set_default_options() {
  alog_get_options();
}

function alog_reset_default_options() {
  // Get current options
  $options = get_option( 'alog_options', array() );

  // Default plugin options
  $options[ 'override_user_settings' ] = false;
  $options[ 'target_word_count' ] = 500;
  $options[ 'allow_editing' ] = false;
  $options[ 'allow_multiple_posts_per_day' ] = false;
  $options[ 'allow_future_posts' ] = false;
  $options[ 'allow_past_posts' ] = false;
  $options[ 'post_visibility' ] = 'private';

  update_option( 'alog_options', $options );
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
  global $settings_page;
  $settings_page = add_submenu_page(
      'edit.php?post_type=alog',                        // $parent_slug
      'Adventure Log Settings',  // $page_title
      'Settings',                // $menu_title
      'manage_options',                                 // $capability (required of the user)
      'alog_settings',                                  // $menu_slug
      'alog_build_settings_page'                        // $function (page builder callback)
  );

  if ( $settings_page ) {
    add_action( 'load-' . $settings_page, 'alog_help_tabs' );
  }

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

function alog_help_tabs() {
  $screen = get_current_screen();
  $screen->add_help_tab( array(
            'id'      => 'alog-plugin-help-instructions',
            'title'   => __( 'Instructions', 'adventure-log' ),
            'callback'=> 'alog_plugin_help_instructions'
  ) );

  $screen->add_help_tab( array(
    'id'      => 'alog-plugin-help-faq',
    'title'   => __( 'FAQ', 'adventure-log' ),
    'callback'=> 'alog_plugin_help_faq'
  ) );

  $screen->set_help_sidebar( '<p>This is the sidebar content.</p>' );
}

function alog_plugin_help_instructions() {
  ?>
    <p>These instructions teach how to use the plugin.</p>
  <?php
}

function alog_plugin_help_faq() {
  ?>
    <p>This is the FAQ section for the plugin.</p>
  <?php
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
  foreach( array( 'target_word_count' ) as $option_name ) {
    if ( isset( $_POST[ $option_name ] ) ) {
      $options[ $option_name ] = sanitize_text_field( $_POST[ $option_name ] );
    }
  }

  // Cycle through all checkbox values and set the options to true or false
  foreach( array( 'override_user_settings', 
                  'allow_editing', 
                  'allow_multiple_posts_per_day', 
                  'allow_future_posts', 
                  'allow_past_posts' ) as $option_name ) {
    if ( isset( $_POST[ $option_name ] ) ) {
      $options[ $option_name ] = true;
    } else {
      $options[ $option_name ] = false;
    }
  }

  // Post Visibility radio button
  echo '<h1><pre>' . $_POST[ 'post_visibility' ] . '</pre></h1>';
  if ( isset( $_POST[ 'post_visibility' ] ) && 
       array_key_exists( $_POST[ 'post_visibility' ], array( 'public', 'private' ) ) ) {
    $options[ 'post_visibility' ] = 'public';
  } else {
    $options[ 'post_visibility' ] = 'private'; // The default option
  }

  // Store updated options array to DB
  update_option( 'alog_options', $options );

  // Temporarily delete extra options
  foreach( array( 'alog_wordcount', 'alog_override', 'alog_editing', 'alog_multiple', 'alog_future', 'alog_past', 'alog_visibility' ) as $option_name ) {
    delete_option( $options[ $option_name ] );
  }

  // Determine which message to send
  $message = '';
  if ( isset( $_POST[ 'reset_defaults' ] ) ) {
    alog_reset_default_options();
    $message = '2';
  } else {
    $message = '1';
  }

  // Redirect to the configuration form
  wp_redirect( add_query_arg( 
                  array( 'page' => 'alog_settings',
                         'post_type' => 'alog',  
                         'message' => $message ), 
                  admin_url( 'edit.php' ) ) );
  exit;
}

/**
 * Display callback for the submenu page.
 */
function alog_build_settings_page() {
  // Retrieve plugin options from the DB
  $options = alog_get_options();

  global $alog_settings_screen;
  $alog_settings_screen = isset( $_GET['tab'] ) ? $_GET['tab'] : 'welcome';
  $widget_screen = ( isset( $_GET['tab'] ) && 'widgets' == $_GET['tab'] );
  $badges_screen = (isset( $_GET['tab'] ) && 'badges' == $_GET['tab' ] );
  ?>

  <div id="alog-main-options" class="wrap">
    <h1><i class="ra ra-sword"></i><?php _e( 'Adventure Log Settings', 'adventure-log' ); ?></h1>
    
    <?php 
    if ( isset( $_GET[ 'message' ] ) ) :
          if ( $_GET[ 'message' ] == '1' ) : $message = 'Settings Saved'; 
          elseif ( $_GET[ 'message' ] == '2' ) : $message = "Default plugin settings restored";
          endif;  
          ?>
      <div id='message' class='updated fade'>
        <p><strong><?php echo $message; ?></strong></p>
      </div>
    <?php endif; ?>
    
    <p><?php _e( 'Adjust sitewide default user settings. These can also override personal user settings if desired.', 'adventure-log' ); ?></p>
  
    <?php
    // echo '<pre>';
    // var_dump( $options );
    // echo '</pre>';

    // Tabbed Navigation and Settings:
    // http://www.kvcodes.com/2016/11/create-tabs-wordpress-settings-page/
    ?>
    <h2 class="nav-tab-wrapper">
      <a href="<?php echo admin_url( 'edit.php?post_type=alog&page=alog_settings' ); ?>" class="nav-tab <?php if ( ! isset( $_GET['tab'] ) || $_GET['tab'] == 'welcome' ) echo 'nav-tab-active'; ?>">Writing</a>
      <a href="<?php echo esc_url( add_query_arg( array( 'tab' => 'widgets' ), 'edit.php?post_type=alog&page=alog_settings' ) ); ?>" class="nav-tab">Widgets</a>
      <a href="<?php echo esc_url( add_query_arg( array( 'tab' => 'badges' ), 'edit.php?post_type=alog&page=alog_settings' ) ); ?>" class="nav-tab">Badges</a>
    </h2>

    <form method="post" action="admin-post.php">
      
      <?php 
      if ( $widget_screen ) {
        settings_fields( 'alog_widget_options' );
        do_settings_sections( 'alog_widget_settings' );
        submit_button();
      } elseif ( $badges_screen ) {
        settings_fields( 'alog_badges_options' );
        do_settings_sections( 'alog_badges_settings' );
        submit_button();
      } else {
        settings_fields( 'alog_settings' );
        do_settings_sections( 'alog_settings_api' );
        submit_button();
      }
      ?>

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
              <input type="checkbox" id="override_user_settings" name="override_user_settings" <?php echo $options[ 'override_user_settings' ] === true ? 'checked' : ''; ?> />
              Override individual user settings
            </label>
            </td>
          </tr>
          <tr valign="top">
            <th scope="row">
              <label for="target_word_count">Daily Word Count Target</label>
            </th>
            <td>
              <input type="text" id="target_word_count" name="target_word_count" value="<?php echo esc_attr( $options[ 'target_word_count' ] ); ?>" />
            </td>
          </tr>
          <tr valign="top">
            <th scope="row">
              <label>Log Writing Options</label>
            </th>
            <td>
              <fieldset>
                <label>
                  <input type="checkbox" id="allow_editing" name="allow_editing" <?php echo $options[ 'allow_editing' ] === true ? 'checked' : ''; ?> />
                  Allow editing
                </label>
                <br>
                <label>
                  <input type="checkbox" id="allow_multiple_posts_per_day" name="allow_multiple_posts_per_day" <?php echo $options[ 'allow_multiple_posts_per_day' ] === true ? 'checked' : ''; ?> />
                  Allow multiple Logs per day
                </label>
                <br>
                <label>
                  <input type="checkbox" id="allow_future_posts" name="allow_future_posts" <?php echo $options[ 'allow_future_posts' ] === true ? 'checked' : ''; ?> />
                  Allow writing Future Logs
                </label>
                <br>
                <label>
                  <input type="checkbox" id="allow_past_posts" name="allow_past_posts" <?php echo $options[ 'allow_past_posts' ] === true ? 'checked' : ''; ?> />
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
                  <input type="radio" name="post_visibility" value="public" checked="<?php echo $options[ 'post_visiblity' ] === 'public' ? 'checked' : ''; ?>">
                  <span>Public</span>
                </label>
                <br>
                <label>
                  <input type="radio" name="post_visibility" value="private" checked="<?php echo $options[ 'post_visiblity' ] === 'private' ? 'checked' : ''; ?>">
                  <span>Private</span>
                </label>
              </fieldset>
            </td>
          </tr>
          <tr valign="top">
            <th scope="row">
              <label>Defaults</label>
            </th>
            <td>
            <label>
              <input type="checkbox" id="reset_defaults" name="reset_defaults" />
              Reset plugin defaults
            </label>
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