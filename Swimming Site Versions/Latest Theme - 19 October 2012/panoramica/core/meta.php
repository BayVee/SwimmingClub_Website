<?php
// Prints meta field HTML
function cpotheme_meta_fields($post, $cpo_metadata = null){
	if($cpo_metadata == null || sizeof($cpo_metadata) == 0) return;
    $output = '';
	
    foreach($cpo_metadata as $current_meta){
		$field_name = $current_meta["name"];
		$field_title = $current_meta['label'];
		$field_desc = $current_meta['desc'];
		$field_type = $current_meta['type'];
		$field_value = '';
		$field_value = get_post_meta($post->ID, $field_name, true);
		
		$output .= '<div class="cpometabox"><div class="name">'.$field_title.'</div>';
		$output .= '<div class="field">';
		
		// Print metaboxes here. Develop different cases for each type of field.
		if($field_type == 'text')
			$output .= cpotheme_form_text($field_name, $field_value, $current_meta);
		
		elseif($field_type == 'textarea')
			$output .= cpotheme_form_textarea($field_name, $field_value, $current_meta);
		
		elseif($field_type == 'select')
			$output .= cpotheme_form_select($field_name, $field_value, $current_meta['option'], $current_meta);
		
		elseif($field_type == 'checkbox')
			$output .= cpotheme_form_checkbox($field_name, $field_value, $current_meta);
		
		elseif($field_type == 'yesno')
			$output .= cpotheme_form_yesno($field_name, $field_value, $current_meta);
		
		elseif($field_type == 'color')
			$output .= cpotheme_form_color($field_name, $field_value);
		        
        elseif($field_type == 'imagelist')
            $output .= cpotheme_form_imagelist($field_name, $field_value, $current_field['option'], $current_field);
			
        elseif($field_type == 'upload') 
            $output .= cpotheme_form_upload($field_name, $field_value, null, $post);
			
        elseif($field_type == 'date') 
            $output .= cpotheme_form_date($field_name, $field_value, null);
			
		$output .= '</div>';
		$output .= '<div class="desc">'.$field_desc.'</div></div>';
    }
    echo $output;
}

// Saves meta field data into database
function cpotheme_meta_save($option){

	if(!isset($_POST['post_ID'])) return;
    
	$cpo_metaboxes = $option;
	$post_id = $_POST['post_ID'];
		
	//Check if we're editing a post
    if(isset($_POST['action']) && $_POST['action'] == 'editpost'){                                   
		
		//Check every option, and process the ones there's an update for.
		if(sizeof($cpo_metaboxes) > 0)
		foreach ($cpo_metaboxes as $current_meta){
           
			$field_name = $current_meta["name"];
			
			//If the field has an update, process it.
			if(isset($_POST[$field_name])){
			
				$field_value = '';
				$field_value = trim(mysql_real_escape_string($_POST[$field_name]));
				
				$current_value = '';
				$current_value = get_post_meta($post_id, $field_name, true);
				
				// Add metadata
				if(get_post_meta($post_id, $field_name) == ""){
					add_post_meta($post_id, $field_name, $field_value, true); 
				}
				// Update metadata
				elseif($field_value != get_post_meta($post_id, $field_name, true)){ 
					update_post_meta($post_id, $field_name, $field_value);
				}
				// Delete unused metadata
				elseif($field_value == ""){ 
					delete_post_meta($post_id, $field_name, get_post_meta($post_id, $field_name, true));
				}
			}
		}
	}
}