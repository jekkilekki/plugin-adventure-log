<?php
/**
 * Use the WP Settings API
 * 
 * @see https://developer.wordpress.org/reference/functions/register_setting/
 * @see https://developer.wordpress.org/reference/functions/add_settings_section/
 * @see https://developer.wordpress.org/reference/functions/add_settings_field/
 */
register_activation_hook( __FILE__, 'alog_settings_api_options' );

function alog_settings_api_options() {
  alog_get_options();
}

add_action( 'admin_init', 'alog_admin_init_settings_api' );
function alog_admin_init_settings_api() {  
  // Register a setting group with a validation function so that post data handling is done automatically for us
  register_setting( 'alog_settings',          // $option_group
                    'alog_options',           // $option_name - the options array in the site database
                    'alog_validate_options' );// $args = array()

  // Add a new settings section within the group
  add_settings_section( 'alog_main_section',  // $id
                        __( 'Main Settings', 'adventure-log' ),      // $title
                        'alog_main_setting_section_callback', // $callback
                        'alog_settings_api' );    // $page

            // Add each fields with its name and function to use for our new settings, and put them in our section
            // OVERRIDE USER SETTINGS
            add_settings_field( 'override_user_settings', // $id            
                        __( 'Override User Settings', 'adventure-log' ), // $title
                        'alog_checkbox', // $callback
                        'alog_settings_api', // $page
                        'alog_main_section', // $section
                        array( 'name' => 'override_user_settings' ) ); // $args = array()
            // ADVENTURER TYPE
            add_settings_field( 'adventurer_select_list',
                        __( 'Adventurer Type', 'adventure-log' ),
                        'alog_select_list',
                        'alog_settings_api',
                        'alog_main_section',
                        array( 'name' => 'adventurer_select_list',
                               'choices' => array( 'Adventurer', 'Ranger', 'Berserker', 'Assassin', 'Scout', 'Monk' ) ) );             
            // TARGET WORD COUNT
            add_settings_field( 'target_word_count', // $id            
                        __( 'Daily Target Word Count', 'adventure-log' ), // $title
                        'alog_text_field', // $callback
                        'alog_settings_api', // $page
                        'alog_main_section', // $section
                        array( 'name' => 'target_word_count' ) ); // $args = array()
            // ALLOW EDITING            
            add_settings_field( 'allow_editing', // $id            
                        __( 'Allow Editing', 'adventure-log' ), // $title
                        'alog_checkbox', // $callback
                        'alog_settings_api', // $page
                        'alog_main_section', // $section
                        array( 'name' => 'allow_editing' ) ); // $args = array()
            // ALLOW MULTIPLE
            add_settings_field( 'allow_multiple_posts_per_day', // $id            
                        __( 'Allow Multiple Posts per Day', 'adventure-log' ), // $title
                        'alog_checkbox', // $callback
                        'alog_settings_api', // $page
                        'alog_main_section', // $section
                        array( 'name' => 'allow_multiple_posts_per_day' ) ); // $args = array()
            // ALLOW FUTURE
            add_settings_field( 'allow_future_posts', // $id            
                        __( 'Allow Future Posts', 'adventure-log' ), // $title
                        'alog_checkbox', // $callback
                        'alog_settings_api', // $page
                        'alog_main_section', // $section
                        array( 'name' => 'allow_future_posts' ) ); // $args = array()
            // ALLOW PAST
            add_settings_field( 'allow_past_posts', // $id            
                        __( 'Allow Past Posts', 'adventure-log' ), // $title
                        'alog_checkbox', // $callback
                        'alog_settings_api', // $page
                        'alog_main_section', // $section
                        array( 'name' => 'allow_past_posts' ) ); // $args = array()
            // POST VISIBILITY
            add_settings_field( 'post_visibility', // $id            
                        __( 'Default Post Visibility', 'adventure-log' ), // $title
                        'alog_radio', // $callback
                        'alog_settings_api', // $page
                        'alog_main_section', // $section
                        array( 'name' => 'post_visibility' ) ); // $args = array()
            // BIO
            add_settings_field( 'adventurer_bio',
                        __( 'Adventurer Bio', 'adventure-log' ),
                        'alog_textarea',
                        'alog_settings_api',
                        'alog_main_section',
                        array( 'name' => 'adventurer_bio' ) );
            // RESET DEFAULTS
            add_settings_field( 'reset_defaults', // $id            
                        __( 'Reset Defaults', 'adventure-log' ), // $title
                        'alog_checkbox', // $callback
                        'alog_settings_api', // $page
                        'alog_main_section', // $section
                        array( 'name' => 'reset_defaults',
                               'description' => __( 'Only check this box if you want to reset everything to the defaults.', 'adventure-log' ) ) 
                        ); // $args = array()
}

