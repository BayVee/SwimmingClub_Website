<?php
/**
 * Agility functions and definitions
 *
 * @package Agility
 * @since Agility 1.0
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @since Agility 1.0
 */
if ( ! isset( $content_width ) )
	$content_width = 640; /* pixels */


if ( ! function_exists( 'agility_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * @since Agility 1.0
 */
function agility_setup() {

	/**
	 * Custom template tags for this theme.
	 */
	require( get_template_directory() . '/inc/template-tags.php' );

	/**
	 * Custom widgets
	 */
	require( get_template_directory() . '/inc/widgets.php' );


	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on Agility, use a find and replace
	 * to change 'agility' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'agility', get_template_directory() . '/languages' );

	$locale = get_locale();
	$locale_file = get_template_directory() . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );

	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );

	/**
	 * This theme uses wp_nav_menu() in one location.
	 */
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'agility' ),
	) );

	/**
	 * Add support for the Aside and Gallery Post Formats
	 */
	add_theme_support( 'post-formats', array( 'image', 'video', 'gallery', 'status', 'quote'  ) );
	
	/**
	 * Add support for post thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	/**
	 * Add Image sizes based on Control Panel Settings
	 */
	global $agilitySettings;

	add_image_size( 'natural_940', 940 );
	add_image_size( 'natural_640', 640 );

	$ratio_w = $agilitySettings->op( 'crop-ratio-w' );
	$ratio_h = $agilitySettings->op( 'crop-ratio-h' );

	agility_register_crop_size( 940 , $ratio_w , $ratio_h );
	agility_register_crop_size( 640 , $ratio_w , $ratio_h );
	//agility_register_crop_size( 420 , $ratio_w , $ratio_h );

	//Initialize Post Types
	$GLOBALS['cpt_portfolio'] = new PortfolioPostType();
	$GLOBALS['cpt_slide'] = new SlidePostType();
	$GLOBALS['cpt_query'] = new QueryPostType();

	//Initialize Meta Boxes
	$GLOBALS['cpt_post_settings_metabox_post'] 	= new PostSettingsMetaBox( 'post_settings', __( 'Post Settings', 'agility' ), 'post', 'side', 'default' );	
	$GLOBALS['cpt_page_settings_metabox_post'] 	= new PageSettingsMetaBox( 'page_settings', __( 'Page Settings', 'agility') , 'page', 'side', 'default' );
	$GLOBALS['cpt_bricklayer_metabox'] 			= new BrickLayerMetaBox( 'bricklayer_settings', 'BrickLayer', 'page', 'side', 'default' );
	$GLOBALS['cpt_portfolio_metabox'] 			= new PortfolioMetaBox( 'portfolio_settings', __( 'Portfolio Settings', 'agility') , 'page', 'side', 'default' );

	if( $agilitySettings->op( 'self-hosted-video' ) ) {
		$GLOBALS['cpt_shvideo_metabox_post'] 	= new SHVideoSettingsMetaBox( 'shvideo_settings', __( 'Self-Hosted Video', 'agility') , 'post', 'side', 'low' );	
		$GLOBALS['cpt_shvideo_metabox_portfolio'] = new SHVideoSettingsMetaBox( 'shvideo_settings', __( 'Self-Hosted Video', 'agility') , 'portfolio-item', 'side', 'low' );
	}	

	//Add support for styling the WYSIWIG editor
	add_theme_support('editor_style');
	add_editor_style( array(  'stylesheets/editor_styles.css' )  );


	//UberMenu-specific
	if( function_exists( 'uberMenu_easyIntegrate' ) ){
		add_filter( 'wp_nav_menu_args' , 'agility_megaMenuFilter', 2100 );  	//filters arguments passed to wp_nav_menu
		//global $uberMenu; $uberMenu->ops['wpmega-menubar-full']['default'] = 'off';
	}

}
endif; // agility_setup
add_action( 'after_setup_theme', 'agility_setup' );

/**
 * Calculates the appropriate dimensions for an image size based on
 * desired aspect ratio
 */
