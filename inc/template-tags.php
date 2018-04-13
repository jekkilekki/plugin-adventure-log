<?php
/**
 * Creates a login form
 */
function alog_get_login_form() {
  ?>
    <form id="alog-login" action="login" method="POST">
      <h1><?php _e( 'Adventure Log Login', 'adventure-log' ); ?></h1>
      <p class="status"></p>

      <label for="username"><?php _e( 'Username', 'adventure-log' ); ?></label>
      <input id="username" type="text" name="username">

      <label for="password"><?php _e( 'Password', 'adventure-log' ); ?></label>
      <input id="password" type="password" name="password">

      <a class="lost" href="<?php echo wp_lostpassword_url(); ?>"><?php _e( 'Lost your password?', 'adventure-log' ); ?></a>
      <input class="submit_button" type="submit" value="<?php _e( 'Login', 'adventure-log' ); ?>" name="submit">
      <a class="close" href=""><?php _e( '(close)', 'adventure-log' ); ?></a>

      <?php wp_nonce_field( 'ajax-login-nonce', 'security' ); ?>
    </form>
  <?php
}