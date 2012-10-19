<?php 

//Displays the accordion wrapper
add_shortcode('accordion_wrap', 'cpotheme_shortcode_accordion_wrap');
function cpotheme_shortcode_accordion_wrap($atts, $content = null){
	return '<div id="accordion">'.do_shortcode($content).'</div>';
}

//Accordion item - wraps content around an accordion item and adds a title
add_shortcode('accordion', 'cpotheme_shortcode_accordion_item');
function cpotheme_shortcode_accordion_item($atts, $content = null){
	$attributes = extract(shortcode_atts(array(
		'title' => 'Abrir'), 
		$atts));
	
	$content = trim($content);
	$title = trim(htmlentities(strip_tags($title)));
	
	return '<h3>'.$title.'</h3><div>'.do_shortcode($content).'</div>';
}

?>