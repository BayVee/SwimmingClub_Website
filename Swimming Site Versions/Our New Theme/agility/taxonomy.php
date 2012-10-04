<?php
/**
 * The template for displaying Custom Taxonomy pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Agility
 * @since Agility 1.0
 */

get_header(); ?>

		<!-- Begin taxonomy.php 
		============================================ -->
		
		<!-- #main-container container -->
		<div id="main-container" class="container">
			
			<!-- #primary .site-content -->
			<div id="primary" class="site-content">
				
				<div id="content" role="main">

				<?php if ( have_posts() ) : ?>
					<?php
						global $wp_query;
						$term = $wp_query->get_queried_object();
						$class = '';
						$subtitle = false;
						if( isset( $term->description ) && '' != $term->description ){
							$subtitle = true;
							$class.= 'entry-title-with-sub';
						}
					?>

					<header class="page-header sixteen columns">
						<h1 class="entry-title page-title <?php echo $class; ?>"><?php echo $term->name; ?></h1>
						<h5 class="sub-page-title"><?php echo $term->description; ?></h5>
						<?php
							
							//ssd( $term );

							if ( is_category() ) {
								// show an optional category description
								$category_description = category_description();
								if ( ! empty( $category_description ) )
									echo apply_filters( 'category_archive_meta', '<div class="taxonomy-description">' . $category_description . '</div>' );

							} elseif ( is_tag() ) {
								// show an optional tag description
								$tag_description = tag_description();
								if ( ! empty( $tag_description ) )
									echo apply_filters( 'tag_archive_meta', '<div class="taxonomy-description">' . $tag_description . '</div>' );
							}
						?>
					</header>

					<?php rewind_posts(); ?>

					<?php agility_content_nav( 'nav-above' ); ?>
					
					<?php /* Start the Loop */ ?>

					<div class="portfolio col-3">
					<?php while ( have_posts() ) : the_post(); ?>

						<?php
							/* Include the Post-Format-specific template for the content.
							 * If you want to overload this in a child theme then include a file
							 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
							 */
							get_template_part( 'content', 'portfolio-grid' );

						?>

					<?php endwhile; ?>
					</div>

					<?php agility_content_nav( 'nav-below' ); ?>

				<?php else : ?>

					<article id="post-0" class="post no-results not-found">
						<header class="entry-header">
							<h1 class="entry-title"><?php _e( 'Nothing Found', 'agility' ); ?></h1>
						</header><!-- .entry-header -->

						<div class="entry-content">
							<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'agility' ); ?></p>
							<?php get_search_form(); ?>
						</div><!-- .entry-content -->
					</article><!-- #post-0 -->

				<?php endif; ?>

				</div>
				<!-- end #content -->
			</div>
			<!-- end #primary .site-content -->
		
			<?php //get_sidebar(); ?>
		
		</div>
		<!-- end #main-container .container -->

		<!-- end taxonomy.php -->
		
<?php get_footer(); ?>