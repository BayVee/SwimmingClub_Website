<?php
/**
 * The Template for displaying a singple Portfolio Item.
 *
 * @package Agility
 * @since Agility 1.0
 */

get_header(); ?>

		<!-- Begin single-portfolio-item.php 
		============================================ -->
		
		<!-- #main-container container -->
		<div id="main-container" class="container">
			
			<!-- #primary .site-content -->
			<div id="primary" class="site-content sixteen columns">
				
				<div id="content" role="main" class="">

				<?php while ( have_posts() ) : the_post(); ?>
		
					<?php get_template_part( 'content', 'portfolio-single' ); ?>
	
					<?php
						// If comments are open or we have at least one comment, load up the comment template
						if ( comments_open() || '0' != get_comments_number() )
							comments_template( '', true );
					?>
	
				<?php endwhile; // end of the loop. ?>

				</div>
				<!-- end #content -->
			</div>
			<!-- end #primary .site-content -->
		
		</div>
		<!-- end #main-container .container -->

		<!-- end single-portfolio-item.php -->
		
<?php get_footer(); ?>
