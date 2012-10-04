<?php
/**
 * Displays a post with format: Gallery
 *
 * The post will be displayed with a slider
 *
 * @package Agility
 * @since Agility 1.0
 */

global $post, $post_index, $agilitySettings;

extract( agility_post_loop_setup() );

?>

<!-- begin content-gallery.php: #post-<?php the_ID(); ?> -->
<article id="post-<?php the_ID(); ?>" <?php post_class( $post_class ); ?>>
	
	<?php agility_first_item_flag( $is_latest ); ?>
	
	<!-- begin .entry-header -->
	<header class="entry-header">
		
		<h1 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'agility' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>

		<?php if ( 'post' == get_post_type() ) : ?>
		<!-- begin .entry-meta -->
		<div class="entry-meta">
			
			<?php if ( 'post' == get_post_type() ) : // Hide category and tag text for pages on Search ?>
			<span class="cat-links four columns alpha">
			<?php agility_categories(); ?>
			</span>
			<?php endif; //End if 'post' type ?>
			
			<span class="seven columns omega far-edge">
				<?php agility_posted_on(); ?>
			</span>
		</div>
		<!-- end .post-meta -->
		<?php endif; ?>
		
		<?php agility_slider( $post->ID , 'attachments' , agility_image_size( $post->ID , 640 ) ); ?>

	</header>
	<!-- end .entry-header -->

	<div class="entry-excerpt">

		<?php switch( $agilitySettings->op( 'blog-excerpt' ) ){

			case 'full':
				the_content( __( 'View Gallery &rarr;', 'agility' ) );
				break;

			case 'excerpt':
				the_excerpt(); ?>
				<a href="<?php the_permalink(); ?>" class="excerpt-link"><?php _e( 'View Gallery &rarr;', 'agility' ); ?></a>
				<?php
		}
		?>

	</div><!-- .entry-content -->
	

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
		
	</footer>
	<!-- end #entry-meta -->
	

</article>
<!-- end content-gallery.php #post-<?php the_ID(); ?> -->

