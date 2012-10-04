<?php
/**
 * Template Name: Left Sidebar Page
 *
 * @package Agility
 * @since Agility 1.0
 */

get_header(); 

$GLOBALS['sidebar_offset'] = '';
?>

		<!-- Begin page.php 
		============================================ -->
		
		<!-- #main-container container -->
		<div id="main-container" class="container">

			<?php get_sidebar(); ?>
			
			<!-- #primary .site-content -->
			<div id="primary" class="site-content eleven columns offset-by-one">
				
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