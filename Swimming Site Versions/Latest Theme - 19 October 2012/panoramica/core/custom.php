<?php

//Adds the admin custom pages for Theme Settings, SEO and Update
add_action('admin_menu', 'cpotheme_custom');
function cpotheme_custom(){
	//Set up data to add menus
	add_theme_page(__('Theme Options', 'cpotheme'), __('Theme Options', 'cpotheme'), 'edit_theme_options', 'cpotheme_settings', 'cpotheme_settings');
	//add_theme_page(__('SEO Settings', 'cpotheme'), __('SEO Settings', 'cpotheme'), 'edit_theme_options', 'cpotheme_seo', 'cpotheme_seo');	
	//add_theme_page('cpotheme_settings', __('Update Theme', 'cpotheme'), __('Update Theme', 'cpotheme'), 'manage_options', 'cpotheme_update', 'cpotheme_update');
}

//Build Settings Form
function cpotheme_custom_form($option_name, $option_list){ ?>
	<div class="wrap">
		<div class="icon32" id="icon-themes"></div>
		<h2><?php echo get_admin_page_title(); ?></h2>
		
		<div id="settingsmenu">
			<?php cpotheme_custom_nav($option_list); ?>
		</div>

		<?php if(isset($_GET['ok'])): ?>
		<div id="message" class="updated">
			<p><strong><?php _e('Changes have been saved.', 'cpotheme'); ?></strong></p>
		</div>
		<?php endif; ?>
		<?php if(isset($_GET['error'])): ?>
		<div id="message" class="error">
			<p><strong><?php _e('Changes could not be saved.', 'cpotheme'); ?></strong></p>
		</div>
		<?php endif; ?> 

		<form name="cpotheme_custom_form" method="post" action="admin.php?page=<?php echo $_GET['page']; ?>" enctype="multipart/form-data">
            <input type="hidden" name="cpotheme_custom_action" value="<?php echo $option_name; ?>" />
            <?php cpotheme_custom_fields($option_list, $option_name); ?>
            <?php if(function_exists('wp_nonce_field')) wp_nonce_field('cpotheme_nonce'); ?>
		</form>
	</div>
<?php }

//Create navigation menu for settings page
function cpotheme_custom_nav($options){
	$field_list = $options;
	$output = '<ul>';
    $output .= '<li class="logo">&nbsp;</li>';
	$nav_count = 0;
	foreach($field_list as $current_field){
		if($current_field['type'] == 'separator'){
			$field_id = $current_field["id"];
			$field_name = $current_field["name"];
			$field_desc = $current_field['desc'];
			$output .= '<li id="'.$field_id.'" title="'.$field_desc.'" class="settingsmenu_element';
			if($nav_count == 0) $output .= ' active';
			$output .= '">'.$field_name.'</li>';
			$nav_count++;
		}
	}
	$output .= '</ul>';
	$output .= '<div class="support">';
	$output .= '<a href="http://www.cpothemes.com">'.__('Support', 'cpotheme').'</a>';
	//$output .= '<a href="http://www.cpo.es">Theme Documentation</a>';
	$output .= '</div>';
	echo $output;
}

//Display the options forms fields
function cpotheme_custom_fields($cpo_options, $list_name){    
	$output = '';
	$tab_count = 0;
	
	$option_list = get_option($list_name, false);
   
    foreach($cpo_options as $current_field){
    	
		//Set common attributes for each element
		$field_name = $current_field['id'];
		$field_title = $current_field['name'];
		$field_desc = $current_field['desc'];
		$field_type = $current_field['type'];
		
		$field_value = '';
		//$field_value = get_option($field_name);
		if($option_list && isset($option_list[$field_name])) $field_value = $option_list[$field_name];
		
		if($current_field['type'] != 'separator'){
			$output .= '<div class="item">';
			$output .= '<div class="title">'.$field_title.'</div>';
			$output .= '<div class="value">';
		
		// Field separator. No actual data.
		}else{
			if($tab_count > 0):
				$output .= '<input class="cposettings_submit button-primary" type="submit" name="cpotheme_settings_save" value="'.__('Save Settings', 'cpotheme').'" />';
				$output .= '</div>';
			endif;
			$output .= '<div class="cposettings_block" id="'.$field_name.'_block"';
			if($tab_count > 0) $output .= ' style="display:none;"';
			$output .= '>';
			$output .= '<input class="cposettings_submit button-primary" type="submit" name="cpotheme_settings_save" value="'.__('Save Settings', 'cpotheme').'" />';
			$output .= '<div class="cposettings_separator">';
			$output .= $field_title.'<br/><span class="desc">'.$field_desc.'</span>';
			$output .= '</div>';
			$tab_count++;
		}
		
		if($field_type == 'text')
			$output .= cpotheme_form_text($field_name, $field_value, $current_field);
		
		elseif($field_type == 'textarea')
			$output .= cpotheme_form_textarea($field_name, $field_value, $current_field);
		
		elseif($field_type == 'select')
			$output .= cpotheme_form_select($field_name, $field_value, $current_field['option'], $current_field);
		
		elseif($field_type == 'checkbox')
			$output .= cpotheme_form_checkbox($field_name, $field_value, $current_field);
		
		elseif($field_type == 'yesno')
			$output .= cpotheme_form_yesno($field_name, $field_value, $current_field);
		
		elseif($field_type == 'color')
			$output .= cpotheme_form_color($field_name, $field_value);
        
        elseif($field_type == 'imagelist')
            $output .= cpotheme_form_imagelist($field_name, $field_value, $current_field['option'], $current_field);
			
        elseif($field_type == 'upload') 
            $output .= cpotheme_form_upload($field_name, $field_value);
                
		//Separator
		if($field_type != 'separator'){
			$output .= '</div><div class="desc">'.__($field_desc, 'cpotheme').'</div>';
			$output .= '</div>';
		}
		unset($current_field);
    }
	$output .= '<input class="cposettings_submit button-primary" type="submit" name="cpotheme_settings_save" value="'.__('Save Settings', 'cpotheme').'" />';
    $output .= '</div>';
    echo $output;
}

//Save all settings upon submitting the settings form
function cpotheme_custom_save($option_name, $option_fields){
	//Check if we're submitting a custom page
    if(isset($_POST['cpotheme_custom_action']) && $_POST['cpotheme_custom_action'] == $option_name){
		if(!wp_verify_nonce($_POST['_wpnonce'], 'cpotheme_nonce')) header("Location: admin.php?page=".$_GET['page']."&error");

		//Get the option array, then update the array values
		$count = 0;
		$options_list = get_option($option_name, false);
		foreach($option_fields as $current_option){
			$count++;
			$field_id = $current_option["id"];
				
			//If the field has an update, process it.
			if(isset($_POST[$field_id])){
				$field_value = '';
				$field_value = trim($_POST[$field_id]);

				$current_value = '';
				if(isset($options_list[$field_id]))
					$current_value = $options_list[$field_id];
				
				// Add option
				if($current_value == '' && $field_value != ''){
					$options_list[$field_id] = $field_value;
				}
				// Update option
				elseif($field_value != $current_value){
					$options_list[$field_id] = $field_value;
				}
				// Delete unused option
				elseif($field_value == ''){
					//TODO: Check default values
					$options_list[$field_id] = $field_value;
				}
			}
		}
		update_option($option_name, $options_list);
		
		header("Location: admin.php?page=".$_GET['page']."&ok");
	}
}

?>