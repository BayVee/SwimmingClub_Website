<?php
/**
 * The Template for displaying all single posts.
 *
 * @package Agility
 * @since Agility 1.0
 */

get_header(); ?>

		<!-- Begin single.php 
		============================================ -->
		
		<!-- #main-container container -->
		<div id="main-container" class="container">
			
			<!-- #primary .site-content -->
			<div id="primary" class="site-content eleven columns">
				
				<div id="content" role="main" class="">

				<?php while ( have_posts() ) : the_post(); ?>

					<?php if( $agilitySettings->op( 'blog-display-post-nav-above' ) ): ?>
					<?php agility_content_nav( 'nav-above' ); ?>
					<?php endif; ?>
	
					<?php get_template_part( 'content', 'single' ); ?>
	
					<?php agility_content_nav( 'nav-below' ); ?>

					<!-- Begin After Post sidebar/widget area -->
					<?php dynamic_sidebar( 'after-post' ); ?>
					<!-- end After Post sidebar/widget area -->
	
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
		
			<?php get_sidebar(); ?>
		
		</div>
		<!-- end #main-container .container -->

		<!-- end single.php -->
		
<?php get_footer(); ?>
