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

get_header(); ?>

<div class="wrap">

	<?php // if ( have_posts() ) : ?>
		<header class="page-header">

      <?php
      // Get all values for today's date and set variable
        $wp_timestamp = current_time( 'timestamp' );
        $today = date( $wp_timestamp );
        $year = date( 'Y', $wp_timestamp );
        $monnum = date( 'n', $wp_timestamp );
        $month = date( 'F', $wp_timestamp );
        $day = date( 'j', $wp_timestamp );
        $days_this_month = date( 't' );

        $todays_date_string = $month . ' ' . $day . ', ' . $year;
        
        echo '<h1 class="page-title">' . $month . ' ' . $year . '</h1>';
        echo '<div class="taxonomy-description">Keep track of your writing this month. What kind of streak are you on?</div>';

        echo '<ul class="alog-date-boxes">';
        for( $i = 1; $i <= $days_this_month; $i++ ) {

          $args = array(
            'post_type' => 'alog',
            'date_query' => array(
              'year'  => $year,
              'month' => $monnum,
              'day'   => $i,
            ),
            'ignore_sticky_posts' => 1
          );
  
          $query = new WP_Query( $args );

          if ( $query->have_posts ) : while ( $query->the_post() ) : $query->the_post();

            echo '<a href="' . esc_url( get_permalink( $post->ID ) ) . '"><li class="alog-day alog-complete"><span class="screen-reader-text">' . 
              $month . ' ' . $i . ', ' . $year .
              '</span>' . $i . '</li></a>';

            endwhile;

          else : 

            echo '<a href="#"><li class="alog-day"><span class="screen-reader-text">' . 
              $month . ' ' . $i . ', ' . $year .
              '</span>' . $i . '</li></a>';

          endif;

          wp_reset_postdata();

        }
        echo '</ul>';
      ?>
      
		</header><!-- .page-header -->
	<?php // endif; ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

    <?php
    $args = array(
      'post_type'  => 'alog',
      'date_query' => array(
        array(
          'year'  => $year,
          'month' => $monnum,
          'day'   => $day,
        ),
      ),
      'ignore_sticky_posts' => 1,
    );
    $query = new WP_Query( $args );

		if ( have_posts() ) : ?>
			<?php
			/* Start the Loop */
			while ( have_posts() ) : the_post();

				/*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */
        get_template_part( 'template-parts/post/content' );

        ?>
        
        <footer class="entry-footer">
        <span class="edit-link">
          <a class="post-edit-link add-log-button">Edit</a>
        </span>
      </footer>

      <?php
			endwhile;

			the_posts_pagination( array(
				'prev_text' => twentyseventeen_get_svg( array( 'icon' => 'arrow-left' ) ) . '<span class="screen-reader-text">' . __( 'Previous page', 'twentyseventeen' ) . '</span>',
				'next_text' => '<span class="screen-reader-text">' . __( 'Next page', 'twentyseventeen' ) . '</span>' . twentyseventeen_get_svg( array( 'icon' => 'arrow-right' ) ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'twentyseventeen' ) . ' </span>',
			) );

		else : ?>

			<h1 class="alog-entry-title entry-title" contenteditable="true"><?php echo $todays_date_string; ?></h1>
      <div class="alog-entry-content entry-content" contenteditable="true"></div>

      <footer class="entry-footer">
        <span class="edit-link">
          <a class="post-edit-link add-log-button">Save</a>
        </span>
      </footer>

    <?php
    endif; ?>
    
    <?php // wp_reset_postdata(); ?>

		</main><!-- #main -->
	</div><!-- #primary -->
	<?php get_sidebar(); ?>
</div><!-- .wrap -->

<?php get_footer();
