<?php

//Displays the SEO Settings page
function cpotheme_seo(){
	cpotheme_custom_form('cpotheme_seo', cpotheme_seo_settings_data());
}

//Saves the SEO Settings page data
add_action('admin_menu', 'cpotheme_seo_savesettings');
function cpotheme_seo_savesettings(){
	cpotheme_custom_save('cpotheme_seo', cpotheme_seo_settings_data());
}

//Create SEO metaboxes in posts and pages
add_action('admin_menu', 'cpotheme_seo_metaboxes');
function cpotheme_seo_metaboxes(){
    if(cpotheme_get_option('cpo_seo_global')):
        add_meta_box('cpotheme_settings', __('SEO Meta Tags', 'cpotheme'), 'cpotheme_seo_metafields', 'post', 'normal', 'high');
        add_meta_box('cpotheme_settings', __('SEO Meta Tags', 'cpotheme'), 'cpotheme_seo_metafields', 'page', 'normal', 'high');
        
		//Add support for custom post types
        $args = array('public' => true, '_builtin' => false);
        $post_types = get_post_types($args); 
        foreach($post_types  as $post_type)
			add_meta_box('cpotheme_settings', __('SEO Meta Tags', 'cpotheme'), 'cpotheme_seo_metafields', $post_type, 'normal', 'high');
    endif;
}

//Display SEO metabox contents
function cpotheme_seo_metafields($post) {
	  cpotheme_meta_fields($post, cpotheme_seo_metadata());
}

//Save SEO page & post metadata
add_action('edit_post', 'cpotheme_seo_savemeta');
function cpotheme_seo_savemeta() {
    if(cpotheme_get_option('cpo_seo_global')){        
        cpotheme_meta_save(cpotheme_seo_metadata());
    }    
}

//Declares all seo config data to be used in the SEO Settings page
function cpotheme_seo_settings_data(){
	$cpotheme_config = array();
	$prefix = 'cpo_';
	
	//SEO PAGE
	$cpotheme_config[] = array(
	'id' => 'seo_global_config',
	'name' => __('SEO Options', 'cpotheme'),
	'desc' => __('Site wide SEO settings.', 'cpotheme'),
	'type' => 'separator');
	
	$cpotheme_config[] = array(
	'id' => $prefix.'seo_global',
	'name' => __('Activate SEO', 'cpotheme'),
	'desc' => __('Toggles SEO options globally. If you wish to use other plugins, turn this setting off to avoid conflicts.', 'cpotheme'),
	'type' => 'yesno');
		
	$cpotheme_config[] = array(
	'id' => $prefix.'title_separator',
	'name' => __('Title Separator', 'cpotheme'),
	'desc' => __('Determines the separator in the title tag. If empty, defaults to the standard separator ( | ).', 'cpotheme'),
	'width' => '50px',
	'type' => 'text');
	
	$cpotheme_config[] = array(
	'id' => $prefix.'default_description',
	'name' => __('Default Description', 'cpotheme'),
	'desc' => __('This field will be used by default in posts and pages lacking a custom description.', 'cpotheme'),
	'type' => 'textarea');
	
	$cpotheme_config[] = array(
	'id' => $prefix.'default_keywords',
	'name' => __('Default Keywords', 'cpotheme'),
	'desc' => __('This field will be used by default in posts and pages lacking custom keywords.', 'cpotheme'),
	'type' => 'text');
	
	$cpotheme_config[] = array(
	'id' => $prefix.'general_title',
	'name' => __('Homepage Title', 'cpotheme'),
	'desc' => __('Specifies a custom title for the home page. If empty, the standard title formatting will be used.', 'cpotheme'),
	'type' => 'text');
	
	$cpotheme_config[] = array(
	'id' => $prefix.'general_description',
	'name' => __('Homepage Description', 'cpotheme'),
	'desc' => __('This field will be shown in the description meta tag in the homepage.', 'cpotheme'),
	'type' => 'textarea');
	
	$cpotheme_config[] = array(
	'id' => $prefix.'general_keywords',
	'name' => __('Homepage Keywords', 'cpotheme'),
	'desc' => __('Specify the keywords that best define your site, comma-separated. This field will be shown in the keywords meta tag in the homepage.', 'cpotheme'),
	'type' => 'text');
	
			
	return $cpotheme_config;
}

//Declares all seo metabox data.
function cpotheme_seo_metadata(){
	$cpotheme_data = array();
	
	$cpotheme_data[] = array(
	"name" => "seo_title",
	"default"  => "",
	"label" => __("Custom Title", 'cpotheme'),
	"type" => "text",
	"desc" => __('Creates a custom title for this post/page. If empty, standard title formatting will be used instead.', 'cpotheme'));

	$cpotheme_data[] = array(
	"name" => "seo_description",
	"default"  => "",
	"label" => __("Custom Description", 'cpotheme'),
	"type" => "textarea",
	"desc" => __("Creates a custom description tag for this post/page.", 'cpotheme'));
	
	$cpotheme_data[] = array(
	"name" => "seo_keywords",
	"default"  => "",
	"label" => __("Custom Keywords", 'cpotheme'),
	"type" => "text",
	"desc" => __("Creates a custom keywords tag for this post/page. Keywords must be comma-separated.", 'cpotheme'));
    
	return $cpotheme_data;
} ?>