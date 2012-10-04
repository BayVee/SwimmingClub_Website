<?php
/**
 * The template used for displaying featured items
 *
 * @package Agility
 * @since Agility 1.0
 */

global $alphaomega, $featured_image_size;
if( !$featured_image_size ) $featured_image_size = 'full';
?>

				<!-- feature column -->
				<article id="feature-item-<?php the_ID(); ?>" class="feature-column one-third column <?php echo $alphaomega; ?>">
						<?php agility_featured_image( $featured_image_size ); //'crop_640'  ?>

						<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
						<?php the_excerpt(); ?>
				</article>
				<!-- end .feature-column -->
