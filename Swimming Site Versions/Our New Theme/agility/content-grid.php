<?php
/**
 * The template used for displaying post items in a grid
 *
 * @package Agility
 * @since Agility 1.0
 */

global $agilitySettings, $post;
?>

<!-- Begin content-grid.php -->
<article id="post-<?php the_ID(); ?>" <?php post_class( 'one-third column with-meta' ); ?>>
	
	<?php switch( get_post_format() ){
		

	/* FORMAT: STATUS */
	case 'status': ?>
		<div class="entry-excerpt tagline notop-margin nobottom">

			<?php switch( $agilitySettings->op( 'blog-excerpt' ) ){

				case 'full':
					the_content( __( 'More &rarr;', 'agility' ) );
					break;

				case 'excerpt':
					the_excerpt(); ?>
					<a href="<?php the_permalink(); ?>" class="excerpt-link"><?php _e( 'More &rarr;', 'agility' ); ?></a>
					<?php
			}
			?>

		</div>

	<?php break;



	/* FORMAT: IMAGE */
	case 'image':
		?>
		<?php agility_featured_image( agility_image_size( $post->ID , 640 ), '' , false, '', false, get_the_title() ); ?>		
		<a href="<?php the_permalink(); ?>" class="excerpt-link"><?php _e( 'View &rarr;', 'agility' ); ?></a>


	<?php break;



	/* FORMAT: QUOTE */
	case 'quote': ?>
		<a href="<?php the_permalink(); ?>" class="quote-link"><?php the_content(); ?></a>

	<?php break;



	/* FORMAT: VIDEO */
	case 'video':
		switch( get_post_meta( $post->ID , 'feature_type', true ) ){
				
			case 'video-self':
				agility_featured_videojs();
					
				break; 

			case 'video':
			case 'video-embed':
			default:
				$video_url = get_post_meta( $post->ID, 'featured_video' , true );
				if( $video_url ){
					agility_featured_video_embedded( $video_url );
				}
				else{
					?>
					<div class="hint clearfix"><?php _e( 'Please enter a video URL in the "Featured Video" field, or change the Feature Type', 'agility' ); ?></div>
					<?php
				}
				break;
		}
		?>
		<a href="<?php the_permalink(); ?>" class="excerpt-link"><?php _e( 'More &rarr;', 'agility' ); ?></a>

	<?php break;



	/* FORMAT: GALLERY */
	case 'gallery':
		agility_slider( $post->ID , 'attachments' , agility_image_size( $post->ID , 640 ) ); ?>
		<a href="<?php the_permalink(); ?>" class="excerpt-link"><?php _e( 'View Gallery &rarr;' , 'agility' ); ?></a>
	
	<?php break;




	default: ?>

		<header class="">

			<?php if( has_post_thumbnail() ): ?>
			
			<div class="featured-image img-wrapper full-width">
			
				<?php agility_featured_image(); ?>
			
			</div>
			
			<?php endif; ?>

			<h1 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>

			<!-- begin .entry-meta -->
			<div class="entry-meta">
				
				<span class="cat-links">
				<?php agility_categories(); ?>
				</span>
							
				<span class="meta-right far-edge">
					<?php agility_posted_on(); ?>
				</span>
			</div>
			<!-- end .post-meta -->
							
		</header>

		<div class="">

			
			<div class="entry-excerpt <?php if( 'aside' == get_post_format() ) echo 'tagline medium'; ?>">
				<?php the_excerpt(); ?>
				<a href="<?php the_permalink(); ?>" class="excerpt-link"><?php _e( 'Continue Reading &rarr;', 'agility' ); ?></a>
			</div><!-- .entry-content -->
			
		</div>

	<?php } // end switch ?>
	
</article><!-- #post-<?php the_ID(); ?> -->
<!-- end content-grid.php -->