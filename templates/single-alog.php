<?php
/**
 * The template for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */
// Get our date variables for the rest of the page
$today = get_wp_current_date(); 
$date = get_url_date_array();
$options = alog_get_options();

if ( alog_is_home_url() && isset( $_GET['new'] ) && $_GET['new'] == true ) {
  wp_redirect( esc_url( adventure_log_date_url( $today['year'], $today['monnum'], $today['day'] ) ) );
  exit;
}

// Get timestamps for our dates and times to compare later
$todays_timestamp = current_time( 'timestamp' );
$urls_timestamp = get_url_timestamp();

get_header(); 
alog_get_login_form(); 
?>

<div class="wrap">
  <?php the_widget( 'Alog_Goals_List' ); ?>
		<header class="page-header alog-header">
      
      <?php alog_nav_header( $date, $today ); ?>  

      <?php 
      if ( is_year() ) {

      } elseif ( is_singular() || is_day() || is_month() || ( is_archive() && ! is_year() ) ) {
        alog_get_calendar( array( 'alog' ) ); 
      } else {
        echo 'No calendar here.';
      }
      ?>

      <?php if ( is_user_logged_in() ): ?>
        <!-- <div class="button post-edit-link">
          <i class="ra ra-quill-ink"></i>
          <a href="<?php // echo esc_url( home_url() . adventure_log_date_url( $today['year'], $today['monnum'], $today['day'] ) ); ?>">Write New Log</a>
        </div>   -->
      <?php endif; ?>   
      
		</header><!-- .page-header -->
  
  <?php // endif; ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

    <?php
    if ( have_posts() ) : 

			/* Start the Loop */
      while ( have_posts() ) : the_post();

				/*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */
        // $target_word_count = $options[ 'target_word_count' ];
        $target_word_count = 14; // test
        $post_word_count = alog_word_count_numeric();
        $classname = '';

        if ( $post_word_count > $target_word_count ) $classname = 'log-success';
        elseif ( $post_word_count > $target_word_count / 2 ) $classname = 'log-half';

        echo "<small class='$classname'>" . alog_word_count() . "</small>";
        // echo "<hr>";
        // get_template_part( 'template-parts/post/content' );
        alog_post_single();
        ?>
        
        <!-- <footer class="entry-footer">
        <span class="edit-link">
          <a class="post-edit-link add-log-button">Edit</a>
        </span>
      </footer> -->

        <?php
        // If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

				the_post_navigation( array(
					'prev_text' => '<span class="screen-reader-text">' . __( 'Previous Post', 'twentyseventeen' ) . '</span><span aria-hidden="true" class="nav-subtitle">' . __( 'Previous', 'twentyseventeen' ) . '</span> <span class="nav-title"><span class="nav-title-icon-wrapper">' . twentyseventeen_get_svg( array( 'icon' => 'arrow-left' ) ) . '</span>%title</span>',
					'next_text' => '<span class="screen-reader-text">' . __( 'Next Post', 'twentyseventeen' ) . '</span><span aria-hidden="true" class="nav-subtitle">' . __( 'Next', 'twentyseventeen' ) . '</span> <span class="nav-title">%title<span class="nav-title-icon-wrapper">' . twentyseventeen_get_svg( array( 'icon' => 'arrow-right' ) ) . '</span></span>',
				) );

			endwhile;

    endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->
  <?php // get_sidebar( 'alog' ); ?>
  <?php load_template( dirname( __FILE__ ) . '/sidebar-alog.php' ); ?>
</div><!-- .wrap -->

<?php get_footer();