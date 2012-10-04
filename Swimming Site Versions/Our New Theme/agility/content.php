<?php
/**
 * Default template for displaying Blog Posts in the Loop
 * 
 * @package Agility
 * @since Agility 1.0
 */

global $post, $post_index, $agilitySettings;

extract( agility_post_loop_setup() );
extract( agility_post_loop_setup_post( $show_feature , $is_latest ) );

?>

<!-- begin content.php: #post-<?php the_ID(); ?> -->
<article id="post-<?php the_ID(); ?>" <?php post_class( $post_class ); ?>>
	
	<?php agility_first_item_flag( $is_latest ); ?>

	<!-- begin .entry-header -->
	<header class="entry-header">
		
		<?php if( !$is_latest && $show_feature ): ?>
			<?php agility_featured_image( agility_image_size( $post->ID, 640 ), $left_class ); ?>
		<?php endif; ?>
		
		<div class="<?php echo $right_header_class; ?>">
			<h1 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'agility' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>

			<?php if ( 'post' == get_post_type() ) : ?>
			<!-- begin .entry-meta -->
			<div class="entry-meta">
				
				<?php if ( 'post' == get_post_type() ) : // Hide category and tag text for pages on Search ?>
				<span class="cat-links <?php echo $meta_left_cols; ?> columns alpha">
				<?php agility_categories(); ?>
				</span>
				<?php endif; //End if 'post' type ?>
				
				<span class="<?php echo $meta_right_cols; ?> columns omega far-edge">
					<?php agility_posted_on(); ?>
				</span>
			</div>
			<!-- end .post-meta -->
			<?php endif; ?>
		</div>
		
		<?php if( $is_latest && has_post_thumbnail() ): ?>
			<?php agility_featured_image( agility_image_size( $post->ID, 640 ), $left_class ); ?>
		<?php endif; ?>

	</header>
	<!-- end .entry-header -->

	<div class="<?php echo $right_class; ?>">

		<?php if ( is_search() ) : // Only display Excerpts for Search ?>
		<div class="entry-summary">
			<?php the_excerpt(); ?>
		</div><!-- .entry-summary -->
		<?php else : ?>

		<?php if( $feature_type == 'video-self' ): ?>
			<?php agility_featured_videojs(); ?>
		<?php endif; ?>

		<div class="entry-excerpt">

			<?php switch( $agilitySettings->op( 'blog-excerpt' ) ){

				case 'full':
					the_content( __( 'Continue Reading &rarr;', 'agility' ) );
					break;

				case 'excerpt':
					the_excerpt(); ?>
					<a href="<?php the_permalink(); ?>" class="excerpt-link"><?php _e( 'Continue Reading &rarr;', 'agility' ); ?></a>
					<?php
			}
			?>

		</div><!-- .entry-content -->
		<?php endif; ?>

		<!-- begin .entry-meta -->
		<footer class="entry-meta">
			<?php if ( 'post' == get_post_type() ) : // Hide category and tag text for pages on Search ?>
				

				<?php
					/* translators: used between list items, there is a space after the comma */
					$tags_list = get_the_tag_list( '', __( ', ', 'agility' ) );
					if ( $tags_list ) :
				?>
				<span class="tag-links tooltip-container"><a href="#" class="icon tag-icon tooltip-anchor"></a><span class="tooltip"><?php 
					echo $tags_list; ?></span>
				</span>
				<?php endif; // End if $tags_list ?>
			<?php endif; // End if 'post' == get_post_type() ?>

			<?php if ( comments_open() || ( '0' != get_comments_number() && ! comments_open() ) ) : ?>
			<span class="post-comments tooltip-container"><a href="<?php comments_link(); ?>" class="icon comments-icon tooltip-anchor"></a><span 
				class="tooltip"><?php comments_popup_link( __( 'Leave a comment', 'agility' ), __( '1 Comment', 'agility' ), __( '% Comments', 'agility' ) ); ?></span></span>
			<?php endif; ?> 
			<span class="post-permalink tooltip-container"><?php echo agility_permalink( '', 'icon permalink-icon tooltip-anchor' ); ?><span class="tooltip"><?php _e( 'Permalink', 'agility' ); ?></span></span>
			<?php edit_post_link( '', '<span class="edit-link tooltip-container">', '<span class="tooltip">Edit</span></span>' ); ?>
		</footer>
		<!-- end #entry-meta -->
	</div>

</article>
<!-- end content.php #post-<?php the_ID(); ?> -->

