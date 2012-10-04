<?php
/**
 * The template used for displaying a single Portfolio Item
 *
 * @package Agility
 * @since Agility 1.0
 */
?>

<!-- begin content-portfolio-single.php -->
<article id="post-<?php the_ID(); ?>" <?php post_class( 'portfolio-item-single cf' ); ?>>
	<!-- Left Column - Photo or Video -->
	<div class="ten columns alpha">

		<?php agility_feature_single( false, true ); ?>
		&nbsp;					
	</div>
	<!-- end left column -->


	<!-- Right Column - Details -->
	<div class="six columns omega">
		<header class="entry-header">
			<h1 class="entry-title"><?php the_title(); ?></h1>
			<div class="entry-meta">
				<div class="four columns alpha">
					<span class="cat-links four columns alpha">
						<?php agility_custom_categories( $post->ID , 'portfolio-categories' ); ?>&nbsp;
					</span>
				</div>
				<div class="portfolio-meta-icons ">
						
					<span class="post-time tooltip-container">
						<a href="#" class="tooltip-anchor icon calendar-icon"></a>
						<span class="tooltip">
							<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>" class="entry-date"><?php echo get_the_date(); ?></time>
						</span>
					</span>
					
					<?php
					$tags_list = agility_custom_categories( $post->ID, 'portfolio-tags', false );
					if ( $tags_list ) :
					?>
					<span class="tag-links tooltip-container"><a href="#" class="icon tag-icon tooltip-anchor"></a><span class="tooltip"><?php 
						echo $tags_list; ?></span>
					</span>
					<?php endif; // End if $tags_list ?>
					
					<span class="post-permalink tooltip-container"><?php echo agility_permalink( '', 'icon permalink-icon tooltip-anchor' ); ?><span class="tooltip"><?php _e( 'Permalink', 'agility' ); ?></span></span>
					
				</div>
			</div>
		</header>
		
		<div class="entry-content">
			<?php the_content(); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'agility' ), 'after' => '</div>' ) ); ?>
			<?php edit_post_link( '', '<span class="edit-link tooltip-container">', '<span class="tooltip">Edit</span></span>' ); ?>
		</div><!-- .entry-content -->
						
		<?php //agility_portfolio_nav($items, $id, $item_list); ?>
		
	</div>
	<!-- end right column -->


	
</article><!-- #post-<?php the_ID(); ?> -->
<!-- end content-portfolio-single.php -->
