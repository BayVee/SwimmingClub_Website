<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package Agility
 * @since Agility 1.0
 */

get_header(); ?>

	<!-- Begin 404.php 
		============================================ -->
		
		<!-- #main-container container -->
		<div id="main-container" class="container">
			
			<!-- #primary .site-content -->
			<div id="primary" class="site-content eleven columns">
				
				<div id="content" role="main">

					<article id="post-0" class="post error404 not-found">
						<header class="entry-header">
							<h1 class="page-title"><?php _e( 'These are not the pages you are looking for.', 'agility' ); ?></h2>
						</header>

						<div class="entry-content">
							<div class="tagline"><?php _e( 'How embarrassing, something got mucked up.', 'agility' ); ?></div>
							
							<p><?php _e( 'Why not try a search, or browse a selection from the blog?', 'agility' ); ?></p>

							<?php get_search_form(); ?>

							<h4><?php _e( 'Most Recent Posts', 'agility' ); ?></h4>
							<?php agility_latest_bloglist(); ?>

							<h4 class="widgettitle"><?php _e( 'Most Used Categories', 'agility' ); ?></h4>
								<ul>
								<?php wp_list_categories( array( 'orderby' => 'count', 'order' => 'DESC', 'show_count' => 1, 'title_li' => '', 'number' => 10 ) ); ?>
								</ul>

							<?php
							/* translators: %1$s: smilie */
							$archive_content = '<p>' . sprintf( __( 'Try looking in the monthly archives. %1$s', 'agility' ), convert_smilies( ':)' ) ) . '</p>';
							the_widget( 'WP_Widget_Archives', 'dropdown=1', array( 'after_title' => "</h4>$archive_content", 'before_title' => '<h4>' ) );
							?>

							<?php the_widget( 'WP_Widget_Tag_Cloud' , array(), array( 'after_title' => "</h4>", 'before_title' => '<h4>' )); ?>

						</div><!-- .entry-content -->
					</article><!-- #post-0 -->
				</div>
				<!-- end #content -->
			</div>
			<!-- end #primary .site-content -->
			
			<?php get_sidebar(); ?>

		</div>
		<!-- end #main-container .container -->

		<!-- end 404.php -->
		
<?php get_footer(); ?>