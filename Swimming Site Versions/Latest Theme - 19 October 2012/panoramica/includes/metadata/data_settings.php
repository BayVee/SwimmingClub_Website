<?php // Options declaration
// Declares all config data to be used in the theme settings page.
function cpotheme_metadata_settings(){
	$cpotheme_config = array();
	$prefix = 'cpo_';


	//General Config
	$cpotheme_config[] = array(
	'id' => $prefix.'general_config',
	'name' => __('General Options', 'cpotheme'),
	'desc' => __('Global configuration options applied to the entire site.', 'cpotheme'),
	'type' => 'separator');

	$cpotheme_config[] = array(
	'id' => $prefix.'general_logo',
	'name' => __('Custom Logo', 'cpotheme'),
	'desc' => __('Insert a custom image to be used as logo.', 'cpotheme'),
	'type' => 'text');

	$cpotheme_config[] = array(
	'id' => $prefix.'general_texttitle',
	'name' => __('Enable Text Title?', 'cpotheme'),
	'desc' => __('Activate this to display the site title as text.', 'cpotheme'),
	'type' => 'yesno');

	$cpotheme_config[] = array(
	'id' => $prefix.'general_analytics',
	'name' => __('Analytics Tracking Code', 'cpotheme'),
	'desc' => __('Insert here your analytics tool\'s tracking code.', 'cpotheme'),
	'type' => 'textarea');

	//Styling
	$cpotheme_config[] = array(
	'id' => $prefix.'styling_config',
	'name' => __('Styling', 'cpotheme'),
	'desc' => __('Set up the look & feel of the site.', 'cpotheme'),
	'type' => 'separator');

	$cpotheme_config[] = array(
	'id' => $prefix.'home_limit',
	'name' => __('Number of Posts in Homepage', 'cpotheme'),
	'desc' => __('Specify the number of recent posts you want to appear in the homepage.', 'cpotheme'),
	'width'  => '50px',
	'type' => 'text');
	
	$cpotheme_config[] = array(
	'id' => $prefix.'slider_always',
	'name' => __('Always Display Slider?', 'cpotheme'),
	'desc' => __('Determines whether the slider will be shown in all posts and pages, or just the homepage.', 'cpotheme'),
	'type' => 'yesno');
	
	$cpotheme_config[] = array(
	'id' => $prefix.'home_tagline',
	'name' => __('Homepage Tagline', 'cpotheme'),
	'desc' => __('Displays a tagline located under the slider in the homepage. You can use HTML to stylize your tagline.', 'cpotheme'),
	'type' => 'textarea');
	
	$cpotheme_config[] = array(
	'id' => $prefix.'home_portfolio',
	'name' => __('Homepage Portfolio Description', 'cpotheme'),
	'desc' => __('Displays a small description in the portfolio section of the homepage. You can use HTML to stylize the description.', 'cpotheme'),
	'type' => 'textarea');
	
	$cpotheme_config[] = array(
	'id' => $prefix.'bg_color',
	'name' => __('Background Color', 'cpotheme'),
	'desc' => __('Determines the color of the background.', 'cpotheme'),
	'type' => 'color');
	
	$cpotheme_config[] = array(
	'id' => $prefix.'bg_texture',
	'name' => __('Background Texture', 'cpotheme'),
	'desc' => __('Specifies how the sidebar is arranged throughout the whole site.', 'cpotheme'),
	'std'  => '',
	'type' => 'imagelist',
	'option' => cpotheme_metadata_backgroundtexture());
	
	$cpotheme_config[] = array(
	'id' => $prefix.'general_credit',
	'name' => __('Enable Credit Link In Footer?', 'cpotheme'),
	'desc' => __('Enables a small, non-obtrusive credit link in the footer. If you decide to activate it, thanks a lot for supporting CPOThemes!', 'cpotheme'),
	'type' => 'yesno');

	//Contact Config
	$cpotheme_config[] = array(
	'id' => $prefix.'contact_config',
	'name' => __('Contact Information', 'cpotheme'),
	'desc' => __('Setup for contact information used in forms.', 'cpotheme'),
	'type' => 'separator');

	$cpotheme_config[] = array(
	'id' => $prefix.'contact_email',
	'name' => __('Contact Form Email', 'cpotheme'),
	'desc' => __('Entries in the contact form template will be sent to this email address.', 'cpotheme'),
	'type' => 'text');
	
	if(function_exists('cpotheme_seo_settings_data')) $cpotheme_config = array_merge($cpotheme_config, cpotheme_seo_settings_data());
	
	return $cpotheme_config;
}