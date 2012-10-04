<?php
/**
 * Sliders!
 */

require_once( 'SlidePostType.class.php' );

if ( ! function_exists( 'agility_slider' ) ) :
function agility_slider( $slider_id = 0, $config_type = 'slider_id', $slide_img_size = 'full' ){

	$slide_query = null;
	$sliderStruct = null;

	switch( $config_type ){

		case 'portfolio_item':
		case 'attachments':
			
			$args = array(
				'post_parent' 		=> $slider_id,
				'post_status' 		=> 'inherit',
				'post_type' 		=> 'attachment',
				'post_mime_type'	=> 'image',
				'order' 			=> 'ASC',
				'orderby' 			=> 'menu_order ID'
			);

			$sliderStruct = new SliderQueryStruct( $slider_id );
			$sliderStruct->loadSettings( $args );
			$slide_query = $sliderStruct->query();

			break;

		case 'slider_id':
			$sliderStruct = new SliderQueryStruct( $slider_id );
			$slide_query = $sliderStruct->query();
			break;

		default:
			if( is_null( $slide_query ) ){
				global $wp_query;
				$slide_query = $wp_query;
			}	
			break;
	}

	
	$k = 0;

	$GLOBALS['slide_img_size'] = $slide_img_size;

	?>

				<!-- FlexSlider -->
				<div class="flex-container">
					<div class="flexslider">
				    	<ul class="slides">

				    	<?php while ( $slide_query->have_posts() ) {
				    			$slide_query->the_post();
								get_template_part( 'content', 'slide' );
							}
				    	?>

						</ul>
					</div>
			 	</div>
				<!-- end FlexSlider -->

	<?php

	if( $sliderStruct ) $sliderStruct->queryCompleted();
}
endif; 


function agility_slider_shortcode( $atts ) {
	global $shortcodeMaster;
	extract( shortcode_atts( $shortcodeMaster->getDefaults( 'slider' ), $atts ) );

	$size = $size <= 640 ? 640 : 940;
	$crop = $crop == 'on' ? 'crop' : 'natural';

	$img_size = "{$crop}_{$size}";

	if( $id > 0 ){
		ob_start();
		agility_slider( $id , 'slider_id', $img_size );
		$slider_html = ob_get_contents();
		ob_end_clean();

		return $slider_html;
	}
}

agility_add_shortcode( 'slider', 'agility_slider_shortcode', array(
		'params'	=> array(
			'id'		=> array(
				'title'		=> 'Slider',
				'desc'		=> 'ID from the SliderConstructor',
				'type'		=> 'select',
				'ops'		=> 'agility_slider_ops',
				'default'	=> -1,
			),
			'size'		=> array(
				'title'		=> 'Size',
				'desc'		=> 'The width of the slider\'s container in pixels, used to determine the optimal size of the image to load. Use 940 for full-width sliders, or 640 for left-column content (11 grid columns).',
				'type'		=> 'text',
				'default'	=> 940,
			),
			'crop'		=> array(
				'title'		=> 'Crop',
				'desc'		=> 'Whether or not to crop the slides in this slider to your default aspect ratio, set in the Agility Control Panel',
				'type'		=> 'checkbox',
				'default'	=> 'off'
			),
		),
		'content' => false,
	) );