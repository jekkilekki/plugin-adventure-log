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

	<?php if ( have_posts() ) : ?>
		<header class="page-header">

			<?php
        $todays_date = getdate();
        $todays_date_string = $todays_date[ 'month' ] . ' ' . $todays_date[ 'mday' ] . ', ' . $todays_date[ 'year' ];
        
        echo '<h1 class="page-title">' . date( 'F Y' ) . '</h1>';
        echo '<div class="taxonomy-description">Keep track of your writing this month. What kind of streak are you on?</div>';
        
        $days_this_month = date( 't' );
        $this_month = $todays_date[ 'month' ];
        $this_year = $todays_date[ 'year' ];

        echo '<ul class="alog-date-boxes">';
        for( $i = 1; $i <= $days_this_month; $i++ ) {
          echo '<a href="#"><li class="alog-day"><span class="screen-reader-text">' . 
            $this_month . ' ' . $i . ', ' . $this_year .
            '</span>' . $i . '</li></a>';
        }
        echo '</ul>';
      ?>
      
		</header><!-- .page-header -->
	<?php endif; ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

    <?php
      $args = array(
        'date_query' => array(
          array(
            'year'  => $todays_date[ 'year' ],
            'month' => $todays_date[ 'mon' ],
            'day'   => $todays_date[ 'mday' ],
          ),
        ),
        'ignore_sticky_posts' => 1,
      );
      $custom_query = new WP_Query( $args );
    ?>

    <?php
		if ( $custom_query->have_posts() ) : ?>
			<?php
			/* Start the Loop */
			while ( $custom_query->have_posts() ) : $custom_query->the_post();

				/*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */
				get_template_part( 'template-parts/post/content', get_post_format() );

			endwhile;

			the_posts_pagination( array(
				'prev_text' => twentyseventeen_get_svg( array( 'icon' => 'arrow-left' ) ) . '<span class="screen-reader-text">' . __( 'Previous page', 'twentyseventeen' ) . '</span>',
				'next_text' => '<span class="screen-reader-text">' . __( 'Next page', 'twentyseventeen' ) . '</span>' . twentyseventeen_get_svg( array( 'icon' => 'arrow-right' ) ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'twentyseventeen' ) . ' </span>',
			) );

		else : ?>

			<h1 class="alog-entry-title entry-title" contenteditable="true"><?php echo $todays_date_string; ?></h1>
      <div class="alog-entry-content entry-content" contenteditable="true"></div>

    <?php
    endif; ?>
    
    <?php wp_reset_postdata(); ?>

		</main><!-- #main -->
	</div><!-- #primary -->
	<?php get_sidebar(); ?>
</div><!-- .wrap -->

<?php get_footer();
