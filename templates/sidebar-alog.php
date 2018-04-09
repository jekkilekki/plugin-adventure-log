<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */

// if ( ! is_active_sidebar( 'sidebar-alog' ) ) {
// 	return;
// }
?>

<aside id="secondary" class="widget-area" role="complementary" aria-label="<?php esc_attr_e( 'Adventure Log Sidebar', 'adventure-log' ); ?>">
	<?php dynamic_sidebar( 'sidebar-alog' ); ?>
</aside><!-- #secondary -->
