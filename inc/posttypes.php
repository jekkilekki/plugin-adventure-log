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
      // 'taxonomies'         => array( 'post_tag' ),
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

  /**
   * Add additional CPTs and make them show up in ALog menu
   * @TODO https://gist.github.com/tommcfarlin/5459391
   * 
   * Add Quests CPT.
   * 
   * This will be displayed in our Adventure Log - as a submenu item.
   */
  $labels = array(
      'name'                  => _x( 'Quests', 'Post type general name', 'adventure-log' ),
      'singular_name'         => _x( 'Quest', 'Post type singular name', 'adventure-log' ),
      'menu_name'             => _x( 'Quests', 'Admin Menu text', 'adventure-log' ),
      'name_admin_bar'        => _x( 'Quest', 'Add New on Toolbar', 'adventure-log' ),
      'add_new'               => __( 'Add New', 'adventure-log' ),
      'add_new_item'          => __( 'Add New Quest', 'adventure-log' ),
      'new_item'              => __( 'New Quest', 'adventure-log' ),
      'edit_item'             => __( 'Edit Quest', 'adventure-log' ),
      'view_item'             => __( 'View Quest', 'adventure-log' ),
      'all_items'             => __( 'My Quests', 'adventure-log' ),
      'search_items'          => __( 'Search Quests', 'adventure-log' ),
      'parent_item_colon'     => __( 'Parent Quests:', 'adventure-log' ),
      'not_found'             => __( 'No Quests found.', 'adventure-log' ),
      'not_found_in_trash'    => __( 'No Quests found in Trash.', 'adventure-log' ),
      'featured_image'        => _x( 'Quest Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'adventure-log' ),
      'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'adventure-log' ),
      'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'adventure-log' ),
      'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'adventure-log' ),
      'archives'              => _x( 'Quest archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'adventure-log' ),
      'insert_into_item'      => _x( 'Insert into Quest', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'adventure-log' ),
      'uploaded_to_this_item' => _x( 'Uploaded to this Quest', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'adventure-log' ),
      'filter_items_list'     => _x( 'Filter Quests list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'adventure-log' ),
      'items_list_navigation' => _x( 'Quests list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'adventure-log' ),
      'items_list'            => _x( 'Quests list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'adventure-log' ),
  );

  $args = array(
      'labels'             => $labels,
      'public'             => true,
      'publicly_queryable' => true,
      'show_ui'            => true,
      'show_in_menu'       => 'edit.php?post_type=alog',
      'query_var'          => true,
      'rewrite'            => array( 'slug' => 'quests' ),
      'capability_type'    => 'alog',
      'has_archive'        => true,
      // 'taxonomies'         => array( 'post_tag' ),
      'hierarchical'       => false,
      'show_in_rest'       => true,
      'rest_base'          => 'alog',
      'menu_position'      => null,
      'menu_icon'          => 'dashicons-clipboard',
      'supports'           => array( 'title', 'editor', 'thumbnail',  ),
      'delete_with_user'   => true,
      'map_meta_cap'       => true
  );

}
add_action( 'init', 'adventure_log_cpt_init' );

/**
 * Create a Tags taxonomy for the post type "Adventure Log".
 *
 * @see register_post_type() for registering custom post types.
 * @link https://developer.wordpress.org/reference/functions/register_taxonomy/
 */
function alog_create_tag_taxonomy() {
  // Add new taxonomy, NOT hierarchical (like tags)
  $labels = array(
    'name'                       => _x( 'Log Tags', 'taxonomy general name', 'adventure-log' ),
    'singular_name'              => _x( 'Log Tag', 'taxonomy singular name', 'adventure-log' ),
    'search_items'               => __( 'Search Log Tags', 'adventure-log' ),
    'popular_items'              => __( 'Popular Log Tags', 'adventure-log' ),
    'all_items'                  => __( 'All Log Tags', 'adventure-log' ),
    'parent_item'                => null,
    'parent_item_colon'          => null,
    'edit_item'                  => __( 'Edit Log Tag', 'adventure-log' ),
    'update_item'                => __( 'Update Log Tag', 'adventure-log' ),
    'add_new_item'               => __( 'Add New Log Tag', 'adventure-log' ),
    'new_item_name'              => __( 'New Log Tag Name', 'adventure-log' ),
    'separate_items_with_commas' => __( 'Separate log tags with commas', 'adventure-log' ),
    'add_or_remove_items'        => __( 'Add or remove log tags', 'adventure-log' ),
    'choose_from_most_used'      => __( 'Choose from the most used log tags', 'adventure-log' ),
    'not_found'                  => __( 'No log tags found.', 'adventure-log' ),
    'menu_name'                  => __( 'Log Tags', 'adventure-log' ),
  );

  $args = array(
    'hierarchical'          => false,
    'labels'                => $labels,
    'show_ui'               => true,
    'show_admin_column'     => true,
    'update_count_callback' => '_update_post_term_count',
    'query_var'             => true,
    'rewrite'               => array( 'slug' => 'alog/tag' ),
    'show_in_rest'          => true,
  	'rest_base'             => 'log_tag',
  	'rest_controller_class' => 'WP_REST_Terms_Controller',
  );

  register_taxonomy( 'tag', 'alog', $args );
}
add_action( 'init', 'alog_create_tag_taxonomy', 0 );

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
  $rules = adventure_log_generate_date_archives( 'alog', $wp_rewrite );
  $wp_rewrite->rules = $rules + $wp_rewrite->rules;
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
    $slug_archive = $post_type->name;
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

  foreach ( $dates as $data ) {
    $query = 'index.php?post_type=' . $cpt;
    $rule = $slug_archive . '/' . $data[ 'rule' ];

    $i = 1;
    foreach ( $data[ 'vars' ] as $var ) {
      $query .= '&' . $var . '=' . $wp_rewrite->preg_index($i);
      $i++;
    }

    $rules[ $rule . "/?$" ] = $query;
    $rules[ $rule . "/feed/(feed|rdf|rss|rss2|atom)/?$" ] = $query . "&feed=" . $wp_rewrite->preg_index($i);
    $rules[ $rule . "/(feed|rdf|rss|rss2|atom)/?$" ] = $query . "&feed=" . $wp_rewrite->preg_index($i);
    $rules[ $rule . "/page/([0-9]{1,})/?$" ] = $query . "&paged=" . $wp_rewrite->preg_index($i);
  }

  return $rules;
}

/**
 * Add a new Single Post view to our CPT
 */
// add_filter( 'template_include', 'alog_single_template', 1 );
// function alog_single_template( $template_path ) {
//   if ( 'alog' == get_post_type() ) {
//     if ( is_single() ) {
//       // checks the theme first, otherwise adds a content filter
//       if ( $theme_file = locate_template( array( 'single-alog.php' ) ) ) {
//         $template_path = $theme_file;
//       } else {
//         add_filter( 'the_content', 'alog_display_single', 20 );
//       }
//     }
//   }
//   return $template_path;
// }


function alog_display_single( $content ) {
  if ( ! empty( get_the_ID() ) ) {
    // $content = alog_get_calendar( array( 'alog' ) );

    $content = get_the_content( get_the_ID() );
    // $content .= '</div>';
    // alog_post_edit();
  }
  return $content;
}