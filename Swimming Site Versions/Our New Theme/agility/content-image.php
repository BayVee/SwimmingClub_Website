<?php
/**
 * The template used for displaying posts in the loop in with the "Image" post format
 *
 * @package Agility
 * @since Agility 1.0
 */

global $post_index, $agilitySettings;

extract( agility_post_loop_setup() );

?>

<!-- begin content-image.php: #post-<?php the_ID(); ?> -->
<article id="post-<?php the_ID(); ?>" <?php post_class( $post_class ); ?>>
	
	<?php agility_first_item_flag( $is_latest ); ?>
	
	<!-- begin .entry-header -->
	<header class="entry-header">
		
		<?php agility_featured_image( agility_image_size( $post->ID , 640 ), '' , false, '', false, get_the_title() ); ?>		

	</header>
	<!-- end .entry-header -->

	<div class="">

		<a href="<?php the_permalink(); ?>" class="excerpt-link"><?php _e( 'View &rarr;' , 'agility' ); ?></a>

		<!-- begin .entry-meta -->
		<footer class="entry-meta">

			<span class="five columns alpha">
				<?php if ( comments_open() || ( '0' != get_comments_number() && ! comments_open() ) ) : ?>
				<span class="post-comments tooltip-container"><a href="<?php comments_link(); ?>" class="icon comments-icon tooltip-anchor"></a><span 
					class="tooltip"><?php comments_popup_link( __( 'Leave a comment', 'agility' ), __( '1 Comment', 'agility' ), __( '% Comments', 'agility' ) ); ?></span></span>
				<?php endif; ?> 
				<span class="post-permalink tooltip-container"><?php echo agility_permalink( '', 'icon permalink-icon tooltip-anchor' ); ?><span class="tooltip"><?php _e( 'Permalink', 'agility' ); ?></span></span>
				<?php edit_post_link( '', '<span class="edit-link tooltip-container">', '<span class="tooltip">Edit</span></span>' ); ?>
			</span>

			<span class="six columns omega far-edge">
				<?php printf( __( 'Posted in %s', 'agility' ), agility_categories( false ) ); ?> <?php agility_posted_on(); ?> 
			</span>
			
			
		</footer>
		<!-- end #entry-meta -->
	</div>

</article>
<!-- end content-image.php #post-<?php the_ID(); ?> -->

