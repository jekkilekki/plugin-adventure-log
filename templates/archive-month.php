<?php
/**
 * The template for displaying MONTHLY archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @since 1.0.0
 * @version 1.0.0
 */

// Get our date variables for the rest of the page
$today = get_wp_current_date(); 
$date = get_url_date_array();

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
        
      <h1 class="page-title">MONTHY | 
        <a href="<?php echo esc_url( home_url() . '/alog/' ); ?>">Home</a> | 
        <a href="<?php echo esc_url( home_url() . '/alog/' . $date['year'] . '/' . $date['monnum'] ); ?>"><?php echo $date['month']; ?></a>
        <a href="<?php echo esc_url( home_url() . '/alog/' . $date['year'] ); ?>"><?php echo $date['year']; ?></a>
      </h1>
      <div class="taxonomy-description"><?php _e( 'Keep track of your writing this month. What kind of streak are you on?', 'adventure-log' ); ?></div>

      <ul class="yearly-date-boxes">
        <?php
          for ( $i = 1; $i <= 365; $i++ ) {

          }
        ?>
      </ul>

      <div>
        <i class="fa fa-edit"></i>
        <a href="<?php echo esc_url( home_url() . adventure_log_date_url( $today['year'], $today['monnum'], $today['day'] ) ); ?>">Write New Log</a>
      </div>

      <ul class="alog-date-boxes">

      <?php
        for( $i = 1; $i <= $date['days_this_month']; $i++ ) {

          $classname = 'alog-day';

          if ( is_today( $today, $date, $i ) ) {
            $classname .= ' alog-today';
          } 
          if ( $i == $date['day'] ) {
            $classname .= ' alog-current';
          } 
          if ( $i > $today['day'] ) {
            $classname .= ' alog-future';
          }
          ?>
            
            <a href="<?php echo esc_url( home_url() . adventure_log_date_url( $date['year'], $date['monnum'], $i ) ); ?>">
              <li class="<?php echo $classname; ?>">
                <span class="screen-reader-text"><?php echo get_url_date_string( $date['year'], $date['monnum'], $i ); ?></span><?php echo $i; ?></li>
            </a>

          <?php
        }
        echo '</ul>';
      ?>
      
		</header><!-- .page-header -->
	<?php // endif; ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

    <?php
    if ( have_posts() ) : 

			/* Start the Loop */
      while ( have_posts() ) : the_post();
      
        if ( is_day() ) {
          echo '<h3>Daily archive: ' . get_the_date( 'F j, Y') . '</h3>';
        } elseif ( is_month() ) {
          echo '<h3>Monthly archive: ' . get_the_date( 'F Y' ) . '</h3>';
        } elseif ( is_year() ) {
          echo '<h3>Yearly archive: ' . get_the_date( 'Y' ) . '</h3>';
        } else {
          echo '<h3>Looks like our function is wrong.</h3>';
        } 

				/*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */
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
	<?php get_sidebar(); ?>
</div><!-- .wrap -->

<?php get_footer();
