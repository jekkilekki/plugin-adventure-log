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

// Get today's date from the archive URL (allows navigation to ANY date)
$todays_date_url = $_SERVER[ 'REQUEST_URI' ];
$url_date = explode( "/", $todays_date_url );

// Retrieve from the end of the array, in order: day, month, year
// Day
array_pop( $url_date ); 
$url_day = end( $url_date );

// Month
array_pop( $url_date );
$url_monnum = end( $url_date );
$date_obj = DateTime::createFromFormat( '!m', $url_monnum );
$url_month = $date_obj->format( 'F' );

// Year
array_pop( $url_date );
$url_year = end( $url_date );

get_header(); ?>

<div class="wrap">

	<?php // if ( have_posts() ) : ?>
		<header class="page-header">

      <?php
        // Get all values for today's date and set variable
        // Grabs the current timestamp according to WordPress's set UTC offset
        $wp_timestamp = current_time( 'timestamp' ); 

        // Then, passes WordPress's UTC offset timestamp into PHP's date function
        $today = date( $wp_timestamp );
        $year = date( 'Y', $wp_timestamp );
        $monnum = date( 'n', $wp_timestamp );
        $month = date( 'F', $wp_timestamp );
        $day = date( 'j', $wp_timestamp );
        $days_this_month = date( 't' );
      ?>
        
      <h1 class="page-title">
        <a href="<?php echo esc_url( home_url() . '/alog/' . $year . '/' . $monnum ); ?>"><?php echo $month; ?></a>
        <a href="<?php echo esc_url( home_url() . '/alog/' . $year ); ?>"><?php echo $year; ?></a>
      </h1>
      <div class="taxonomy-description"><?php _e( 'Keep track of your writing this month. What kind of streak are you on?', 'adventure-log' ); ?></div>

      <ul class="yearly-date-boxes">
        <?php
          for ( $i = 1; $i <= 365; $i++ ) {

          }
        ?>
      </ul>

      <ul class="alog-date-boxes">

      <?php
        for( $i = 1; $i <= $days_this_month; $i++ ) {

          $classname = 'alog-day';

          if ( $year == $url_year && $monnum == $url_monnum && $i == $url_day ) {
            $classname .= ' alog-current';
          } elseif ( $i == $day ) {
            $classname .= ' alog-today';
          } elseif ( $i > $day ) {
            $classname .= ' alog-future';
          }

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

          if ( $query->have_posts ) : 
            $classname .= ' alog-complete';

            while ( $query->the_post() ) : $query->the_post();

            echo '<a href="' . esc_url( get_permalink( $post->ID ) ) . '"><li class="' . $classname . '"><span class="screen-reader-text">' . 
              $month . ' ' . $i . ', ' . $year .
              '</span>' . $i . '</li></a>';

            endwhile;

          else : 
          ?>

            <a href="<?php echo esc_url( home_url() . '/alog/' . $url_year . '/' . $url_monnum . '/' . $i . '/' ); ?>">
              <li class="<?php echo $classname; ?>">
                <span class="screen-reader-text"><?php echo $url_month . ' ' . $i . ', ' . $url_year; ?></span><?php echo $i; ?></li>
            </a>

          <?php
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
    // $args = array(
    //   'post_type'  => 'alog',
    //   'date_query' => array(
    //     array(
    //       'year'  => $year,
    //       'month' => $monnum,
    //       'day'   => $day,
    //     ),
    //   ),
    //   'ignore_sticky_posts' => 1,
    // );
    // $query = new WP_Query( $args );

    if ( have_posts() ) : ?>
    
      <div><a href="<?php echo esc_url( home_url() . '/alog/' . $year . '/' . $monnum . '/' . $day . '/' ); ?>">Create New</a></div>

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

    else : 
      if ( ! is_today() ) : ?>
        <div class="alog-entry-content entry-content">Sorry, you have no writing for this date.</div>
      
      <?php 
      else: ?>

        <h1 class="alog-entry-title entry-title" contenteditable="true">
          <?php echo $url_month . ' ' . $url_day . ', ' . $url_year; ?>
        </h1>
        <div class="alog-entry-content entry-content" contenteditable="true"></div>

        <footer class="entry-footer">
          <span class="edit-link">
            <a class="post-edit-link add-log-button">Save</a>
          </span>
        </footer>

    <?php
      endif; 
    endif; ?>
    
    <?php // wp_reset_postdata(); ?>

		</main><!-- #main -->
	</div><!-- #primary -->
	<?php get_sidebar(); ?>
</div><!-- .wrap -->

<?php get_footer();
