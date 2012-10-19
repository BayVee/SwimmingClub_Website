<?php 

//OK Button - Displays a button
add_shortcode('button', 'cpotheme_shortcode_button');
function cpotheme_shortcode_button($atts, $content = null){
	$attributes = extract(shortcode_atts(array(
		'url' => '',
		'size' => ''), 
		$atts));
	
	$content = trim(strip_tags($content));
	$url = htmlentities($url);
	
	$size = trim(strip_tags($size));
	switch($size){
		case 'small': $size = 'button_small'; break;
		case 'medium': $size = 'button_medium'; break;
		case 'large': $size = 'button_large'; break;
		default: $size = ''; break;
	}
	
	return '<a class="button '.$size.'" href="'.$url.'">'.$content.'</a>';
}

?>