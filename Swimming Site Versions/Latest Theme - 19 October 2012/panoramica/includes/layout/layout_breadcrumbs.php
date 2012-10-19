<?php

// Generates breadcrumb navigation 
function cpotheme_layout_breadcrumb(){
	global $post;
	if(is_object($post)) $pid = $post->ID; else $pid = '';
	$result = '';
	
	if($pid != ''):
		if(is_singular()):
			$post_data = get_post($pid);
			$result = "<span>".apply_filters('the_title', $post_data->post_title)."</span>\n";
	
			while($post_data->post_parent):
				$post_data = get_post($post_data->post_parent);
				$result = "<a href='".get_permalink($post_data->ID)."'>".apply_filters('the_title', $post_data->post_title)."</a>\n".$result;
			endwhile;
	
		elseif(is_category()):			
			$post_data = get_the_category($pid);
			if(isset($post_data[0])):
				$data = get_category_parents($post_data[0]->cat_ID, TRUE, ' &raquo; ');
				if(!is_object($data)):
					$result = ''.substr($data, 0, -8).$result;
				endif;
			endif;
		endif;
	endif;
	
	$result = '<a href="'.home_url().'">'.get_bloginfo('name').'</a>'.$result;
 
    echo $result;
	
	
} ?>