function alog_validate_options( $input ) {
  // Text field validation
  foreach( array( 'target_word_count' ) as $option_name ) {
    if ( isset( $input[ $option_name ] ) ) {
      $input[ $option_name ] = sanitize_text_field( $input[ $option_name ] );
    }
  }

  // Checkbox validation
  foreach( array( 'override_user_settings', 'allow_editing', 'allow_multiple_posts_per_day', 'allow_future_posts', 'allow_past_posts', 'reset_defaults' ) as $option_name ) {
    if ( isset( $input[ $option_name ] ) ) {
      $input[ $option_name ] = true;
    } else {
      $input[ $option_name ] = false;
    }
  }

  // Radio validation
  $post_visibility = array( 'public', 'private' );
  if ( ! in_array( $input[ 'post_visibility'], $post_visibility ) ) {
    $input[ 'post_visibility' ] = 'private';
  }

  // Text area validation

  return $input;
}

function alog_main_setting_section_callback() {
  ?>
    <p>This is the main configuration section.</p>
  <?php
}

function alog_text_field( $data = array() ) {
  extract( $data );
  $options = alog_get_options();
  ?>
  <input type="text" name="alog_options[<?php echo $name; ?>]" value="<?php echo esc_html( $options[ $name ] ); ?>" />
  <br>
  <?php
}

function alog_checkbox( $data = array() ) {
  extract( $data );
  $options = alog_get_options();
  ?>
  <input type="checkbox" name="alog_options[<?php echo $name; ?>]" 
    <?php checked( $options[ $name ] ); ?> /><?php echo isset( $description ) ? $description : ''; ?>
  <?php
}

function alog_radio( $data = array() ) {
  extract( $data );
  $options = alog_get_options();
  ?>
  <input type="radio" name="alog_options[<?php echo $name; ?>]" <?php checked( $options[ $name ] ); ?> />
  <?php
}

function alog_select_list( $data = array() ) {
  extract( $data );
  $options = alog_get_options();
  ?>
  <select name="alog_options[<?php echo $name; ?>]">
    <?php foreach( $choices as $item ) : ?>
    <option value="<?php echo $item; ?>"
      <?php selected( $options[ $name ] == $item ); ?>><?php echo $item; ?>
    </option>
    <?php endforeach; ?>
  </select>
  <?php
}

function alog_textarea( $data = array() ) {
  extract( $data );
  $options = alog_get_options();
  ?>
  <textarea type="text" name="alog_options[<?php echo $name; ?>]" rows="5" cols="30">
    <?php echo esc_html( $options[ $name ] ); ?>
  </textarea>
  <?php
}

add_action( 'admin_menu', 'alog_settings_api_menu' );
function alog_settings_api_menu() {
  add_options_page( 'Adventure Log Settings API',
                __( 'Adventure Log Settings API', 'adventure-log' ),
                'manage_options',
                'alog_settings_api',
                'alog_build_settings_api_page' );
}

/**
 * Undocumented function
 * 
 * @see https://developer.wordpress.org/reference/functions/settings_fields/
 * @see https://developer.wordpress.org/reference/functions/do_settings_sections/
 * @return void
 */
function alog_build_settings_api_page() {
  ?>
  <div id="alog-general" class="wrap">
  <h2>Adventure Log Settings API</h2>

  <form name="alog_options_form_settings_api" method="post" action="options.php">
    
    <?php settings_fields( 'alog_settings' ); ?>
    <?php do_settings_sections( 'alog_settings_api' ); ?>

    <input type="submit" value="Save" class="button-primary" />
  </form>
  </div>
  <?php
}