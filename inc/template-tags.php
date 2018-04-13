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
 * Creates the new Log writing area
 */
function alog_new_log_section() { 
  ?>
    <p class="alog-log-caption"><?php _e( 'New Log', 'adventure-log' ); ?></p>
    <!-- <input class="alog-image-input" type="text" placeholder="Featured Image" />
    <input type="submit" value="Upload..." /> -->

    <h1 class="alog-entry-title entry-title alog-entry-editable" contenteditable="true"><?php echo get_url_date_string(); ?></h1>
    <div class="alog-entry-content entry-content alog-entry-editable" contenteditable="true"></div>

    <footer class="alog-entry-footer entry-footer">
      <input class="alog-tag-input" type="text" placeholder="<?php _e( 'Tag it &amp; bag it', 'adventure-log' ); ?>" />
      <span class="edit-link">
        <a class="post-edit-link add-log-button"><?php _e( 'Save', 'adventure-log' ); ?></a>
      </span>
    </footer>

    <div class="alog-stats alog-overlay-bottom">
      <p class="alog-stats-wordcount">Current post word count: <span class="alog-wc-number">12</span> words</p>
    </div>
  <?php
}