<?php
/**
 * Template Name: Portfolio
 *
 * A portfolio.
 *
 * @package Agility
 * @since Agility 1.0
 */

get_header(); 
global $agilitySettings; ?>

		<!-- Begin page-portfolio.php 
		============================================ -->
		
		<!-- #main-container container -->
		<div id="main-container" class="container">
			
			<!-- #primary .site-content -->
			<div id="primary" class="site-content">
				
				<div id="content" role="main" class="">

				<?php while ( have_posts() ) : the_post();
					$portfolio_id = get_the_ID();
					$portfolio_meta = get_post_custom();
					$subtitle = empty( $portfolio_meta['post_subtitle'][0] ) ? false : $portfolio_meta['post_subtitle'][0];
					?>

					<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<div class="portfolio-above sixteen columns">
							<header class="entry-header">
								<h1 class="page-title entry-title <?php if( $subtitle ) echo 'entry-title-with-sub'; ?>"><?php the_title(); ?></h1>
								<?php if( $subtitle ): ?>
								<h5 class="sub-page-title"><?php echo $subtitle; ?></h5>
								<?php endif; ?>
							</header><!-- .entry-header -->
						</div>	

						<?php if( $portfolio_meta['content_order'][0] == 'above' ): ?>
						<div class="entry-content sixteen columns">
							<?php the_content(); ?>
							<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'agility' ), 'after' => '</div>' ) ); ?>
							<?php edit_post_link( __( 'Edit', 'agility' ), '<span class="edit-link">', '</span>' ); ?>
						</div><!-- .entry-content -->
						<?php endif; ?>			

						<div class="row">
							<?php
							$grid_columns = isset( $portfolio_meta['grid_columns'][0] ) ? $portfolio_meta['grid_columns'][0] : '1-3';
							$items_per_row = agility_divide_columns( $grid_columns, 16 );
							$col_x = 'col-'.$items_per_row;
							$portfolio_meta['items_per_row'] = $items_per_row;
							$portfolio_meta['wrap'] = false;
							
							//Portfolio Query
							$portfolio_query_id = get_post_meta( $post->ID , 'portfolio_query', true );
							$queryStruct 		= new PortfolioQueryStruct( $portfolio_query_id );
							$portfolio_query 	= $queryStruct->query();
							?>

							<?php $filtered = agility_portfolio_filters( $post, $portfolio_query, $portfolio_query_id ); ?>

							<div id="portfolio-<?php echo $portfolio_query_id; ?>" class="portfolio <?php echo $col_x; ?> <?php if( $filtered ): ?>isotope-container <?php endif; ?>clearfix">

							<?php

								if( $filtered ) rewind_posts();
								if( $portfolio_query->have_posts() ){ 
									while( $portfolio_query->have_posts() ){
										$portfolio_query->the_post();
										get_template_part( 'content', 'portfolio-grid' );
									}
								}
								?>

							</div>
							
							<div class="entry-content sixteen columns">
							<?php agility_content_nav( 'nav-below' , true, '' ); ?>
							</div>

								<?php

								$queryStruct->queryCompleted();
							?>

							
						</div>

						<?php if( $portfolio_meta['content_order'][0] == 'below' ): ?>
						<div class="entry-content sixteen columns">
							<?php the_content(); ?>
							<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'agility' ), 'after' => '</div>' ) ); ?>
							<?php edit_post_link( __( 'Edit', 'agility' ), '<span class="edit-link">', '</span>' ); ?>
						</div><!-- .entry-content -->
						<?php endif; ?>

					</div><!-- #post-<?php the_ID(); ?> -->
				<?php endwhile; // end of the loop. ?>

				<?php //comments_template( '', true ); ?>

				</div>
				<!-- end #content -->
			</div>
			<!-- end #primary .site-content -->
				
		</div>
		<!-- end #main-container .container -->

		<!-- end page-portfolio.php -->
		
<?php get_footer(); ?>

