<?php
/**
 * Create a custom archive page for Adventure Logs
 * @see https://codex.wordpress.org/Plugin_API/Filter_Reference/archive_template
 */
function adventure_log_archive_page( $template ) {
  global $post;

  if ( is_post_type_archive( 'alog' ) ) {
    $template = dirname( __FILE__ ) . '/templates/archive-alog.php';
  }
  return $template;
}
add_filter( 'archive_template', 'adventure_log_archive_page' );







// /**
//  * More links to look at
//  * @see https://wordpress.stackexchange.com/questions/55763/is-it-possible-to-define-a-template-for-a-custom-post-type-within-a-plugin-indep
//  * @see https://www.wphub.com/blog/posts/dream-plugin-custom-post-type-template-builder/
//  */




// /**
//  * This one WORKS on single template pages, but BREAKS on the archives
//  * @see https://code.tutsplus.com/articles/plugin-templating-within-wordpress--wp-31088
//  */
// // function alog_template_chooser( $template ) {
// //   $post_id = get_the_ID();

// //   // For all other Post Types
// //   if ( get_post_type( $post_id ) != 'alog' ) {
// //     return $template;
// //   }

// //   // Else use custom template
// //   if ( is_single() ) {
// //     return alog_get_template_hierarchy( 'single' );
// //   }
// // }
// // add_filter( 'template_include', 'alog_template_chooser' );

// // function alog_get_template_hierarchy( $template ) {
// //   // Get the template slug
// //   $template_slug = rtrim( $template, '.php' );
// //   $template = $template_slug . '.php';

// //   // Check if a custom template exists in the theme folder
// //   // if not, load the plugin template
// //   if ( $theme_file = locate_template( array( 'plugin_templates/' . $template ) ) ) {
// //     $file = $theme_file;
// //   } else {
// //     $file = ALOG_BASE_DIR . '/templates/' . $template;
// //   }

// //   return apply_filters( 'alog_repl_template_' . $template, $file );
// // }


// /**
//  * @see http://pateason.com/including-single-archive-templates-custom-post-type-wordpress-plugins/
//  */
// // Route single- template
// function alog_single_template( $single_template ) {
//   global $post;
//   $found = locate_template( 'single-alog.php' );
//   if ( $post->post_type == 'alog' && $found != '' ) {
//     $single_template = plugin_dir_path( __FILE__ ) . '/templates/single-alog.php';
//   }
//   return $single_template;
// }
// add_filter( 'single_template', 'alog_single_template' );

// // Route archive- template
// function alog_archive_template( $archive_template ) {
//   if ( is_post_type_archive( 'alog' ) ) {
//     $theme_files = array( 'archive-alog.php' );
//     $exists_in_theme = locate_template( $theme_files, false );
//     if ( $exists_in_theme == '' ) {
//       return plugin_dir_path( __FILE__ ) . '/templates/archive-alog.php'; 
//     }
//   }
//   return $archive_template;
// }













// echo 'Template functions baby!';
// /**
//  * Locate a template file for this plugin.
//  * 
//  * Locate the called template.
//  * Search order:
//  * 1. /themes/theme/templates/$template_name
//  * 2. /themes/theme/$template_name 
//  * 3. /plugins/plugin/templates/$template_name 
//  * 
//  * @since 1.0.0
//  * @see https://benmarshall.me/add-wordpress-plugin-template-files/
//  * 
//  * @param string $template_name Template to load.
//  * @param string $template_path Path to templates.
//  * @param string $default_path  Default path to template files.
//  * @return string               Path to the template file.
//  */
// function alog_locate_template( $template_name, $template_path = '', $default_path = '' ) {

//   // Set variable to search in the templates folder of the theme.
//   if ( ! $template_path ) {
//     $template_path = 'templates/';
//   }

//   // Set default plugin templates path.
//   if ( ! $default_path ) {
//     $default_path = plugin_dir_path( __FILE__ ) . 'templates/'; // Path to the plugin template folder
//   }

//   // Search template file in theme folder.
//   $template = locate_template( array(
//     $template_path . $template_name,
//     $template_name
//   ) );

//   // Get plugins template file.
//   if ( ! $template ) {
//     $template = $default_path . $template_name;
//   }

//   return apply_filters( 'alog_locate_template', $template, $template_name, $template_path, $default_path );
// }

// /**
//  * Get the plugin template.
//  * 
//  * Search for the template and include the file.
//  * 
//  * @since 1.0.0
//  * @see alog_locate_template()
//  * 
//  * @param string $template_name Template to load.
//  * @param array  $args          Args passed for the template file.
//  * @param string $template_path Path to templates.
//  * @param string $default_path  Default path to template files.
//  */
// function alog_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {

//   if ( is_array( $args ) && isset( $args ) ) {
//     extract( $args );
//   }

//   $template_file = alog_locate_template( $template_name, $template_path, $default_path );

//   if ( ! file_exists( $template_file ) ) {
//     _doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $template_file ), '1.0.0' );
//     return;
//   }

//   include $template_file;
// }

// /**
//  * Shortcode loader.
//  * 
//  * @since 1.0.0
//  * @see https://jeroensormani.com/how-to-add-template-files-in-your-plugin/
//  */
// function alog_template_shortcode() {
//   return alog_get_template( 'alog-single.php' );
// }
// add_shortcode( 'alog_single', 'alog_template_shortcode' );

// /**
//  * Plugin template loader.
//  * 
//  * The template loader will check if WP is loading a template
//  * for a specific Post Type and will try to load the template 
//  * from out of the 'templates' directory.
//  * 
//  * @since 1.0.0
//  * 
//  * @param string $template  Template file that is being loaded.
//  * @return string           Template file that should be loaded.
//  */
// function alog_template_loader( $template ) {

//   $find = array();
//   $file = '';

//   if ( is_singular( 'post' ) ) {
//     $file = 'alog-single.php';
//   } 

//   if ( file_exists( alog_locate_template( $file ) ) ) {
//     $template = alog_locate_template( $file );
//   }

//   return $template;
// }
// add_filter( 'template_include', 'alog_template_loader' );


// /**
//  * The below function will help to load template file from plugin directory of wordpress
//  *  Extracted from : http://wordpress.stackexchange.com/questions/94343/get-template-part-from-plugin
//  */ 
 
//  define('PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ));
//  function ccm_get_template_part($slug, $name = null) {
//   do_action("ccm_get_template_part_{$slug}", $slug, $name);
//   $templates = array();
//   if (isset($name))
//       $templates[] = "{$slug}-{$name}.php";
//   $templates[] = "{$slug}.php";
//   ccm_get_template_path($templates, true, false);
// }
// /* Extend locate_template from WP Core 
// * Define a location of your plugin file dir to a constant in this case = PLUGIN_DIR_PATH 
// * Note: PLUGIN_DIR_PATH - can be any folder/subdirectory within your plugin files 
// */ 
// function ccm_get_template_path($template_names, $load = false, $require_once = true ) {
//     $located = ''; 
//     foreach ( (array) $template_names as $template_name ) { 
//       if ( !$template_name ) 
//         continue; 
//       /* search file within the PLUGIN_DIR_PATH only */ 
//       // if ( file_exists(PLUGIN_DIR_PATH . $template_name)) { 
//         $located = PLUGIN_DIR_PATH . $template_name; 
//         // break; 
//       // } 
//     }
//     if ( $load && '' != $located )
//         load_template( $located, $require_once );
//     return $located;
// }