function agility_register_crop_size( $crop_w , $ratio_w , $ratio_h ){
	$crop_h = (int) ( ( $ratio_h / $ratio_w ) * $crop_w );
	add_image_size( 'crop_'.$crop_w, $crop_w, $crop_h, true );
}

/**
 * Register widgetized area and update sidebar with default widgets
 *
 * @since Agility 1.0
 */
function agility_widgets_init() {

	register_sidebar( array(
		'name' => __( 'Sidebar', 'agility' ),
		'id' => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
		'agility' => array(
			'bricks'		=>	true,
			'custom_side'	=>	true,
			'wrap_cols'		=> 	true,
		)
	) );

	register_sidebar( array(
		'name' => __( 'Drop Container', 'agility' ),
		'id' => 'drop-container',
		'before_widget' => '<div id="%1$s" class="widget %2$s cf nobottom">',
		'after_widget' => "</div>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
		'agility' => array(
			'bricks'		=>	false,
			'custom_side'	=>	false,
			'wrap_cols'		=> 	false,
		)
	) );


	register_sidebar( array(
		'name' => __( 'Home Template - Lower Area', 'agility' ),
		'id' => 'home-template',
		'before_widget' => '<aside id="%1$s" class="eight columns widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h6 class="widget-title">',
		'after_title' => '</h6>',
		'agility' => array(
			'bricks'		=>	false,
			'custom_side'	=>	false,
			'wrap_cols'		=> 	false,
		)
	) );

	register_sidebar( array(
		'name' => __( 'After Post', 'agility' ),
		'id' => 'after-post',
		'before_widget' => '<aside id="%1$s" class="widget %2$s single-post-extra cf">',
		'after_widget' => "</aside>",
		'before_title' => '<h6 class="widget-title">',
		'after_title' => '</h6>',
		'agility' => array(
			'bricks'		=>	true,
			'custom_side'	=>	false,
			'wrap_cols'		=> 	true,
		)
	) );

	

	register_sidebar( array(
		'name' => __( 'Footer Left', 'agility' ),
		'id' => 'footer-left',
		'before_widget' => '<aside id="%1$s" class="widget %2$s cf">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
		'agility' => array(
			'bricks'		=>	false,
			'custom_side'	=>	false,
			'wrap_cols'		=> 	true,
		)
	) );

	register_sidebar( array(
		'name' => __( 'Footer Center', 'agility' ),
		'id' => 'footer-center',
		'before_widget' => '<aside id="%1$s" class="widget %2$s cf">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
		'agility' => array(
			'bricks'		=>	false,
			'custom_side'	=>	false,
			'wrap_cols'		=> 	true,
		)
	) );

	register_sidebar( array(
		'name' => __( 'Footer Right', 'agility' ),
		'id' => 'footer-right',
		'before_widget' => '<aside id="%1$s" class="widget %2$s cf">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
		'agility' => array(
			'bricks'		=>	false,
			'custom_side'	=>	false,
			'wrap_cols'		=> 	true,
		)
	) );


	/* Custom Sidebars */
	global $agilitySettings;
	$numSidebars = $agilitySettings->op( 'custom-sidebars' );

	if(!empty($numSidebars)){
		if($numSidebars == 1){
			register_sidebar(array(
				'name'          => __('Agility Custom Widget Area 1', 'agility'),
				'id'            => 'agility-custom-sidebar',
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget' 	=> "</aside>",
				'before_title' 	=> '<h1 class="widget-title">',
				'after_title' 	=> '</h1>',
				'description'	=> 'This widget can replace the sidebar on a particular page, or be used in a BrickLayer layout',
				'agility' => array(
					'bricks'		=>	true,
					'custom_side'	=>	true,
					'wrap_cols'		=> 	true,
				)
			));				
		}
		else{
			register_sidebars( $numSidebars, array(
				'name'          => __('Agility Custom Widget Area %d', 'agility'),
				'id'            => 'agility-custom-sidebar',
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget' 	=> "</aside>",
				'before_title' 	=> '<h6 class="widget-title">',
				'after_title' 	=> '</h6>',
				'description'	=> 'This widget can replace the sidebar on a particular page, or be used in a BrickLayer layout',
				'agility' => array(
					'bricks'		=>	true,
					'custom_side'	=>	true,
					'wrap_cols'		=> 	true,
				)
			));
		}
	}
}
add_action( 'widgets_init', 'agility_widgets_init' );

