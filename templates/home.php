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

// echo '<pre>';
// var_dump( $today );
// echo '</pre>';

// echo '<pre>';
// var_dump( $date );
// echo '</pre>';

// Get timestamps for our dates and times to compare later
$todays_timestamp = current_time( 'timestamp' );
$urls_timestamp = get_url_timestamp();

// if ( $date == null ) {
//   $date = $today;
// }

get_header(); ?>

<div class="wrap">
		<header class="page-header alog-header">
      
      <div class="alog-nav-header">
        <h1 class="page-title"><i class="ra ra-sword ra-lg"></i>
          <a href="<?php echo esc_url( home_url() . '/alog/' ); ?>">Adventure Log</a> 
        </h1>

        
        <nav class="alog-nav-container">
          <ul class="alog-nav">
          <?php if ( is_user_logged_in() ) : ?>
            <li>
              <a href="#"><i class="ra ra-cog"></i> <small class="screen-reader-text"><?php _e( 'Adventure Log Settings', 'adventure-log' ); ?></small></a>
            </li>
            <li>
              <a href="<?php echo esc_url( wp_logout_url( home_url() . '/alog/' ) ); ?>"><i class="ra ra-cancel"></i> <small class=""><?php _e( 'Sign out', 'adventure-log' ); ?></small></a>
            </li>
          <?php else: ?>
            <li>
              <a href="<?php echo esc_url( wp_login_url() ); ?>"><i class="ra ra-key"></i> <small>Sign in</small></a>
            </li>
          <?php endif; ?>
          </ul>
        </nav>
      

      </div>

      <div class="taxonomy-description"><?php _e( 'Keep track of your writing this month. What kind of streak are you on?', 'adventure-log' ); ?></div>

      <?php alog_get_calendar( array( 'alog' ) ); ?>

      <?php if ( is_user_logged_in() ): ?>
        <div class="button post-edit-link">
          <i class="ra ra-quill-ink"></i>
          <a href="<?php echo esc_url( home_url() . adventure_log_date_url( $today['year'], $today['monnum'], $today['day'] ) ); ?>">Write New Log</a>
        </div>  
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
        echo "<hr>";
        get_template_part( 'template-parts/post/content' );
        ?>
        
        <!-- <footer class="entry-footer">
        <span class="edit-link">
          <a class="post-edit-link add-log-button">Edit</a>
        </span>
      </footer> -->

      <?php
			endwhile;

			the_posts_pagination( array(
				'prev_text' => twentyseventeen_get_svg( array( 'icon' => 'arrow-left' ) ) . '<span class="screen-reader-text">' . __( 'Previous page', 'twentyseventeen' ) . '</span>',
				'next_text' => '<span class="screen-reader-text">' . __( 'Next page', 'twentyseventeen' ) . '</span>' . twentyseventeen_get_svg( array( 'icon' => 'arrow-right' ) ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'twentyseventeen' ) . ' </span>',
			) );

    else : 

      // echo "Today's timestamp: " . $todays_timestamp;
      // echo "<br>URL's timestamp: " . $urls_timestamp;

      if ( ! is_today( $today, $date ) ) : ?>
        <h1 class="alog-entry-title entry-title"><?php echo get_url_date_string(); ?></h1>
        <div class="alog-entry-content entry-content">Sorry, you have no writing for this date.</div>
      
      <?php 
      else: ?>

        <h1 class="alog-entry-title entry-title alog-entry-editable" contenteditable="true"><?php echo get_url_date_string(); ?></h1>
        <div class="alog-entry-content entry-content alog-entry-editable" contenteditable="true"></div>

        <footer class="entry-footer">
          <span class="edit-link">
            <a class="post-edit-link add-log-button">Save</a>
          </span>
        </footer>

      <?php
      endif; 

    endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->
  <?php // get_sidebar( 'alog' ); ?>
  <?php load_template( dirname( __FILE__ ) . '/sidebar-alog.php' ); ?>
</div><!-- .wrap -->

<?php get_footer();
