<?php
/**
 * Template Name: Home + Blog (Grid)
 *
 * @package Agility
 * @since Agility 1.0
 */

get_header(); 
global $agilitySettings;

//Determine Slider Image Size
$slider_img_size = 'natural_940';
if( $agilitySettings->op( 'home-slider-crop' ) ) $slider_img_size = 'crop_940';

?>

		<!-- Begin page-home-blog.php 
		============================================ -->
		
		<!-- #main-container container -->
		<div id="main-container" class="container">
			
			<!-- #primary .site-content -->
			<div id="primary" class="site-content">

				<div id="content" >

					<div class="sixteen columns">
						<?php agility_slider( $agilitySettings->op( 'home-slider' ), 'slider_id', $slider_img_size ); ?>

						<?php 
							$tagline = $agilitySettings->op( 'home-tagline' );
							if( $tagline ): ?>
							<div class="tagline"><?php echo $tagline; ?>
								<?php if( $agilitySettings->op( 'home-tagline-fleuron' ) ): ?><span class="fleuron"></span><?php endif; ?>
							</div>
						<?php else: ?>
							<br/><br/><hr class="mini" /><br/>
						<?php endif; ?>

					</div>


					<?php if( $agilitySettings->op( 'home-show-featured-items' ) ): ?>
						<?php agility_featured_items( $agilitySettings->op( 'home-featured-items' ) ); ?>
					<?php endif; ?>

					<?php global $post;
						if( $post->post_content ): ?>

					<div role="main" class="sixteen columns">

					<?php while ( have_posts() ) : the_post(); ?>

						<?php //get_template_part( 'content', 'page' ); ?>
						<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
							<div class="entry-content">
								<?php the_content(); ?>
								<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'agility' ), 'after' => '</div>' ) ); ?>
								<?php edit_post_link( __( 'Edit', 'agility' ), '<span class="edit-link">', '</span>' ); ?>
							</div><!-- .entry-content -->
						</article><!-- #post-<?php the_ID(); ?> -->

					<?php endwhile; // end of the loop. ?>

					</div>
					<?php endif; ?>

					<?php 
					$paged_var = is_front_page() ? 'page' : 'paged';
					$blog_query = new WP_Query( array( 
						'post_type'	=>	'post',
						'paged' 	=> 	get_query_var( $paged_var )
					)); 
					
					if ( $blog_query->have_posts() ) : 
						global $wp_query;
						$temp_query = $wp_query;
						$wp_query = $blog_query;
						?>
						<div class="row">
							<hr class="sixteen columns">

							<?php if( $agilitySettings->op( 'blog-display-title' ) ): ?>
								<h5 class="entry-title page-title sixteen columns"><?php echo $agilitySettings->op( 'blog-title' ); ?></h5>
							<?php endif; ?>
						</div>

						<!-- Blog Grid Layout -->		
						<div id="blog-grid" class="mosaic col-3 cf clearfix blog-layout-grid">

							<?php /* Start the Loop */ $post_index = 0; ?>
							<?php while ( $blog_query->have_posts() ) : $blog_query->the_post(); ?>

								<?php
									/* Include the Post-Format-specific template for the content.
									 * If you want to overload this in a child theme then include a file
									 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
									 */
									get_template_part( 'content', 'grid' );
									$post_index++;
								?>

							<?php endwhile; ?>

							<div class="sixteen columns">
								<?php agility_content_nav( 'nav-below' , true ); ?>
							</div>

						</div>		
						<!-- end #blog-grid.mosaic -->
					<?php 
					$wp_query = $temp_query;
					endif;						
					wp_reset_query(); 
					?>
				

					<?php if( $agilitySettings->op( 'home-display-widgets' ) ): ?>
					<hr class="mini">
					<div class="row">
						<?php if( !dynamic_sidebar( 'home-template' ) ): ?>
						<div class="hint"><?php _e( 'Add widgets to the "Home Template - Lower Area".  Widgets will arrange in two columns.' , 'agility' ); ?></div>
						<?php endif; ?>
					</div>
					<hr class="mini">
					<?php endif; ?>

					<?php if( $agilitySettings->op( 'home-display-twitter' ) ): ?>
					<div  class="sixteen columns">
						<?php if( $twitterTitle = $agilitySettings->op( 'home-twitter-title' ) ): ?><h6><?php echo $twitterTitle; ?></h6><?php endif; ?>
						<div id="tweet" data-account="<?php echo $agilitySettings->op( 'twitter' ); ?>"><?php _e( 'Loading Tweets...', 'agility' ); ?></div>
					</div>
					<?php endif; ?>
				</div>
				<!-- end #content -->
			</div>
			<!-- end #primary .site-content -->
		</div>
		<!-- end #main-container .container -->

		<!-- end page-home-blog.php -->
		
<?php get_footer(); ?>