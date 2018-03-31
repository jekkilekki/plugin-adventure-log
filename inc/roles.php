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
    $role->add_cap( 'edit_logs' );
    $role->add_cap( 'publish_logs' );
    $role->add_cap( 'edit_published_logs' );
  }

  $manager_roles = array( 'administrator', 'editor' );

  foreach( $manager_roles as $your_role ) {
    $role = get_role( $your_role );
    $role->add_cap( 'read_private_logs' );
    $role->add_cap( 'edit_others_logs' );
    $role->add_cap( 'edit_private_logs' );
    $role->add_cap( 'delete_logs' );
    $role->add_cap( 'delete_published_logs' );
    $role->add_cap( 'delete_private_logs' );
    $role->add_cap( 'delete_others_logs' );
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
    $role->remove_cap( 'edit_logs' );
    $role->remove_cap( 'publish_logs' );
    $role->remove_cap( 'edit_published_logs' );
    $role->remove_cap( 'read_private_logs' );
    $role->remove_cap( 'edit_others_logs' );
    $role->remove_cap( 'edit_private_logs' );
    $role->remove_cap( 'delete_logs' );
    $role->remove_cap( 'delete_published_logs' );
    $role->remove_cap( 'delete_private_logs' );
    $role->remove_cap( 'delete_others_logs' );
  }

}