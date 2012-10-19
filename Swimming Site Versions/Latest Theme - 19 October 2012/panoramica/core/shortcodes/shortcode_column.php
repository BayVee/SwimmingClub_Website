<?php 

//Half Column
add_shortcode('column_half', 'cpotheme_shortcode_column2');
function cpotheme_shortcode_column2($atts, $content = null){
	$content = $content;	
	return '<div class="column col2">'.do_shortcode($content).'</div>';
}
add_shortcode('column_half_last', 'cpotheme_shortcode_column2_last');
function cpotheme_shortcode_column2_last($atts, $content = null){
	$content = $content;	
	return '<div class="column col2_last">'.do_shortcode($content).'</div><div class="col_divide"></div>';
}

//Third Column
add_shortcode('column_third', 'cpotheme_shortcode_column3');
function cpotheme_shortcode_column3($atts, $content = null){
	$content = $content;	
	return '<div class="column col3">'.do_shortcode($content).'</div>';
}
add_shortcode('column_third_last', 'cpotheme_shortcode_column3_last');
function cpotheme_shortcode_column3_last($atts, $content = null){
	$content = $content;	
	return '<div class="column col3_last">'.do_shortcode($content).'</div><div class="col_divide"></div>';
}

//Fourth Column
add_shortcode('column_fourth', 'cpotheme_shortcode_column4');
function cpotheme_shortcode_column4($atts, $content = null){
	$content = $content;	
	return '<div class="column col4">'.do_shortcode($content).'</div>';
}
add_shortcode('column_fourth_last', 'cpotheme_shortcode_column4_last');
function cpotheme_shortcode_column4_last($atts, $content = null){
	$content = $content;	
	return '<div class="column col4_last">'.do_shortcode($content).'</div><div class="col_divide"></div>';
}

//Fifth Column
add_shortcode('column_fifth', 'cpotheme_shortcode_column5');
function cpotheme_shortcode_column5($atts, $content = null){
	$content = $content;	
	return '<div class="column col5">'.do_shortcode($content).'</div>';
}
add_shortcode('column_fifth_last', 'cpotheme_shortcode_column5_last');
function cpotheme_shortcode_column5_last($atts, $content = null){
	$content = $content;	
	return '<div class="column col5_last">'.do_shortcode($content).'</div><div class="col_divide"></div>';
}

//Divider - Displays a dividing line separating content
add_shortcode('divide', 'cpotheme_shortcode_divide');
function cpotheme_shortcode_divide($atts, $content = null){
	return '<hr/>';
}

//Divider - Displays a dividing line separating content
add_shortcode('divider', 'cpotheme_shortcode_divider');
function cpotheme_shortcode_divider($atts, $content = null){
	return '<div style="clear:both;width:100%;"></div>';
}

?>