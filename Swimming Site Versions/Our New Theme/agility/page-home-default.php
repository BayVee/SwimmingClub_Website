<?php
/**
 * Template Name: Home
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

		<!-- Begin page-home-default.php 
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
						<?php agility_featured_items( $agilitySettings->op( 'home-featured-items' ) , 'query_id', false , $agilitySettings->op( 'home-featured-items-crop' ) ); ?>
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

		<!-- end page-home-default.php -->
		
<?php get_footer(); ?>