<?php
/**
 * Template Name: Full Width Page (No Sidebar)
 *
 * A full-width (no-sidebar) version of the page template.
 *
 * @package Agility
 * @since Agility 1.0
 */

get_header(); ?>

		<!-- Begin page.php 
		============================================ -->
		
		<!-- #main-container container -->
		<div id="main-container" class="container">
			
			<!-- #primary .site-content -->
			<div id="primary" class="site-content sixteen columns">
				
				<div id="content" role="main" class="">

				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'content', 'page' ); ?>

					<?php //comments_template( '', true ); ?>

				<?php endwhile; // end of the loop. ?>

				</div>
				<!-- end #content -->
			</div>
			<!-- end #primary .site-content -->
				
		</div>
		<!-- end #main-container .container -->

		<!-- end page.php -->
		
<?php get_footer(); ?>