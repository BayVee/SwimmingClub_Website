<?php
/**
 * The template used for displaying portfolio items in a grid
 *
 * @package Agility
 * @since Agility 1.0
 */
?>

<?php

global $portfolio_id, $portfolio_index, $portfolio_meta, $post;

//extract portfolio_meta with defaults?

//Determine Grid Columns for portfolio item
$items_per_row 		= $portfolio_meta['items_per_row'];
$grid_columns 		= !empty( $portfolio_meta['grid_columns'][0] ) ? $portfolio_meta['grid_columns'][0] : '1-3'; 
$grid_columns_class = agility_grid_columns_class( $grid_columns );

//Show the title and excerpt?
$show_title 		= !empty( $portfolio_meta['portfolio_show_title'][0] ) ? $portfolio_meta['portfolio_show_title'][0] : 'on';
$show_excerpt 		= !empty( $portfolio_meta['portfolio_show_excerpt'][0] ) ? $portfolio_meta['portfolio_show_excerpt'][0] : 'on';

//Dealing with inner columns?
$wrap 				= isset( $portfolio_meta['wrap'] ) ? $portfolio_meta['wrap'] : true;
$alphaomega 		= '';
if( $wrap ) $alphaomega = agility_alphaomega( $portfolio_index , $items_per_row );

//Optimize image size for container
$crop = !empty( $portfolio_meta['portfolio_crop_items'][0] ) ? $portfolio_meta['portfolio_crop_items'][0] : 'off';
$pixel_width = agility_max_pixel_width( $grid_columns );
$image_size = agility_image_size( $post->ID , $pixel_width , $crop );

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'portfolio-item '.$grid_columns_class.' author-'.$post->post_author .' '.$alphaomega ); ?>>
	
	<?php if( has_post_thumbnail() ): ?>
		<?php agility_featured_image( $image_size, '', false, '[portfolio-'.$portfolio_id.']'); ?>
	<?php endif; ?>
	
	<?php if( $show_title == 'on' ): ?>
		<header class="entry-header">
			<h1 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
		</header>
	<?php endif; ?>
		
	<?php if( $show_excerpt == 'on' ): ?>
		<div class="portfolio-excerpt">
			<?php the_excerpt(); ?>
		</div><!-- .entry-content -->
	<?php endif; ?>

	<?php //agility_portfolio_nav($items, $id, $item_list); ?>
		
</article><!-- #post-<?php the_ID(); ?> -->
