<?php
/**
 * Creates a login form
 */
function alog_get_login_form() {
  ?>
    <form id="alog-login" action="login" method="POST">
      <h1><?php _e( 'Adventure Log Login', 'adventure-log' ); ?></h1>
      <p class="message"></p>

      <label for="username"><?php _e( 'Username', 'adventure-log' ); ?></label>
      <input id="username" type="text" name="username">
      <br>
      <label for="password"><?php _e( 'Password', 'adventure-log' ); ?></label>
      <input id="password" type="password" name="password">
      <br>
      <a class="lost" href="<?php echo wp_lostpassword_url(); ?>"><?php _e( 'Lost your password?', 'adventure-log' ); ?></a>
      <input class="submit_button" type="submit" value="<?php _e( 'Login', 'adventure-log' ); ?>" name="submit">
      <a class="close" href=""><i class="ra ra-cancel"></i></a>

      <?php wp_nonce_field( 'ajax-login-nonce', 'security' ); ?>
    </form>
  <?php
}

/**
 * Adventure Log Menu Bar
 */
function alog_nav_header( $date, $today ) {
  ?>
  <div class="alog-nav-header">
    <h1 class="page-title">
      <a href="<?php echo esc_url( home_url() . '/alog/' ); ?>"><i class="ra ra-sword ra-lg"></i> Adventure Log</a> 
    </h1>

    <h1 class="page-title">
      <?php 
      if ( is_year() ) 
        echo $date['year'] . ' Archive';
      elseif ( is_month() )
        echo $date['month'] . ' ' . $date['year'] . ' Archive';
      elseif ( is_day() ) 
        echo $date['month'] . ' ' . $date['day'] . ', ' . $date['year'] . ' Archive';
      else 
        echo 'Archives';
      ?>
    </h1>
    
    <nav class="alog-nav-container">
      <ul class="alog-nav-menu">

      <?php if ( is_user_logged_in() ) : ?>

        <li>
          <a href="<?php echo esc_url( home_url() . adventure_log_date_url( $today['year'], $today['monnum'], $today['day'] ) ); ?>?new=true"><i class="ra ra-quill-ink"></i> <small class="screen-reader-text"><?php _e( 'Write New Log', 'adventure-log' ); ?></small></a>
        </li>
        <li>
          <a href="#"><i class="ra ra-cog"></i> <small class="screen-reader-text"><?php _e( 'Adventure Log Settings', 'adventure-log' ); ?></small></a>
        </li>
        <li>
          <a class="login_button alog-login-button" href="<?php echo esc_url( wp_logout_url( $_SERVER[ 'REQUEST_URI' ] ) ); ?>"><i class="ra ra-cancel"></i> <small class=""><?php _e( 'Logout', 'adventure-log' ); ?></small></a>
        </li>

      <?php else: ?>

        <li>
          <a class="login_button alog-login-button" id="alog_show_login" href="<?php // echo esc_url( wp_login_url() ); ?>"><i class="ra ra-key"></i> <small><?php _e( 'Login', 'adventure-log' ); ?></small></a>
        </li>

      <?php endif; ?>

      </ul>
    </nav>
  

  </div>

  <div class="taxonomy-description"><?php // _e( 'Keep track of your writing this month. What kind of streak are you on?', 'adventure-log' ); ?></div>

  <?php
}

/**
 * Word count and Image uploading HTML for Logs
 */
function alog_post_edit() {
  ?>
  <div class="alog-post-edit-meta">
    <small class="alog-log-caption"><?php _e( 'New Log', 'adventure-log' ); ?></small>

    <div class="post-thumbnail">
      <input id="alog-img-id" type="hidden" value="" />
      <img id="alog-img-preview" class="wp-post-image" />
    </div>
  </div>
  <?php
}

function alog_wp_editor() {
  $content = the_content();
  $editor_id = 'alog_editor';
  $settings = array(
    'wpautop' => true,
    'media_buttons' => true,
    'textarea_name' => $editor_id,
    'textarea_rows' => get_option( 'default_post_edit_rows', 10 ),
    'tabindex' => '',
    'editor_css' => '',
    'editor_class' => 'alog-entry-content entry-content alog-entry-editable',
    // 'teeny' => true,
    'dfw' => false,
    'tinymce' => array(
      // 'plugins' => 'wordcount',
        'toolbar1' => 'formatselect, bold, italic, forecolor, bullist, numlist, blockquote, alignleft, aligncenter, alignright, link, unlink, charmap',
    ),
    'quicktags' => false,
    'drag_drop_upload' => true,
    
  );
  wp_editor( $content, $editor_id, $settings );
}

function alog_tinymce_custom() {
  $html = '';

  // Editor settings
  $content = '';
  $editor_id = 'alog_tinymce_prime';
  $settings = array(
    'media_buttons' => false,
    'textarea_rows' => 1,
    'quicktags' => false,
    'tinymce' => array(
      'toolbar1' => 'bold, italic, undo, redo',
      'statusbar' => false,
      'resize' => 'both',
      'paste_as_text' => true
    )
  );

  // Grab content to put inside a variable
  // ob_start();

  // Create editor
  wp_editor( $content, $editor_id, $settings );

  // IMPORTANT: Add required scripts, styles, and wp_editor config
  // _WP_Editors::enqueue_scripts();
  // _WP_Editors::editor_js();
  // print_footer_scripts();

  // $html .= ob_get_contents();

  // ob_end_clean();

  // Send everything to JS function
  // wp_send_json_success( $html );
  // wp_die();
}
// add_action( 'wp_ajax_insert_wp_editor_callback', 'alog_tinymce_prime' );
// add_action( 'wp_ajax_nopriv_insert_wp_editor_callback', 'alog_tinymce_prime' );

/**
 * Creates the new Log writing area
 */
function alog_new_log_section() { 

  if ( is_user_logged_in() ) : ?>
    
    <article class="alog hentry">
      <input id="alog-post-id" type="hidden" value="">
      <?php alog_post_edit(); ?>

      <header class="entry-header">
        <h1 class="alog-entry-title entry-title alog-entry-editable" contenteditable="true"><?php echo get_url_date_string(); ?></h1>
        <div class="alog-feature-img-buttons">
          <input id="alog-image-select" class="button" type="button" value="<?php _e( 'Featured Image...', 'adventure-log' ); ?>" />
          <a id="alog-image-remove" href="" title="<?php _e( 'Remove Featured Image', 'adventure-log' ); ?>"><i class="ra ra-cancel"></i></a>
        </div>
      </header>
    
      <!-- <div id="alog-tinymce-prime" class="alog-entry-content entry-content alog-entry-editable" contenteditable="true"></div> -->

      <?php alog_wp_editor(); ?>
      <!-- <hr> -->
      <?php //alog_tinymce_custom(); ?>

      <footer class="alog-entry-footer entry-footer">
        <input class="alog-tag-input alog-post-edit-meta" type="text" placeholder="<?php _e( 'Tag it &amp; bag it', 'adventure-log' ); ?>" />
        <span class="edit-link">
          <a class="post-edit-link add-log-button"><?php _e( 'Save', 'adventure-log' ); ?></a>
        </span>
      </footer>

      <div class="alog-stats alog-stats-overlay">
        <small class="alog-stats-wordcount">Current post word count: <span class="alog-wc-number">12</span> words</small>
      </div>
    </article>

  <?php else : ?>

  <?php endif;

}