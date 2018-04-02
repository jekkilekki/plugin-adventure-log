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
      'rewrite'            => array( 'slug' => 'alog' ),
      'capability_type'    => 'alog',
      'has_archive'        => true,
      'taxonomies'         => array( 'post_tag' ),
      'hierarchical'       => false,
      'show_in_rest'       => true,
      'rest_base'          => 'alog',
      'menu_position'      => null,
      'menu_icon'          => 'dashicons-clipboard',
      'supports'           => array( 'title', 'editor', 'thumbnail',  ),
      'delete_with_user'   => true,
      'map_meta_cap'       => true
  );

  register_post_type( 'alog', $args );
}

add_action( 'init', 'adventure_log_cpt_init' );

/**
 * Flush rewrite rules on activation - to be able to access our CPT in REST, etc
 */
function adventure_log_flush_rewrite_init() {
  adventure_log_cpt_init();
  flush_rewrite_rules();
}

/**
 * Adventure Log specific rewrite rules
 * @see http://clubmate.fi/date-archives-for-wordpress-custom-post-types/
 * 
 * @return wp_rewrite Rewrite rules handled by WordPress
 */
function adventure_log_rewrite_rules( $wp_rewrite ) {
  // Hardcode in the CPT
  // $rules = adventure_log_generate_date_archives( 'alog', $wp_rewrite );
  // $wp_rewrite->rules = $rules + $wp_rewrite->rules;
  return $wp_rewrite;
}
add_action( 'generate_rewrite_rules', 'adventure_log_rewrite_rules' );

/** @TODO work in progress
 * Generate date archive rewrite rules for a Adventure Log
 * 
 * @param string $posttype slug of the custom post type 
 * 
 * @return rules $rules returns a set of rewrite rules for WordPress to handle
 */
function adventure_log_generate_date_archives( $cpt, $wp_rewrite ) {
  $rules = array();

  $post_type = get_post_type_object( $cpt );
  $slug_archive = $post_type->has_archive;

  if ( $slug_archive === false ) {
    return $rules;
  }

  if ( $slug_archive === true ) {
    // Get custom slug from post type object if specified
    $slug_archive = $post_type->rewrite[ 'slug' ];
  }

  $dates = array(
    array(
      'rule'  => "([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})",
      'vars'  => array( 'year', 'monthnum', 'day' )
    ),
    array(
      'rule'  => "([0-9]{4})/([0-9]{1,2})",
      'vars'  => array( 'year', 'monthnum' )
    ),
    array(
      'rule'  => "([0-9]{4})",
      'vars'  => array( 'year' )
    )
  );

  foreach ( $dates as $date ) {
    $query = 'index.php?post_type=' . $cpt;
    $rule = $slug_archive . '/' . $date[ 'rule' ];

    $i = 1;
    foreach ( $date[ 'vars' ] as $var ) {
      $query .= '&' . $var . '=' . $wp_rewrite->preg_index($i);
      $i++;
    

    $rules[ $rule . "/?$" ] = $query;
    $rules[ $rule . "/feed/(feed|rdf|rss|rss2|atom)/?$" ] = $query . "&feed=" . $wp_rewrite->preg_index($i);
    $rules[ $rule . "/(feed|rdf|rss|rss2|atom)/?$" ] = $query . "&feed=" . $wp_rewrite->preg_index($i);
    $rules[ $rule . "/page/([0-9]{1,})/?$" ] = $query . "&paged=" . $wp_rewrite->preg_index($i);
  }}

  return $rules;
}