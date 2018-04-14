<?php
/**
 * Register Adventurer role.
 */
function adventure_log_register_role() {
  add_role( 'adventurer', 'Adventurer' );
}

/**
 * Remove Adventurer role.
 */
function adventure_log_remove_role() {
  remove_role( 'adventurer' );
}

/**
 * Grant Adventure Log capabilities to Administrator, Editor, and Task Logger.
 */
function adventure_log_add_capabilities() {

  $roles = array( 'administrator', 'editor', 'adventurer' );

  foreach( $roles as $your_role ) {
    $role = get_role( $your_role );
    $role->add_cap( 'read' );
    $role->add_cap( 'edit_alogs' );
    $role->add_cap( 'publish_alogs' );
    $role->add_cap( 'edit_published_alogs' );
    $role->add_cap( 'upload_files' );
  }

  $manager_roles = array( 'administrator', 'editor' );

  foreach( $manager_roles as $your_role ) {
    $role = get_role( $your_role );
    $role->add_cap( 'read_private_alogs' );
    $role->add_cap( 'edit_others_alogs' );
    $role->add_cap( 'edit_private_alogs' );
    $role->add_cap( 'delete_alogs' );
    $role->add_cap( 'delete_published_alogs' );
    $role->add_cap( 'delete_private_alogs' );
    $role->add_cap( 'delete_others_alogs' );
  }

}

/**
 * Remove Adventure Log capabilities for Administrator, Editor, and Task Logger.
 */
function adventure_log_remove_capabilities() {

  $manager_roles = array( 'administrator', 'editor' );

  foreach( $manager_roles as $your_role ) {
    $role = get_role( $your_role );
    $role->remove_cap( 'read' );
    $role->remove_cap( 'edit_alogs' );
    $role->remove_cap( 'publish_alogs' );
    $role->remove_cap( 'edit_published_alogs' );
    $role->remove_cap( 'read_private_alogs' );
    $role->remove_cap( 'edit_others_alogs' );
    $role->remove_cap( 'edit_private_alogs' );
    $role->remove_cap( 'delete_alogs' );
    $role->remove_cap( 'delete_published_alogs' );
    $role->remove_cap( 'delete_private_alogs' );
    $role->remove_cap( 'delete_others_alogs' );
  }

}

/**
 * Remove Admin Bar from Adventurer's Frontend view
 */
add_action( 'after_setup_theme', 'alog_no_admin_bar' );
function alog_no_admin_bar() {
  $user = wp_get_current_user();
  if ( in_array( 'adventurer', (array) $user->roles ) ) {
    show_admin_bar( false );
  }
}

/**
 * Enable Image uploads from logged in users
 */
add_action( 'wp_enqueue_scripts', 'alog_enqueue_img_selection_script' );
function alog_enqueue_img_selection_script() {
  wp_enqueue_media();
  wp_enqueue_script( 'alog_upload_img_script', plugin_dir_url( __FILE__ ) . '../js/select.img.js', array( 'jquery' ), '20180414', true );
}

// Make sure users only see their own media
add_filter( 'ajax_query_attachments_args', 'alog_filter_user_media' );
function alog_filter_user_media( $query ) {
  // admins can see everything
  if ( ! current_user_can( 'manage_options' ) )
    $query['author'] = get_current_user_id();
  
  return $query;
}