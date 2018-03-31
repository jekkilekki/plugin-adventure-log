<?php
/**
 * Register a custom post type called "A.Log".
 *
 * @see get_post_type_labels() for label keys.
 */
function adventure_log_cpt_init() {
  $labels = array(
      'name'                  => _x( 'Adventure Logs', 'Post type general name', 'adventure-log' ),
      'singular_name'         => _x( 'Log', 'Post type singular name', 'adventure-log' ),
      'menu_name'             => _x( 'Adventure Logs', 'Admin Menu text', 'adventure-log' ),
      'name_admin_bar'        => _x( 'Adventure Log', 'Add New on Toolbar', 'adventure-log' ),
      'add_new'               => __( 'Add New', 'adventure-log' ),
      'add_new_item'          => __( 'Add New Log', 'adventure-log' ),
      'new_item'              => __( 'New Log', 'adventure-log' ),
      'edit_item'             => __( 'Edit Log', 'adventure-log' ),
      'view_item'             => __( 'View Log', 'adventure-log' ),
      'all_items'             => __( 'My Adventure Logs', 'adventure-log' ),
      'search_items'          => __( 'Search Logs', 'adventure-log' ),
      'parent_item_colon'     => __( 'Parent Logs:', 'adventure-log' ),
      'not_found'             => __( 'No Logs found.', 'adventure-log' ),
      'not_found_in_trash'    => __( 'No Logs found in Trash.', 'adventure-log' ),
      'featured_image'        => _x( 'Log Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'adventure-log' ),
      'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'adventure-log' ),
      'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'adventure-log' ),
      'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'adventure-log' ),
      'archives'              => _x( 'Log archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'adventure-log' ),
      'insert_into_item'      => _x( 'Insert into Log', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'adventure-log' ),
      'uploaded_to_this_item' => _x( 'Uploaded to this Log', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'adventure-log' ),
      'filter_items_list'     => _x( 'Filter Logs list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'adventure-log' ),
      'items_list_navigation' => _x( 'Logs list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'adventure-log' ),
      'items_list'            => _x( 'Logs list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'adventure-log' ),
  );

  $args = array(
      'labels'             => $labels,
      'public'             => true,
      'publicly_queryable' => true,
      'show_ui'            => true,
      'show_in_menu'       => true,
      'query_var'          => true,
      'rewrite'            => array( 'slug' => 'log' ),
      'capability_type'    => 'log',
      'has_archive'        => true,
      'hierarchical'       => false,
      'show_in_rest'       => true,
      'rest_base'          => 'logs',
      'menu_position'      => null,
      'menu_icon'          => 'dashicons-clipboard',
      'supports'           => array( 'title', 'editor' ),
      'map_meta_cap'       => true
  );

  register_post_type( 'log', $args );
}

add_action( 'init', 'adventure_log_cpt_init' );

/**
 * Flush rewrite rules on activation - to be able to access our CPT in REST, etc
 */
function adventure_log_flush_rewrite_init() {
  adventure_log_cpt_init();
  flush_rewrite_rules();
}