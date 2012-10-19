<?php 

//Standard text field
function cpotheme_form_text($name, $value, $args = null){
	if(isset($args['width'])) $field_width = ' style="width:'.$args['width'].';"'; else $field_width = '';
	if(isset($args['placeholder'])) $field_placeholder = ' placeholder="'.$args['placeholder'].'"'; else $field_placeholder = '';
	$output = '<input type="text" value="'.stripslashes($value).'" name="'.$name.'" id="'.$name.'"'.$field_width.$field_placeholder.'/>';
	return $output;
}

//Textarea field
function cpotheme_form_textarea($name, $value, $args = null){	
	if(isset($args['placeholder'])) $field_placeholder = ' placeholder="'.$args['placeholder'].'"'; else $field_placeholder = '';		
	$output = '<textarea name="'.$name.'" id="'.$name.'"'.$field_placeholder.'>'.stripslashes($value).'</textarea>';
	return $output;
}

//File upload field
function cpotheme_form_upload_old($name, $value, $args = null){
	if(isset($args['placeholder'])) $field_placeholder = ' placeholder="'.$args['placeholder'].'"'; else $field_placeholder = '';		
	$output = '<input type="upload" value="'.stripslashes($value).'" name="'.$name.'" id="'.$name.'"'.$field_placeholder.'/>';
	$output .= '<input class="upload_button" type="button" value="Upload" name="'.$name.'" id="'.$name.'_button"/>';
	return $output;			
}

//Checkbox field
function cpotheme_form_checkbox($name, $value, $args = null){
	if($value != '') $field_value = ' checked'; else $field_value = '';
	$output = '<input type="checkbox" value="1" name="'.$name.'" id="'.$name.'"'.$field_value.'/>';
	return $output;
}

//Yes/No radio selection field
function cpotheme_form_yesno($name, $value, $args = null){
	$output = '<input type="radio" name="'.$name.'" id="'.$name.'_yes" value="1"'; 
	if($value == '1') $output .= ' checked';
	$output .= '/> <label for="'.$name.'_yes">'.__('Yes', 'cpotheme').'</label> &nbsp;&nbsp;&nbsp;&nbsp;';
	$output .= '<input type="radio" name="'.$name.'" id="'.$name.'_no" value="0"'; 
	if($value != '1') $output .= ' checked';
	$output .= '/> <label for="'.$name.'_no">'.__('No', 'cpotheme').'</label>';
	return $output;
}

//Dropdown list field
function cpotheme_form_select($name, $value, $list, $args = null){
	$output = '<select class="cpometabox_field_select" name="'.$name.'" id="'.$name.'">';
	if(sizeof($list) > 0)
		foreach($list as $list_key => $list_value){
			$output .= '<option value="'.$list_key.'"';
			if($value == $list_key) $output .= ' selected';
			$output .= '>'.$list_value.'</option>';
		}
	$output .= '</select>';
	return $output;
}

//Image list selection
function cpotheme_form_imagelist($name, $value, $list, $args = null) {    
    $output = '<div id="'.$name.'_wrap">';
    foreach ($list as $list_key => $list_value) {
        $checked = null;
        $selected = null;
        if($list_key == $value) {
            $checked = ' checked="checked"';
            $selected = ' class="selected"';
        }
        $output .= '<label class="form_image_list_item" for="'.$name.'_'.$list_key.'"><img '.$selected.' src="'.$list_value.'" alt="'.$list_key.'"/><br/>';
        $output .= '<input type="radio" name="'.$name.'" id="'.$name.'_'.$list_key.'" value="'.$list_key.'" '.$checked.'/>';        
        $output .= '</label>';        
    }
    $output .= '</div>';
    return $output;
}

//Color Picker field
function cpotheme_form_color($name, $value, $args = null){
	if(isset($args['placeholder'])) $field_placeholder = ' placeholder="'.$args['placeholder'].'"'; else $field_placeholder = '';		
	$output = '<div id="'.$name.'_wrap">';
	$output .= '<input type="text" class="color" value="'.$value.'" name="'.$name.'" id="'.$name.'"'.$field_placeholder.' maxlength="7"/>';
	$output .= '<div class="colorselector" id="'.$name.'_sample"></div>';
	$output .= '</div>';	
	return $output;
}

//Uploader using Media Library
function cpotheme_form_upload($name, $value, $args = null, $post = null) {
    $post_id = -1;
    if(!$post){
        $post_type = array('post_type' => $name);     
        $args = array('post_type' => $name,'post_parent' => '0','post_status' => 'draft','comment_status' => 'closed','ping_status' => 'closed','post_title' => $name);
        $posts = query_posts('post_type='.$name);   
        if(sizeof($posts) > 0)
            $post_id = $posts[0]->ID;
        else
            $post_id = wp_insert_post(array_merge($args, $post_type));
    }else{
        $post_id = $post->ID;
    }
    
    if(isset($args['placeholder'])) $field_placeholder = ' placeholder="'.$args['placeholder'].'"'; else $field_placeholder = '';		
	$output = '<input id="upload_image_hidden" type="hidden" name="'.$name.'" value="'.$post_id.'"/>';
    $output .= '<input type="upload" value="'.stripslashes($value).'" name="'.$name.'" id="upload_image"'.$field_placeholder.'/>';
	$output .= '<input class="upload_button" type="button" value="Upload" name="'.$name.'" id="upload_image_button"/>';
	return $output;	    
}

//Date picker field
function cpotheme_form_date($name, $value, $args = null){
	if(isset($args['placeholder'])) $field_placeholder = ' placeholder="'.$args['placeholder'].'"'; else $field_placeholder = '';
	if(isset($args['autocomplete'])) $field_autocomplete = ' autocomplete="'.$args['placeholder'].'"'; else $field_autocomplete = ' autocomplete="off"';
	$output = '<input type="text" class="dateselector" value="'.stripslashes($value).'" name="'.$name.'" id="'.$name.'"'.$field_placeholder.$field_autocomplete.'/>';
	?><script>
	jQuery(function(){
		jQuery(".dateselector").datepicker({dateFormat: 'yy-mm-dd'});
	});
	</script><?php
	return $output;
}
?>