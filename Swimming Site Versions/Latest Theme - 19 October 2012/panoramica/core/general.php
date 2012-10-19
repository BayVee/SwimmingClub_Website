<?php

//Displays the blog title and descripion in home or frontpage
function cpotheme_title(){
	global $post, $page, $paged;
	$title = '';
	if(cpotheme_get_option('cpo_seo_global') == '1'){
		if(is_home() || is_front_page())
			$title = cpotheme_get_option('cpo_general_title');
		else
			$title = get_post_meta($post->ID, 'seo_title', true);
		if($title != '') echo $title;
	}
	
	//Default to normal titles if SEO titles are blank or turned off
	if($title == ''){
		$separator = '';
		if(cpotheme_get_option('cpo_seo_global') == '1')
			$separator = cpotheme_get_option('cpo_title_separator');
		if($separator == '') $separator = '|';
		wp_title($separator, true, 'right');
		bloginfo('name');
		$site_description = get_bloginfo('description', 'display');
		if($site_description && (is_home() || is_front_page()))
			echo ' '.$separator.' '.$site_description;
	
		// Page numbers
		if($paged >= 2 || $page >= 2) echo ' | '.sprintf( __('Page %s', 'cpotheme'), max($paged, $page));
	}
}

//Display the meta description
function cpotheme_description(){
	global $post;
	$description = '';
	//Check if SEO is enabled
	if(cpotheme_get_option('cpo_seo_global') == '1'){
		if(is_home() || is_front_page()){
			//Check for the homepage value
			$description = cpotheme_get_option('cpo_general_description');
			
			//If not, resort to default values
			if($description == '') 
				$description = cpotheme_get_option('cpo_default_description');
		}else{
			//Check whether custom SEO for individual pages is enabled
			$description = get_post_meta($post->ID, 'seo_description', true);
			
			//If not, resort to default values
			if($description == '')
				$description = cpotheme_get_option('cpo_default_description');
		}
		if($description != '') echo '<meta name="description" content="'.$description.'" />';
	}
}

//Display custom favicon
function cpotheme_favicon(){
	$favicon_url = cpotheme_get_option('cpo_general_favicon');
	if($favicon_url != '')
    	echo '<link type="image/x-icon" href="'.esc_url($favicon_url).'" rel="icon" />';
}

//Display meta keywords
function cpotheme_keywords(){
	global $post;
	$keywords = '';
	//Check if SEO is enabled
	if(cpotheme_get_option('cpo_seo_global') == '1'){
		if(is_home() || is_front_page()){
			//Check for the homepage value
			$keywords = cpotheme_get_option('cpo_general_keywords');
			
			//If not, resort to default values
			if($keywords == '') 
				$keywords = cpotheme_get_option('cpo_default_keywords');
		}else{
			//Check whether custom SEO for individual pages is enabled
			$keywords = get_post_meta($post->ID, 'seo_keywords', true);
			
			//If not, resort to default values
			if($keywords == '')
				$keywords = cpotheme_get_option('cpo_default_keywords');
		}
		if($keywords != '') echo '<meta name="keywords" content="'.$keywords.'" />';
	}
}

//Display custom fonts
function cpotheme_fonts($font_name){	
	$font_value = '';
	switch($font_name){
		case 'asap': $font_value = 'Asap'; break;
		case 'bree_serif': $font_value = 'Bree Serif'; break;
		case 'dancing_script': $font_value = 'Dancing+Script'; break;
		case 'droid_sans': $font_value = 'Droid+Sans'; break;
		case 'imprima': $font_value = 'Imprima'; break;
		case 'great_vibes': $font_value = 'Great+Vibes'; break;
		case 'gudea': $font_value = 'Gudea'; break;
		case 'lobster': $font_value = 'Lobster'; break;
		case 'oleo_script': $font_value = 'Oleo+Script'; break;
		case 'oxygen': $font_value = 'Oxygen'; break;
		case 'quattrocento': $font_value = 'Quattrocento'; break;
		case 'raleway': $font_value = 'Raleway:100'; break;
		case 'sorts_mill_goudy': $font_value = 'Sorts+Mill+Goudy'; break;
		case 'yanone_kaffeesatz': $font_value = 'Yanone+Kaffeesatz'; break;
	}
	if($font_value != '') echo '<link href="http://fonts.googleapis.com/css?family='.$font_value.'" rel="stylesheet" type="text/css">';
}

//Outputs font names as used in the CSS
function cpotheme_fonts_css($font_name){	
	$font_value = '';
	switch($font_name){
		case "arial": $font_value = "Arial"; break;
		case "asap": $font_value = "'Asap'"; break;
		case "bree_serif": $font_value = "'Bree Serif'"; break;
		case "dancing_script": $font_value = "'Dancing Script'"; break;
		case "droid_sans": $font_value = "'Droid Sans'"; break;
		case "imprima": $font_value = "'Imprima'"; break;
		case "georgia": $font_value = "Georgia"; break;
		case "great_vibes": $font_value = "'Great Vibes'"; break;
		case "gudea": $font_value = "'Gudea'"; break;
		case "lobster": $font_value = "'Lobster'"; break;
		case "oleo_script": $font_value = "'Oleo Script'"; break;
		case "oxygen": $font_value = "'Oxygen'"; break;
		case "quattrocento": $font_value = "'Quattrocento'"; break;
		case "raleway": $font_value = "'Raleway'"; break;
		case "sorts_mill_goudy": $font_value = "'Sorts Mill Goudy'"; break;
		case "times_new_roman": $font_value = "Times New Roman"; break;
		case "verdana": $font_value = "Verdana"; break;
		case "yanone_kaffeesatz": $font_value = "'Yanone Kaffeesatz'"; break;
	}
	echo $font_value;
}


//Adds custom analytics code in the footer
add_action('wp_footer','cpotheme_layout_analytics');
function cpotheme_layout_analytics(){
	$output = cpotheme_get_option('cpo_general_analytics');
	$output = stripslashes($output);
	echo $output;
}

//Abstracted function for retrieving specific options inside option arrays
function cpotheme_get_option($option_name = '') {
	$option_list = get_option('cpotheme_settings', false);
	if($option_list && isset($option_list[$option_name]))
		$option_value = $option_list[$option_name];
	else
		$option_value = false;
	return $option_value;
}

//Abstracted function for updating specific options inside arrays
function cpotheme_update_option($option_name, $option_value){
	$option_list = get_option('cpotheme_settings', false);
	if(!$option_list)
		$option_list = array();
	$option_list[$option_name] = $option_value;
	if(update_option('cpotheme_settings', $option_list))
		return true;
	else
		return false;
} ?>