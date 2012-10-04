<?php
/**
 * The template used for displaying slides
 *
 * @package Agility
 * @since Agility 1.0
 */
 
global $post, $slide_img_size;

$slide_class 	= 'slide';
$url 			= '';

$caption 		= get_the_excerpt();
$captionClass 	= 'flex-caption';
$displayTitle 	= agility_meta_on( 'display-title' , 'on' );
$displayCaption = agility_meta_on( 'display-caption' , 'on' );

$slideType 		= get_post_meta( $post->ID, 'slide_type', 'image' , true );
//if( $slideType == 'video-embed' ) $slide_class.= ' slide-video';

//If slide image size hasn't been set by other template, use full size
if( !$slide_img_size ) $slide_img_size = 'full';


/*
 * Determine URL and class based on port type, which may be post, portfolio-item, slide, or attachment
 */
switch( $post->post_type ){

	case 'post':
		$url = get_permalink();
		$slide_class.= ' slide-post';
		break;

	case 'portfolio-item':
		$url = get_permalink();
		$slide_class.= ' slide-portfolio-item';
		break;

	case 'slide':
		$url = get_post_meta( $post->ID , 'slide_link' , true );
		$slide_class.= ' slide-slide';	
		break;

	case 'attachment':
		//$url = get_the_permalink();
		$slide_class.= ' slide-attachment';
		$displayTitle = get_post_meta( $post->post_parent , 'display-title' , true ) == 'off' ? false : true;
		$displayCaption = get_post_meta( $post->post_parent , 'display-caption' , true ) == 'off' ? false : true;
		break;		

}

?>

<li class="<?php echo $slide_class; ?> slide-<?php the_ID(); ?>" >
	
	<?php
	/*
	 * Generate slide appropriate for type: image (Slide), text (Slide), 
	 * image (portfolio or post), attachment (portfolio or post), text only (portfolio or post)
	 */
	switch( $slideType ){

		//Slide: Image
		case 'image':
			if( has_post_thumbnail() ){
				if( $url ): ?><a href="<?php echo $url; ?>"><?php endif;
				the_post_thumbnail( $slide_img_size , array( 'class' => 'scale-with-grid size-'.$slide_img_size ));
				if( $url ): ?></a><?php endif;
			}
			else $captionClass = 'flex-caption-text-only';
			break;

		/*case 'video-embed':
			$video_url = get_post_meta( $post->ID, 'featured_video' , true );
			$displayTitle = false;
			$displayCaption = false;
			if( $video_url ){
				agility_video_embed( $video_url , false );
			}
			else{
			?>
				<div class="hint clearfix">Please enter a video URL in the "Featured Video" field, or change the Slide Type</div>
			<?php
			}
			break;
		*/

		//Slide: Text
		case 'text':
			$captionClass = 'flex-caption-text-only';
			break;

		//Post or Portfolio
		default:

			if( has_post_thumbnail() ){
				if( $url ): ?><a href="<?php echo $url; ?>"><?php endif;
				the_post_thumbnail( $slide_img_size , array( 'class' => 'scale-with-grid size-'.$slide_img_size ));
				if( $url ): ?></a><?php endif;
			}
			else if( $post->post_type == 'attachment' ){
				echo wp_get_attachment_image( get_the_ID(), $slide_img_size, false, array( 'class' => 'scale-with-grid size-'.$slide_img_size ) );
			}
			else{
				$captionClass = 'flex-caption-text-only';
			}
	}
	
	?>

	<?php if( $displayCaption || $displayTitle ): ?>
	<div class="<?php echo $captionClass; ?>">
		<?php if( $displayTitle ): ?>
		<h5><?php if( $url ): ?><a href="<?php echo $url; ?>"><?php endif; ?><?php the_title(); ?><?php if( $url ): ?></a><?php endif; ?></h5>
		<?php endif; ?>

		<?php if( $displayCaption ): ?>
		<?php echo $caption; ?>
		<?php endif; ?>

	</div>
	<!-- end flex-caption -->
	<?php endif; ?>
	
	
</li><!-- .slide-<?php the_ID(); ?> -->
