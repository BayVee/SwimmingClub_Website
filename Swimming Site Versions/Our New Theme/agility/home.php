<?php
/**
 * The main Blog Post Loop template file.
 *
 * @package Agility
 * @since Agility 1.0
 */

get_header(); 
global $agilitySettings;
?>

		<!-- Begin home.php 
		============================================ -->
				
		<!-- #main-container container -->
		<div id="main-container" class="container blog-layout-<?php echo $agilitySettings->op( 'blog-layout' ); ?>">

		<?php 
		/* Uncomment to add a slider to the Blog Loop 
		<div class="row">
			<div class="sixteen columns">
				<?php 
					$slider_img_size = 'natural_940';
					if( $agilitySettings->op( 'home-slider-crop' ) ) $slider_img_size = 'crop_940';
					agility_slider( $agilitySettings->op( 'home-slider' ), 'slider_id', $slider_img_size ); 
				?>
			</div>
		</div>
		<hr class="mini" />
		*/ ?>

		<?php if( $agilitySettings->op( 'blog-layout' ) == 'grid' ): ?>

			<?php if( $agilitySettings->op( 'blog-display-title' ) ): ?>
			<h2 class="entry-title page-title sixteen columns"><?php echo $agilitySettings->op( 'blog-title' ); ?></h2>
			<?php endif; ?>

			<!-- #primary .site-content -->
			<div id="primary" class="site-content clearfix">
				
					
			<?php if ( have_posts() ) : ?>

				<!-- Blog Grid Layout -->		
				<div id="content" class="mosaic col-3 cf">

					<?php /* Start the Loop */ $post_index = 0; ?>
					<?php while ( have_posts() ) : the_post(); ?>

						<?php
							get_template_part( 'content', 'grid' );
							$post_index++;
						?>

					<?php endwhile; ?>

					<div class="sixteen columns">
						<?php agility_content_nav( 'nav-below' ); ?>
					</div>

				</div>		
				<!-- end #content.mosaic -->


				

			<?php elseif ( current_user_can( 'edit_posts' ) ) : ?>

				<article id="post-0" class="post no-results not-found">
					<header class="entry-header">
						<h1 class="entry-title"><?php _e( 'No posts to display', 'agility' ); ?></h1>
					</header><!-- .entry-header -->

					<div class="entry-content">
						<p><?php printf( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'agility' ), admin_url( 'post-new.php' ) ); ?></p>
					</div><!-- .entry-content -->
				</article><!-- #post-0 -->

			<?php endif; ?>
				

			</div>
			<!-- end #primary .site-content -->
		<?php else: ?>
			
			<!-- #primary .site-content -->
			<div id="primary" class="site-content eleven columns">
				
				<div id="content" role="main" class="blog-layout">
	
				<?php if( $agilitySettings->op( 'blog-display-title' ) ): ?>
					<h2 class="page-title"><?php echo $agilitySettings->op( 'blog-title' ); ?></h2>
				<?php endif; ?>

				<?php if ( have_posts() ) : ?>
	
					<?php agility_content_nav( 'nav-above' ); ?>
	
					<?php /* Start the Loop */ $post_index = 0; ?>
					<?php while ( have_posts() ) : the_post(); ?>
	
						<?php
							/* Include the Post-Format-specific template for the content.
							 * If you want to overload this in a child theme then include a file
							 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
							 */
							get_template_part( 'content', get_post_format() );
							$post_index++;
						?>
	
					<?php endwhile; ?>
	
					<?php agility_content_nav( 'nav-below' ); ?>
					
	
				<?php elseif ( current_user_can( 'edit_posts' ) ) : ?>
	
					<article id="post-0" class="post no-results not-found">
						<header class="entry-header">
							<h1 class="entry-title"><?php _e( 'No posts to display', 'agility' ); ?></h1>
						</header><!-- .entry-header -->
	
						<div class="entry-content">
							<p><?php printf( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'agility' ), admin_url( 'post-new.php' ) ); ?></p>
						</div><!-- .entry-content -->
					</article><!-- #post-0 -->
	
				<?php endif; ?>
	
				</div>
				<!-- end #content -->
			</div>
			<!-- end #primary .site-content -->
		
			<?php get_sidebar(); ?>

			<?php endif; ?>
		
		</div>
		<!-- end #main-container .container -->

		<!-- end home.php -->
		
<?php get_footer(); ?>