/**
 * Retrieve a list of registered sidebars for a specific group
 */
function agility_sidebar_ops( $for = 'custom_side' ){
	global $wp_registered_sidebars;
	$sidebars = array();

	foreach( $wp_registered_sidebars as $sidebar_id => $sidebar ){

		$name = $sidebar['name'];

		$valid = false;
		if( !$for ) $valid = true;
		else{
			if( isset( $sidebar['agility'] ) ){
				$agops = $sidebar['agility'];

				switch( $for ){

					case 'custom_side':
						if( $agops['custom_side'] ) $valid = true;
						break;

					case 'bricks':
						if( $agops['bricks'] ) $valid = true;
						break;

				}
			}
		}

		if( $valid ) $sidebars[$sidebar_id] = $name;
		if( $name == 'Sidebar' ) $sidebars[$sidebar_id] = $name . ' (Default)';
	}
	//ssd( $sidebars );
	return $sidebars;
}

/**
 * Enqueue scripts and styles
 */
function agility_scripts_and_styles() {
	global $post, $agilitySettings;

	$dir = get_template_directory_uri().'/';

	//Stylesheets
	wp_enqueue_style( 'base', 		$dir.'stylesheets/base.css' );
	wp_enqueue_style( 'skeleton', 	$dir.'stylesheets/skeleton.css' );
	wp_enqueue_style( 'layout', 	$dir.'stylesheets/layout.css' );
	wp_enqueue_style( 'prettyPhoto',$dir.'js/prettyPhoto/css/prettyPhoto.css' );
	wp_enqueue_style( 'style', 		get_stylesheet_uri() );	//enqueue last so we can override
	
	//Fonts
	wp_enqueue_style( 'font-open-sans', 'http://fonts.googleapis.com/css?family=Open+Sans:400italic,400,700' );
	wp_enqueue_style( 'font-droid-serif', 'http://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic' );
	

	//Javascript	
	wp_enqueue_script( 'modernizr', $dir.'js/modernizr.js', array(), false, false );
	wp_enqueue_script( 'jquery' );
	//wp_enqueue_script( 'tabs' , $dir.'js/tabs.js' ); //Now incorporated into agility.js
	wp_enqueue_script( 'prettyPhoto', $dir.'js/prettyPhoto/js/jquery.prettyPhoto.js', array( 'jquery' ), false, true );
	wp_enqueue_script( 'isotope', $dir.'js/jquery.isotope.min.js', array( 'jquery' ), false, true );
	wp_enqueue_script( 'agility-js', $dir.'js/agility.js', array( 'jquery' ), false, true );
	wp_enqueue_script( 'google-maps', 'http://maps.googleapis.com/maps/api/js?sensor=false', array(), false, true );
	wp_enqueue_script( 'twitter-js', 'http://twitterjs.googlecode.com/svn/trunk/src/twitter.min.js', array(), false, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image( $post->ID ) ) {
		wp_enqueue_script( 'keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
	}

	//Load JavaScript Settings
	wp_localize_script( 'agility-js', 'agilitySettings', array(
		'prettyPhoto_default_width'	=>	$agilitySettings->op( 'prettyPhoto_default_width' ),
		'slideshowSpeed'			=>	$agilitySettings->op( 'slider-speed' ),
		'animationSpeed'			=>	$agilitySettings->op( 'slider-animation-speed' ),
		'animation'					=>	$agilitySettings->op( 'slider-animation' ),
		'autoplay'					=>	$agilitySettings->op( 'slider-autoplay' ),
	));


}
add_action( 'wp_enqueue_scripts', 'agility_scripts_and_styles' );

function agility_insert_custom_css(){

	global $agilitySettings;
	$custom = $agilitySettings->op( 'custom_css' );

	if( $custom ){
		?>

<!-- Agility Custom CSS Tweaks - Controlled through Agility Control Panel 
================================================================ -->
<style type="text/css" id="agility-custom-css">
<?php echo $custom; ?>
	
</style>
<!-- end Agility Custom CSS Tweaks -->
		
			<?php
	}

}
add_action( 'wp_head', 'agility_insert_custom_css' );




if ( ! function_exists( 'ssd' ) ):
/**
 * Print function for testing
 */
function ssd($d){
	echo '<pre style="clear:both">';
	print_r($d);
	echo '</pre>';
}
endif;


add_filter('the_content', 'agility_remove_bad_br_tags');
function agility_remove_bad_br_tags($content) {
	$content = str_ireplace("<br />\n<input", "<input", $content);
	$content = str_ireplace("<br />\n<label", "<label", $content);
	return $content;
}


function agility_excerpt_more($more) {
	return '...';
}
add_filter( 'excerpt_more', 'agility_excerpt_more' );


/**
 * Add Vimeo's https to OEmbed providers
 */
function agility_oembed_providers( $providers ){
	/* Allow https:// to be used with Vimeo */
	$providers ['#https://(www\.)?vimeo\.com/.*#i'] = array( 'http://vimeo.com/api/oembed.{format}', true  );

	return $providers;
}
add_filter( 'oembed_providers', 'agility_oembed_providers' );



function agility_image_send_to_editor_filter($html, $id, $caption, $title, $align, $url, $size, $alt){

	$html = get_image_tag($id, $alt, $title, $align.' scale-with-grid', $size);

	list( $img_src, $width, $height ) = image_downsize( $id, $size );

	$rel = $rel ? ' rel="attachment wp-att-' . esc_attr($id).'"' : '';

	if ( $url ){
		if( $caption ){	
			$html = '<a class="img-prettyPhoto" data-rel="prettyPhoto" title="'.$title.'" href="' . esc_attr($url) . "\"$rel>$html</a>";
		}
		else{
			$html = '<a style="width:'.$width.'px; max-height:'.$height.'px; max-width:100%;" class="img-prettyPhoto align'.$align.'" data-rel="prettyPhoto" title="'.$title.'" href="' . esc_attr($url) . "\"$rel>$html</a>";
		}
	}

	return $html;

}
add_filter('image_send_to_editor', 'agility_image_send_to_editor_filter', 10, 8);


/**
 * Get the string version of 1/$n
 */
function agility_one_over( $n ){
	$convert = array(

		'2'		=>	'half',
		'3'		=>	'third',
		'4'		=>	'fourth',
		'5'		=>	'fifth',
		'6'		=>	'sixth',
		'7'		=>	'seventh',
		'8'		=>	'eighth',
		'9'		=>	'ninth',
		'10'	=>	'tenth'
	);

	return isset( $convert[$n] ) ? $convert[$n] : $n;
}


/**
 * Get the maximum pixel width based on the number of grid columns
 */
function agility_max_pixel_width( $columns ){
	$convert = array(

		'1'		=> 420,
		'2'		=> 420,
		'3'		=> 420,
		'4'		=> 420,
		'5'		=> 420,
		'6'		=> 420,
		'7'		=> 420,
		'8'		=> 460,
		'9'		=> 520,
		'10'	=> 580,
		'11'	=> 640,
		'12'	=> 700,
		'13'	=> 760,
		'14'	=> 820,
		'15'	=> 880,
		'16'	=> 940,

		'1-4'	=> 420,
		'1-3'	=> 420,
		'1-2'	=> 460,
		'2-3'	=> 620,
		'3-4'	=> 700,

		'f'		=> 940,
		'natural' => 940,
	);

	if( !isset( $convert[$columns] ) ) return 940;

	return $convert[$columns];
}

/**
 * Convert a numeric number of columns to string class
 */
function agility_grid_columns_class( $num_cols , $include_columns = true, $offset = '' ){

	$convert = array(

		'1'		=> 'one',
		'2'		=> 'two',
		'3'		=> 'three',
		'4'		=> 'four',
		'5'		=> 'five',
		'6'		=> 'six',
		'7'		=> 'seven',
		'8'		=> 'eight',
		'9'		=> 'nine',
		'10'	=> 'ten',
		'11'	=> 'eleven',
		'12'	=> 'twelve',
		'13'	=> 'thirteen',
		'14'	=> 'fourteen',
		'15'	=> 'fifteen',
		'16'	=> 'sixteen',

		'1-4'	=> 'one-fourth',
		'1-3'	=> 'one-third',
		'1-2'	=> 'one-half',
		'2-3'	=> 'two-thirds',
		'3-4'	=> 'three-fourths',

		'f'		=> 'full-width',
		'natural' => '',
	);

	$grid_cols = $convert[$num_cols];

	if( $offset && isset( $convert[$offset] ) ){
		$grid_cols.= " offset-by-$offset";
	}

	if( $include_columns ){
		$grid_cols .= agility_grid_columns_plural( $num_cols );
	}

	return $grid_cols;

}

/**
 * Determine the appropriate class, 'columns' or 'column' for the Skeleton framework
 */
function agility_grid_columns_plural( $num_cols ){
	
	switch( $num_cols ){

		
		case '2':
		case '3':
		case '4':
		case '5':
		case '6':
		case '7':
		case '8':
		case '9':
		case '10':
		case '11':
		case '12':
		case '13':
		case '14':
		case '15':
		case '16':
		case 'f':
		
		case 'two':
		case 'three':
		case 'four':
		case 'five':
		case 'six':
		case 'seven':
		case 'eight':
		case 'nine':
		case 'ten':
		case 'eleven':
		case 'twelve':
		case 'thirteen':
		case 'fourteen':
		case 'fifteen':
		case 'sixteen':

			return ' columns';

		case '1':
		case '1-4':
		case '1-3':
		case '1-2':
		case '2-3':
		case '3-4':
		case 'f':

		case 'one':
		case 'one-fourth':
		case 'one-third':
		case 'one-half':
		case 'two-thirds':
		case 'three-fourths':

			return ' column';

	}
}

/**
 * @param cols 			integer, number of grid columns of an element
 * @param container 	integer, number of grid columns in the wrapper
 *
 */
function agility_divide_columns( $cols , $container = 11, $default = 1 ){

	if( !is_numeric( $cols ) ) {

		if( $cols == 'f' ){
			return 1;
		}

		if( $fraction = agility_convert_fraction( $cols ) ){

			switch( $container ){

				case 16:
				case 11:
					return substr( $cols , 2 );

			}
			if( $container < 11 ){
				$cols_11 = substr( $cols , 2 );		//Number of items per row in 11
				$grid_cols = 11 / $cols_11;			//Number of grid columns per item
				return (int) ( $container / $grid_cols );
			}
			return $fraction * $container;
		}
		echo '<!-- ['.$cols.']not recognized.  $columns value is a string but not a recognized fraction -->';
		return 1;
	}
	return (int) ( $container / $cols );
}

/**
 * Convert fraction class representation into a float that can be used for mathematical operations
 */
function agility_convert_fraction( $cols ){
	switch( $cols ){
		case '1-4':
			return .25;
		case '1-3':
			return 1/3;
		case '1-2':
			return .5;
		case '2-3':
			return 2/3;
		case '3-4':
			return .75;
		case 'f':
			return 1;
	}
	return false;
}

/**
 * Convert string text names to numeric representation
 */
function agility_cols_string_to_num( $cols , $div = 1){
	$convertInt = array(
		'one'	=>	1,
		'two'	=>	2,
		'three'	=>	3,
		'four'	=>	4,
		'five'	=>	5,
		'six'	=>	6,
		'seven'	=>	7,
		'eight'	=>	8,
		'nine'	=>	9,
		'ten'	=>	10,
		'eleven'=>	11,
		'twelve'=>	12,
		'thirteen'	=> 13,
		'fourteen'	=> 14,
		'fifteen' 	=> 15,
		'sixteen' 	=> 16
	);

	if( isset( $convertInt[$cols] ) ) return $convertInt[$cols];

	$convertFraction = array(

		'one-third'	=> ( 1/3 ),
		'two-thirds'=> ( 2/3 ),
		'one-fourth'=> .25,
		'three-fourths'=> .75,
		'one-half'	=> .5,
		'full-width'=> 1,
	);

	if( isset( $convertFraction[$cols] ) ){
		return $convertFraction[$cols] * $div;
	}

	return 1;
}

/**
 * Determine, based on index and items per column, alpha and omega classes
 */
function agility_alphaomega( $index , $columns ){

	if( $columns == 0 ) return '';

	$ao = array();

	if( $index % $columns == 0 ) 		$ao[] = 'alpha';
	if( ( $index + 1) % $columns == 0 ) $ao[] = 'omega';

	return implode( ' ' , $ao );

}

/**
 * Get the terms for a specific post in a specific taxonomy
 */
function agility_get_post_terms( $post_id, $taxonomy ){
	$terms = wp_get_object_terms( $post_id, $taxonomy );
	if( is_wp_error( $terms )) return array();
	return $terms;
}



/**
 * Determine the image size (from those that are registered) based on the width of the container and the
 * cropping settings for that post.
 *
 * @param post_id - ID of the post.  Might be a post, portfolio, or slide
 * @param width - the width, in pixels, that the image should fill
 */
function agility_image_size( $post_id , $width = 940 , $crop_feature = '' ){
	global $agilitySettings;

	//If no override, use post setting
	if( !$crop_feature ) $crop_feature = agility_meta_on( 'crop_feature' , 'off', $post_id );
	//If we're overriding, translate to boolean
	else{
		$crop_feature = $crop_feature == 'on' ? true : false;
	}

	$crop = $crop_feature && $agilitySettings->op( 'crop-ratio-w' );

	$size = 'full';

	if( $width <= 640 ){
		if( $crop ) $size = 'crop_640';
		else $size = 'natural_640';
	}
	else if( $width <= 940 ){
		if( $crop ) $size = 'crop_940';
		else $size = 'natural_940';
	}

	//Allow users to define their own sizes and return them accordingly
	$size = apply_filters( 'agility_image_size', $size, $post_id, $width , $crop );

	return $size;

}

/**
 * Get the featured media type options available
 */
function agility_feature_types(){

	global $agilitySettings;

	$featureTypes = array(
		'image'			=>	__( 'Featured Image', 'agility') ,
		'video-embed'	=>	__( 'Featured Video - Embedded', 'agility') ,
		'video-self'	=>	__( 'Featured Video - Self-Hosted', 'agility') ,
		'slider'		=>	__( 'Image Slider (Attachments)', 'agility') ,
		'none'			=>	__( 'None', 'agility') 
	);

	if( !$agilitySettings->op( 'self-hosted-video' ) ) {
		unset( $featureTypes['video-self'] );
	}

	return $featureTypes;
}


/**
 * Allow users to be able to wrap content that shouldn't be auto-p'd and texturized with the [raw] code
 */
function agility_formatter($content) {
       $new_content = '';
       $pattern_full = '{(\[raw\].*?\[/raw\])}is';
       $pattern_contents = '{\[raw\](.*?)\[/raw\]}is';
       $pieces = preg_split($pattern_full, $content, -1, PREG_SPLIT_DELIM_CAPTURE);

       foreach ($pieces as $piece) {
               if (preg_match($pattern_contents, $piece, $matches)) {
                       $new_content .= $matches[1];
               } else {
                       $new_content .= wptexturize(wpautop($piece));
               }
       }

       return $new_content;
}

remove_filter('the_content', 'wpautop');
remove_filter('the_content', 'wptexturize');

add_filter('the_content', 'agility_formatter', 99);


/**
 * Setup the basic values for displaying posts in the blog loop
 */
function agility_post_loop_setup(){

	global $post, $post_index, $agilitySettings;

	$post_class 	= 'cf';
	$is_latest 		= ( $post_index == 0 && get_query_var( 'paged' ) == 0 );
	$feature_type 	= get_post_meta( $post->ID , 'feature_type', true );
	$show_feature 	= has_post_thumbnail() && $feature_type != 'video-self';

	if( $show_feature && $is_latest ){
		$post_class.= ' first';
	}

	return compact( 'post_class' , 'is_latest' , 'feature_type' , 'show_feature' );

}

/**
 * Setup values specific to Post type posts in the blog loop
 */
function agility_post_loop_setup_post( $show_feature , $is_latest ){
	$left_class = '';
	$right_class = '';
	$right_header_class = '';
	$meta_left_cols = 'four';
	$meta_right_cols = 'seven';

	if( $show_feature ){
		$left_class = "four columns alpha";
		$right_class = $right_header_class = "seven columns omega";
		$meta_left_cols = 'three';
		$meta_right_cols = 'four';

		if( $is_latest ){
			$left_class = "seven columns alpha";
			$right_class = "four columns omega";
			$right_header_class = '';
			$meta_left_cols = 'four';
			$meta_right_cols = 'seven';
		}

	}
	else{
		$right_class = 'clearfix';
	}

	return compact( 'left_class', 'right_class', 'right_header_class', 'meta_left_cols', 'meta_right_cols' );
}


/**
 * Load Agility resources in the admin area
 */
function agility_admin_resources(){
	$dir = get_template_directory_uri().'/';

	//Javascript	
	wp_enqueue_script( 'agility-admin', $dir.'js/admin.js', array( 'jquery', 'quicktags' ), false, false );

	//Load styles for post edit
	wp_enqueue_style( 'agility-edit', $dir.'stylesheets/agility_edit.css', array(), false, false );
	
}
add_action( 'admin_print_styles-post.php', 'agility_admin_resources' );
add_action( 'admin_print_styles-post-new.php', 'agility_admin_resources' );

function agility_nav_hint(){
	?>
	<div class="menu-primary-container site-navigation main-navigation two-thirds column omega">
		<div class="hint"><?php _e( 'To create a menu, visit Appearance > Menus in the WordPress admin and set a menu in the Primary Theme Location.', 'agility' ); ?></div>
	</div>
	<?php
}

/**
 * Adds classes to UberMenu to properly align menu
 */
function agility_megaMenuFilter( $args ){
	$args['container_class'].= ' two-thirds column omega';
	return $args;

}


/**
 * Loads an individual theme module
 */
function agility_load_module( $module , $rootFile='' ){
	if( $rootFile == '' ) $rootFile = $module;
	require_once( "modules/$module/$rootFile.php" );
}

/**
 * Loads all modules together.
 */
function agility_load_modules(){

	//Load Modules
	agility_load_module( 'custom_post_types', 'CustomPostType.class' );
	agility_load_module( 'custom_post_types', 'post-settings' );
	agility_load_module( 'video' );
	agility_load_module( 'slider' );
	agility_load_module( 'portfolio' );
	agility_load_module( 'author_profile' );

	agility_load_module( 'query_constructor', 'QueryConstructor.class');
	agility_load_module( 'bricklayer', 'BrickLayer.class' );
}

/**
 * Custom Theme Options
 * 
 * We have to load the SparkOptions module before the theme options, and before the shortcodes
 * due to code dependencies
 */
agility_load_module( 'sparkoptions', 'SparkOptions.class');
require( get_template_directory() . '/inc/theme-options.php' );

/**
 * Custom shortcodes
 */
require( get_template_directory() . '/inc/shortcodes.php' );

//Load up the rest
agility_load_modules();


/*function agility_oembed_html(  $html, $url, $attr, $post_ID ){
	$html = str_replace( 'feature=oembed', 'feature=oembed&#038;rel=0', $html );
	return $html;
}
add_filter( 'embed_oembed_html', 'agility_oembed_html', 10, 4 );*/






/**
 * Provides a notification everytime the theme is updated
 * Original code courtesy of João Araújo of Unisphere Design - http://themeforest.net/user/unisphere
 */

function agility_update_notifier_menu() {  

	if( !function_exists( 'simplexml_load_string' ) ) return;
	global $wp_admin_bar, $wpdb;

	$xml = agility_get_latest_theme_version(172800); // This tells the function to cache the remote call for 172800 seconds (48 hours)
	$theme_data = get_theme_data(TEMPLATEPATH . '/style.css'); // Get theme data from style.css (current version is what we want)
	
	if(version_compare($theme_data['Version'], $xml->latest) == -1) {
		$wp_admin_bar->add_menu( array( 'id' => 'agility_update_notifier', 'title' => '<span> Agility Theme <span id="ab-updates">New Updates</span></span>', 'href' => get_admin_url() . 'admin.php?page=agility-settings&updates=1' ) );
	}
}  

if( is_admin() ) add_action('admin_menu', 'agility_update_notifier_menu');

function agility_update_notifier() { 
	$xml = agility_get_latest_theme_version(172800); // This tells the function to cache the remote call for 172800 seconds (48 hours)
	$theme_data = get_theme_data(TEMPLATEPATH . '/style.css'); // Get theme data from style.css (current version is what we want) ?>
	
	<style>
		.update-nag {display: none;}
		#instructions {max-width: 800px;}
		h3.title {margin: 30px 0 0 0; padding: 30px 0 0 0; border-top: 1px solid #ddd;}
	</style>

	<div class="wrap">
	
		<div id="icon-tools" class="icon32"></div>
		<h2><?php echo $theme_data['Name']; ?> Theme Updates</h2>
	    <div id="message" class="updated below-h2"><p><strong>There is a new version of the <?php echo $theme_data['Name']; ?> theme available.</strong> You have version <?php echo $theme_data['Version']; ?> installed. Update to version <?php echo $xml->latest; ?>.</p></div>
        
        <img style="float: left; margin: 0 20px 20px 0; border: 1px solid #ddd;" src="<?php echo get_bloginfo( 'template_url' ) . '/screenshot.png'; ?>" />
        
        <div id="instructions" style="max-width: 800px;">
            <h3>Update Download and Instructions</h3>
            <p><strong>Please note:</strong> make a <strong>backup</strong> of the Theme inside your WordPress installation folder <strong>/wp-content/themes/<?php echo strtolower($theme_data['Name']); ?>/</strong></p>
            <p>To update the Theme, login to <a href="http://www.themeforest.net/">ThemeForest</a>, head over to your <strong>downloads</strong> section and re-download the theme like you did when you bought it.</p>
            <p>Extract the zip's contents, look for the extracted theme folder, and after you have all the new files upload them using FTP to the <strong>/wp-content/themes/<?php echo strtolower($theme_data['Name']); ?>/</strong> folder overwriting the old ones (this is why it's important to backup any changes you've made to the theme files).</p>
        </div>
        
            <div class="clear"></div>
	    
	    <h3 class="title">Changelog</h3>
	    <?php echo $xml->changelog; ?>

	</div>
    
<?php } 

// This function retrieves a remote xml file on my server to see if there's a new update 
// For performance reasons this function caches the xml content in the database for XX seconds ($interval variable)
function agility_get_latest_theme_version($interval) {
	// remote xml file location
	$notifier_file_url = 'http://sevensparklabs.com/updates/agility/notifier.xml';
	
	$db_cache_field = 'agility-notifier-cache';
	$db_cache_field_last_updated = 'agility-notifier-last-updated';
	$last = get_option( $db_cache_field_last_updated );
	$now = time();
	// check the cache
	if ( !$last || (( $now - $last ) > $interval) ) {
		// cache doesn't exist, or is old, so refresh it
		if( function_exists('curl_init') ) { // if cURL is available, use it...
			$ch = curl_init($notifier_file_url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10);
			$cache = curl_exec($ch);
			curl_close($ch);
		} else {
			$cache = file_get_contents($notifier_file_url); // ...if not, use the common file_get_contents()
		}
		
		if ($cache) {			
			// we got good results
			update_option( $db_cache_field, $cache );
			update_option( $db_cache_field_last_updated, time() );			
		}
		// read from the cache file
		$notifier_data = get_option( $db_cache_field );
	}
	else {
		// cache file is fresh enough, so read from it
		$notifier_data = get_option( $db_cache_field );
	}
	
	$xml = simplexml_load_string($notifier_data); 
	
	return $xml;
}