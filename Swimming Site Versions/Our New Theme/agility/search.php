<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package Agility
 * @since Agility 1.0
 */

get_header(); ?>

		<!-- Begin search.php 
		============================================ -->
		
		<!-- #main-container container -->
		<div id="main-container" class="container">
			
			<!-- #primary .site-content -->
			<section id="primary" class="site-content eleven columns">
				
				<div id="content" role="main">

				<?php if ( have_posts() ) : ?>

					<header class="page-header">
						<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'agility' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
					</header>

					<?php agility_content_nav( 'nav-above' ); ?>

					<?php agility_bloglist( null, 'eleven' ); wp_reset_postdata(); ?>

					<?php agility_content_nav( 'nav-below' ); ?>

				<?php else : ?>

					<article id="post-0" class="post no-results not-found">
						<header class="entry-header">
							<h1 class="entry-title"><?php printf( $agilitySettings->op( 'search-header-noresults' ), '<em>'.get_search_query().'</em>' ); ?></h1>
						</header><!-- .entry-header -->

						<div class="entry-content">
							<p><?php echo $agilitySettings->op( 'search-message-noresults' ); ?></p>
							<?php get_search_form(); ?>
						</div><!-- .entry-content -->
					</article><!-- #post-0 -->

				<?php endif; ?>

				</div>
				<!-- end #content -->
			</section>
			<!-- end #primary .site-content -->
		
			<?php get_sidebar(); ?>
		
		</div>
		<!-- end #main-container .container -->

		<!-- end search.php -->
		
<?php get_footer(); ?>