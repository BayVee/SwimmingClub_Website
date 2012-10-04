<?php
/**
 * Self-hosted Video Module
 */

function agility_videojs_scripts_and_styles(){

	global $agilitySettings;

	if( $agilitySettings->op( 'self-hosted-video' ) ){
		//Stylesheets
		wp_enqueue_style( 'videojs', 'http://vjs.zencdn.net/c/video-js.css' );

		//Javascript
		wp_enqueue_script( 'videojs' , 'http://vjs.zencdn.net/c/video.js' , false, false ); // false );
	}
}
add_action( 'wp_enqueue_scripts', 'agility_videojs_scripts_and_styles' );



function agility_featured_videojs( $delay = false , $width = 640 , $height = 264 ){
	global $post;

	$sources = array(
		'ogg'	=>	get_post_meta( $post->ID , 'featured_video_ogg' , true ),
		'webm'	=>	get_post_meta( $post->ID , 'featured_video_webm' , true ),
		'mp4'	=>	get_post_meta( $post->ID , 'featured_video_mp4' , true ), //
		'img'	=>	wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) ),
	);

	agility_videojs( 'featured-video-'.$post->ID, $sources , $width, $height, $delay );

}

function agility_videojs( $id, $sources , $width = 640 , $height = 264 , $delay = false ){

	$source_defaults = array(
		'ogg'	=>	'',
		'mp4'	=>	'',
		'webm'	=>	'',
		'img'	=>	'',
	);

	extract( wp_parse_args( $sources , $source_defaults ) );

	?>

	<?php if( !$ogg && !$mp4 && !$webm ):?>

				<div class="hint">Please define at least one video source file (ogg, mp4, webm).</div>

	<?php else: ?>


				<!-- featured-video -->
				<div id="<?php echo $id; ?>" class="featured-video featured-video-videojs">
					
					
					<!-- Begin VideoJS - Self Hosted Video -->
					<!-- Using the Video for Everybody Embed Code http://camendesign.com/code/video_for_everybody -->
					<video class="video-js vjs-default-skin" width="<?php echo $width; ?>" height="<?php echo $height; ?>" controls preload="auto" 
							poster="<?php echo $img; ?>" data-setup="{}" >

					<?php if( $mp4 ): ?>
						<source src="<?php echo $mp4; ?>" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"' />
					<?php endif; ?>

					<?php if( $webm ): ?>
						<source src="<?php echo $webm; ?>" type='video/webm; codecs="vp8, vorbis"' />
					<?php endif; ?>

					<?php if( $ogg ): ?>
						<source src="<?php echo $ogg; ?>" type='video/ogg; codecs="theora, vorbis"' />
					<?php endif; ?>

					<?php if( $mp4 ): ?>
						<!-- Flash Fallback. -->
						<object class="vjs-flash-fallback" width="<?php echo $width; ?>" height="<?php echo $height; ?>" type="application/x-shockwave-flash"
							data="http://vjs.zencdn.net/3.2/video-js.swf">
							<param name="movie" value="http://vjs.zencdn.net/3.2/video-js.swf" />
							<param name="allowfullscreen" value="true" />
							<param name="flashvars" value='config={"playlist":["<?php echo $mp4; ?>", {"url": "<?php echo $mp4; ?>","autoPlay":false,"autoBuffering":true}]}' />
							<!-- Image Fallback. Typically the same as the poster image. -->
							<img src="<?php echo $img; ?>" width="<?php echo $width; ?>" height="<?php echo $height; ?>" alt="Poster Image"
								title="No video playback capabilities." />
						</object>
					<?php endif; ?>

					</video>
					<!-- End VideoJS -->					
  					
				</div>
				<!-- end .featured-video -->

	<?php endif;
}


//Create Meta Box
if(!class_exists('AgilityCustomPostType')) require_once( get_template_directory().'/modules/custom_post_types/AgilityCustomPostType.class.php');

class SHVideoSettingsMetaBox extends CustomMetaBox{

	public function __construct( $id, $title, $page, $context = 'side', $priority = 'default' ){
			
		parent::__construct( $id, $title, $page, $context, $priority );		

		$this->addField( new TextMetaField( 'featured_video_mp4', __( 'MP4 Video URL', 'agility' ) , '' , array( 
					'description' => __( 'MP4 is required for Safari/iOS HTML5 Video and Flash Fallback', 'agility' ),
				) ) );
		
		$this->addField( new TextMetaField( 'featured_video_ogg', __( 'OGG Video URL', 'agility' ) , '' , array( 
					'description' => __( 'OGG (.ogv) is used for Firefox/Opera/Chrome HTML5 Video', 'agility' ),
				) ) );

		$this->addField( new TextMetaField( 'featured_video_webm', __( 'WebM Video URL', 'agility' ) , '' , array( 
					'description' => '<a href="http://camendesign.com/code/video_for_everybody#webm">WebM</a> '.__( 'is optional.', 'agility' ),
				) ) );

	}

}