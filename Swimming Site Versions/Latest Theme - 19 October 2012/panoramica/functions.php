<?php //CPO Framework for Wordpress

add_theme_support('post-thumbnails');
add_theme_support('automatic-feed-links');
if(!isset($content_width)) $content_width = 960;	

//Load Core; check existing core or load development core
if(defined('WP_CPODEV'))
	require_once(get_template_directory().'/../cpoframework/core/init.php');
else
	require_once(get_template_directory().'/core/init.php');

$include_path = get_template_directory().'/includes/';

//Main components
require_once($include_path.'setup.php');

//Metadata & variables
require_once($include_path.'metadata/data_general.php');
require_once($include_path.'metadata/data_metaboxes.php');
require_once($include_path.'metadata/data_settings.php');

//Layout & Display components
require_once($include_path.'layout/layout_login.php');
require_once($include_path.'layout/layout_post.php');
require_once($include_path.'layout/layout_breadcrumbs.php');
require_once($include_path.'layout/layout_comments.php');

//Custom posts
require_once($include_path.'cposts/cpost_slider.php');
require_once($include_path.'cposts/cpost_features.php');
require_once($include_path.'cposts/cpost_portfolio.php');

//Custom taxonomies
require_once($include_path.'taxonomies/tax_portfolio_cats.php');

?>