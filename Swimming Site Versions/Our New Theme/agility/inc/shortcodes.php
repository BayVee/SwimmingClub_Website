<?php
/**
 * Agility Shortcodes
 *
 * Additional module-specific shortcodes may be defined by their module
 *
 * @package Agility
 * @since Agility 1.0
 */



/**
 * Adds the shortcode with two namespaces:
 * 
 * 	agility-{shortcode}
 *  {namspace}{shortcode}
 *
 *  Where {namespace} is set in the Agility Control Panel
 */
function agility_add_shortcode( $shortcode_tag , $function, $args = array(), $public = true ){	
	global $shortcodeMaster;
	$shortcodeMaster->addShortcode( $shortcode_tag , $function, $args , $public );
}

class SparkShortcodeMaster{

	private $shortcodes;
	private $defaults;

	function __construct(){
		$this->shortcodes = array();
		$this->defaults = array();

		if( is_admin() ){
			add_action( 'init', array( &$this, 'mce_button' ) );
			add_action( 'wp_ajax_shortcodeMaster_loadShortcodes', array( &$this, 'ajaxLoadShortcodes' ) );
			add_action( 'wp_ajax_shortcodeMaster_loadShortcodeForm', array( &$this, 'ajaxLoadShortcodeForm' ) );
			
		}
	}

	function mce_button() {
		if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) {
			return;
		}
	 
		if ( get_user_option('rich_editing') == 'true' ) {
			add_filter( 'mce_external_plugins', array( &$this, 'add_mce_plugin' ) );
			add_filter( 'mce_buttons', array( &$this, 'register_mce_button' ) );
		} 
	}

	function register_mce_button( $buttons ) {
		array_push( $buttons, "|", "shortcodeMaster" );
		return $buttons;
	}

	function add_mce_plugin( $plugin_array ) {
		$plugin_array['shortcodeMaster'] =  get_template_directory_uri(). '/js/shortcodebutton.js';
		return $plugin_array;
	}

	function ajaxLoadShortcodes(){
		$ul = '<ul class="standard-shortcodes"><li><span class="shortcode-breaker">Shortcodes:</span></li><li><span class="shortcode-breaker">&nbsp;</span></li>';
		$columns = '<ul class="columns-shortcodes"><li><span class="shortcode-breaker">'.__( 'Columns', 'agility' ).':</span></li><li><span class="shortcode-breaker">&nbsp;</span></li>'; //<li><span class="shortcode-breaker">&nbsp;</span></li>
		foreach( $this->shortcodes as $shortcode => $atts ){
			if( isset( $atts['column'] ) && $atts['column'] ){
				$columns.= '<li><a href="#'.$shortcode.'">'.$shortcode.'</a></li>';
			}
			else $ul.= '<li><a href="#'.$shortcode.'">'.$shortcode.'</a></li>';

		}
		$ul.= '</ul>'.$columns.'</ul>';
		echo $ul;
		die();
	}

	function ajaxLoadShortcodeForm(){

		$sid = $_POST['shortcode'];
		if( $sid ){
			$sid = substr( $sid, 1 );
			$form = '<form class="shortcodeMaster-form">';
			$form.= '<h3>'.__( 'Insert a', 'agility' ).' ['.$sid.']</h3>';

			$shortcode_params = $this->shortcodes[$sid]['params'];
			$content = $this->shortcodes[$sid]['content'];

			foreach( $shortcode_params as $param => $settings ){

				$default = isset( $settings['default'] ) ? $settings['default'] : '';
				$id = $sid.'-'.$param;
				$param_title = isset( $settings['title'] ) ? $settings['title'] : $param;

				$tooltip = isset( $settings['desc'] ) ? 'data-tooltip="'.$settings['desc'].'"' : 'data-tooltip="'.$param.'"';

				$form.= '<div class="shortcodeMaster_form_setting shortcodeMaster_form_setting-attribute">';
				$form.= '<label for="'.$id.'" '.$tooltip.'>'.$param_title.'</label>';

				switch( $settings['type'] ){

					case 'text':						
						$form.= '<input id="'.$id.'" name="'.$param.'" type="text" value="'.$default.'" data-default="'.$default.'" />';
						break;

					case 'select':
						$ops = array();
						if( is_array( $settings['ops'] ) ){
							$ops = $settings['ops'];
						}
						else if( function_exists( $settings['ops'] ) ){
							$ops = $settings['ops']();
						}

						$form.= '<select id="'.$id.'" name="'.$param.'"  data-default="'.$default.'" >';
						foreach( $ops as $val => $title ){
							$form.= '<option value="'.$val.'" '.selected( $val , $default, false ) .'>'.$title.'</option>';
						}
						$form.= '</select>';
						break;

					case 'checkbox':
						$form.= '<input id="'.$id.'" name="'.$param.'" type="checkbox" '.checked( $default , 'on' , false ). ' data-default="'.$default.'" />';
						break;

					case 'radio':
						break;

				}

				

				$form.= '</div>';
			}

			if( $content ){
				$form.= '<div class="shortcodeMaster_form_setting">';

				$desc = isset( $this->shortcodes[$sid]['content']['desc'] ) ? '<br/>'.$this->shortcodes[$sid]['content']['desc'] : '';
				$content_default = isset( $this->shortcodes[$sid]['content']['default'] ) ? $this->shortcodes[$sid]['content']['default'] : '';

				if( $desc ) $form.= '<p>'.$desc.'</p>';
				$form.= '<label for="'.$sid.'-content" data-tooltip="Shortcode Content">'.$this->shortcodes[$sid]['content']['title'].'</label>';
				$form.= '<textarea name="content" id="'.$sid.'-content">'.$content_default.'</textarea>';
				
				$form.= '</div>';
			}

			$form.= '<div class="shortcode-preview">['.$sid.']</div>';
			

			$form.= '<input type="hidden" value="'.$sid.'" name="shortcode_tag" class="shortcode-tag" />';
			$form.= '<input type="submit" value="Insert" name="insert_shortcode" class="insert-shortcode button" />';
			$form.= '</form>';

			$form.= '<br style="clear:both;" />';
			echo $form;
		}
		die();
	}

	function addShortcode(  $shortcode_tag , $function, $args, $public = true ){

		global $agilitySettings;

		$shortcode_namespace = $agilitySettings->op( 'shortcode_namespace' );
		if( $shortcode_namespace == 'agility' ) $shortcode_namespace = '';
		
		//Always register shortcodes with agility prefix
		add_shortcode( 'agility-'.$shortcode_tag , $function );

		//If specifying a custom prefix && namespace != 'agility-'
		$namespaced_shortcode_tag = $shortcode_namespace.$shortcode_tag;
		add_shortcode( $namespaced_shortcode_tag, $function );

		if( $public ){
			$this->shortcodes[$shortcode_tag] = $args;
			$this->defaults[$shortcode_tag] = array();

			if( isset( $args['params'] ) ){
				foreach( $args['params'] as $param => $settings ){
					$this->defaults[$shortcode_tag][$param] = $settings['default'];
				}
			}
		}

	}

	function getDefaults( $shortcode ){
		if( isset( $this->defaults[$shortcode] ) )
			return $this->defaults[$shortcode];
		return array();
	}

}
$GLOBALS['shortcodeMaster'] = new SparkShortcodeMaster();




/**
 * The button shortcode
 */
function agility_shortcode_button( $atts , $content ){

	global $shortcodeMaster;

	extract( shortcode_atts( $shortcodeMaster->getDefaults( 'button' ) , $atts ) );

	$class = 'button';
	if( $color ) 			$class.= " button-$color";
	if( $light == 'on' ) 	$class.= " button-lighttext";
	if( $large == 'on' )	$class.= " button-large";
	if( $full_width =='on') $class.= " button-full";

	$class = 'class="'.$class.'"';

	if( $href )				$href = 'href="'.$href.'"';
	if( $target )			$target = 'target="'.$target.'"';

	$html = "<$tag $class $href $target >$content</$tag>";

	return $html;
}
agility_add_shortcode( 'button' , 'agility_shortcode_button', array(

	'params' => array(
		
		'color' 	=> array(
			'title'		=>	__( 'Color' , 'agility' ),
			'desc'		=>	__( 'Button background color' , 'agility' ),
			'default'	=>	'',
			'type'		=>	'select',
			'ops'		=>	array(
				'silver'	=> __( 'Silver (Default)' , 'agility' ),
				'green'		=> __( 'Green' , 'agility' ),
				'blue'		=> __( 'Blue' , 'agility' ),
				'red'		=> __( 'Red' , 'agility' ),
				'grey'		=> __( 'Grey' , 'agility' ),
				'purple'	=> __( 'Purple' , 'agility' ),
				'yellow'	=> __( 'Yellow' , 'agility' ),
			),
		),
		'light'		=> array(
			'title'		=> __( 'Light text' , 'agility' ),
			'desc'		=> __( 'Uncheck to use dark text in the color of the button' , 'agility' ),
			'default'	=> 'on',
			'type'		=> 'checkbox',
			//'ops'		=> array( 'on' , 'off' ),
		),
		'large'		=> array(
			'title'		=> __( 'Large button' , 'agility' ),
			'desc'		=> __( 'Enable a larger button' , 'agility' ),
			'default'	=> 'off',
			'type'		=> 'checkbox',
			//'ops'		=> array( 'on' , 'off' ),
		),
		'full_width'=> array(
			'title'		=> __( 'Full Width Button' , 'agility' ),
			'desc'		=> __( 'Display the button at the full width of its container' , 'agility' ),
			'default'	=> 'off',
			'type'		=> 'checkbox',
			//'ops'		=> array( 'on' , 'off' ),
		),
		'href'		=> array(
			'title'		=> __( 'URL' , 'agility' ),
			'desc'		=> __( 'Link to redirect to' , 'agility' ),
			'default'	=> '',
			'type'		=> 'text',
		),
		'tag'		=> array(
			'title'		=> __( 'Tag' , 'agility' ),

			'default'	=> 'a', 	//button
			'type'		=> 'select',
			'ops'		=> array(
					'a'		=> 'a',
					'button' => 'button',
				)
		),
		'target'	=> array(
			'title'		=> 'Link Target',
			'default'	=> '',
			'type'		=> 'select',
			'ops'		=> array(
				'_self'	=> 'Self (Default)',
				'_blank'=> 'Blank (new tab/window)',
				'_parent'=>'Parent',
				'_top'	=> 'Top',
			),
		),
	),
	'content' => array(
		'title' =>	__( 'Button Text', 'agility' )
	)

) );


/**
 * The tagline shortcode
 */
function agility_shortcode_tagline( $atts, $content ){
	
	global $shortcodeMaster;

	extract( shortcode_atts( $shortcodeMaster->getDefaults( 'tagline' ), $atts ) );

	$html = '<div class="tagline">'.$content;

	if( $fleuron == 'on' ) $html.= '<br/><span class="fleuron"></span>';

	$html.= '</div>';

	return $html;
}
agility_add_shortcode( 'tagline' , 'agility_shortcode_tagline', array(
			'params'	=>	array(
				'fleuron' 	=> array(
					'title'	=> __( 'Fleuron', 'agility' ),
					'type'	=> 'checkbox',
					'desc'	=> __( 'Display the fleuron below the tagline', 'agility' ),
					'default'	=> 'on',
				),
			),
			'content'	=> array(
				'title' => __( 'Tagline content', 'agility' ),
			),
		) );


/**
 * The alert function, used by notices, warnings, and errors
 */
function agility_alert( $type, $content, $title = '' , $custom_html = 'off' ){

	$html = 
		'<div class="alert alert-'.$type.'">';

	if( $title ) $html.= "<h6>$title</h6>";

	if( $custom_html == 'on' ) $html.= $content; 
	else $html.= "<p>$content</p>";

	$html.= '</div>';

	return $html;
}

/**
 * The Notice shortcode
 */
function agility_shortcode_alert_notice( $atts, $content ){

	global $shortcodeMaster;

	extract( shortcode_atts( $shortcodeMaster->getDefaults( 'notice' ) , $atts ) );

	return agility_alert( 'notification' , $content , $title , $html );	
}

/**
 * The Warning shortcode
 */
function agility_shortcode_alert_warning( $atts, $content ){

	global $shortcodeMaster;

	extract( shortcode_atts( $shortcodeMaster->getDefaults( 'warning' ), $atts ) );

	return agility_alert( 'warning' , $content , $title , $html );	
}

/**
 * The Error shortcode
 */
function agility_shortcode_alert_error( $atts, $content ){

	global $shortcodeMaster;

	extract( shortcode_atts( $shortcodeMaster->getDefaults( 'error' ), $atts ) );

	return agility_alert( 'error' , $content , $title , $html );	
}

$alert_shortcode_settings = array(
	'params'	=>	array(
		'title' 	=> array(
			'title'	=> __( 'Alert Title', 'agility' ),
			'desc'	=> __( 'Optional header text in the alert box', 'agility' ),
			'type'	=> 'text',
			'default' => '',
		),
		'html'		=> array(
			'title' => __( 'HTML Content', 'agility' ),
			'desc'	=> __( 'Enable if you are using HTML in your Alert Text', 'agility' ),
			'type'	=> 'checkbox',
			'default' => 'off',
		),
	),
	'content'	=> array(
		'title' => __( 'Alert Text', 'agility' ),
	),
);
agility_add_shortcode( 'notice' , 'agility_shortcode_alert_notice' , $alert_shortcode_settings );
agility_add_shortcode( 'warning' , 'agility_shortcode_alert_warning', $alert_shortcode_settings );
agility_add_shortcode( 'error' , 'agility_shortcode_alert_error' , $alert_shortcode_settings );



/**
 * The Tabs shortcode
 */
function agility_shortcode_tabs( $atts, $content ){

	global $shortcodeMaster;

	extract( shortcode_atts( $shortcodeMaster->getDefaults( 'tabs' ), $atts ) );

	$content = trim( $content );

	$tabs = str_replace( '[/tab', '[/_tab' , $content);
	$tabs = str_replace( '[tab', '[_tab' , $tabs);
	$tabs = do_shortcode( $tabs );

	$tab_content = str_replace( '[/tab]', '[/_tab_content]', $content );
	$tab_content = str_replace( '[tab', '[_tab_content', $tab_content );
	$tab_content = do_shortcode( $tab_content );

	$html = '<ul class="tabs">'.$tabs.'</ul>';
	$html.= '<ul class="tabs-content">'.do_shortcode( $tab_content ).'</ul>';

	return $html;
}

/**
 * The Tab (nav) shortcode
 */
function agility_shortcode_tab( $atts, $content ){

	//global $shortcodeMaster;

	extract( shortcode_atts( array(
		'title'	=> '',
		'active'=> 'off',
	), $atts ) );

	if( !$title ) return '<li class="hint">'.__( 'You must set a unique "title" attribute in the [tab] shortcode', 'agility' ).'</li>';	

	$id = sanitize_title( $title );

	if( $active == 'on' ) $active = 'class="active"';
	else $active = '';

	$html = '<li><a href="#'.$id.'" '.$active.'>'.$title.'</a></li>';

	return $html;
}
function agility_shortcode_tab_dummy( $atts, $content ){
	return __( 'This shortcode needs to be wrapped in the [tabs] shortcode', 'agility' );
}
/**
 * The Tab (content) shortcode
 */
function agility_shortcode_tab_content( $atts, $content ){

	//global $shortcodeMaster;

	extract( shortcode_atts( array(
		'title'	=> '',
		'active'=> 'off',
	), $atts ) );

	$id = sanitize_title( $title );

	if( $active == 'on' ) $active = 'class="active"';
	else $active = '';

	$html = '<li id="'.$id.'" '.$active.'>'.$content.'</li>';

	return $html;
}
agility_add_shortcode( 'tabs', 'agility_shortcode_tabs' , array( 
		'params'	=> array(),
		'content'	=> array(
			'title' => __( 'Tab Content', 'agility' ),
			'desc' 	=> __( 'The [tabs] shortcode is a wrapper.  You can insert [tab] shortcodes inside it to create tabs and content', 'agility' ),
			'default' => '[tab title="Tab 1" active="on"]Tab 1 content[/tab] [tab title="Tab 2"]Tab 2 content[/tab]',
		)
	) );

agility_add_shortcode( 'tab' , 'agility_shortcode_tab_dummy' , array(
		'params'	=> array(
			'title'	=> array(
				'title'		=> __( 'Tab Title', 'agility' ),
				'desc'		=> __( 'The title of the tab', 'agility' ),
				'type'		=> 'text',
				'default' 	=> '',
			),
			'active' => array(
				'title'		=> __( 'Active Tab?', 'agility' ),
				'desc'		=> __( 'Make this tag active by default', 'agility' ),
				'type'		=> 'checkbox',
				'default'	=> 'off'
			),
		),
		'content'	=> array(
			'title' => __( 'Tab Content', 'agility' ),
			'desc' 	=> '',
		)
	));
agility_add_shortcode( '_tab', 'agility_shortcode_tab', array(), false );
agility_add_shortcode( '_tab_content', 'agility_shortcode_tab_content', array(), false );

/**
 * The Toggle shortcode
 */
function agility_shortcode_toggle( $atts, $content ){

	global $shortcodeMaster;

	extract( shortcode_atts( $shortcodeMaster->getDefaults( 'toggle' ), $atts ) );

	$html = '<div class="toggle toggle-'.$start.'">'.
				'<'.$htag.' class="toggle-header"><a href="#">'.$title.'</a></'.$htag.'>'.
				'<div class="toggle-body">'.do_shortcode( $content ).'</div>'.
			'</div>';

	return $html;

}
agility_add_shortcode( 'toggle', 'agility_shortcode_toggle' , array(
		'params' => array(
			'title'	=> array(
				'title'		=> __( 'Title' , 'agility' ),
				'type'		=> 'text',
				'desc'		=> __( 'The header for this toggle' , 'agility' ),
				'default' 	=> __( 'Set a toggle title attribute', 'agility' ),
			),
			'htag'	=> array(
				'title' 	=> __( "Header Tag" , 'agility' ),
				'desc'		=> __( "Header tag h1-h6" , 'agility' ),
				'type'		=> 'select',
				'ops'		=> array( 
									'h1' => 'h1', 
									'h2' => 'h2', 
									'h3' => 'h3', 
									'h4' => 'h4', 
									'h5' => 'h5', 
									'h6' => 'h6' ),
				'default'  	=>	'h5',
			),
			'start'			=> array(
				'title' 	=> __( "Start State" , 'agility' ),
				'desc'		=> __( "Should this toggle be open or closed when the page loads?" , 'agility' ),
				'type'		=> 'select',
				'ops'		=> array( 'open' => __( 'Open', 'agility' ), 'closed' => __( 'Closed', 'agility' ) ),
				'default'	=> 'closed',
			)
		),
		'content'	=> array(
			'title'	=> __( 'Toggle Content' , 'agility' ),
			'desc'	=> __( 'The content to hide or reveal' , 'agility' ),

		)
	));

/**
 * The Map shortcode
 */
function agility_shortcode_map( $atts ) {
	global $shortcodeMaster;
	extract( shortcode_atts( $shortcodeMaster->getDefaults( 'map' ) , $atts ) );

	return '<div class="map_canvas" '.
					'data-lat="'.$lat.'" '.
					'data-lng="'.$lng.'" '.
					'data-address="'.$address.'" '.
					'data-zoom="'.$zoom.'" '.
					'data-mapTitle="'.$title.'" '.
					'style="height:'.$height.';width:'.$width.';"></div>';

}
agility_add_shortcode( 'map' , 'agility_shortcode_map' , array(

		'params'	=> array(
			'zoom' 		=> array( 
				'title' 	=> __( 'Zoom', 'agility' ),
				'desc'		=> __( 'The zoom value for the map. Higher numbers are zoomed in further', 'agility' ),
				'type'		=> 'text',
				'default' 	=> 8,
			),
			'lat' 		=> array( 
				'title'	=> __( 'Latitude', 'agility' ),
				'desc'	=> __( 'The decimal latitude coordinate for the location to display.', 'agility' ),
				'type'	=> 'text',
				'default' => 0,
			),
			'lng'		=> array( 
				'title'	=> __( 'Longitude', 'agility' ),
				'desc'	=> __( 'The decimal longitude coordinate for the location to display.', 'agility' ),
				'type'	=> 'text',
				'default' => 0,
			),
			'address'	=> array( 
				'title'	=> __( 'Address', 'agility' ),
				'desc'	=> __( 'Use the address option to geocode your map location on the fly and place a marker.', 'agility' ),
				'type'	=> 'text',
				'default' => '',
			),
			'title'		=> array( 
				'title'	=> __( 'Title', 'agility' ),
				'desc'	=> __( 'The title for the map marker (optional)', 'agility' ),
				'type'	=> 'text',
				'default' => '',
			),
			'height'	=> array( 
				'title'	=> __( 'Map Height', 'agility' ),
				'desc'	=> __( 'Height of the map', 'agility' ),
				'type'	=> 'text',
				'default' => '150px',
			),
			'width'		=> array( 
				'title'	=> __( 'Map Width ', 'agility' ),
				'desc'	=> __( 'Width of the map.  Leave at 100% for responsiveness.', 'agility' ),
				'type'	=> 'text',
				'default' => '100%',
			),
		),
		'content' => false,
	));

/**
 * The URL shortcode - can take a post ID, slug, path (string), or attachment img ID
 */
function agility_url( $atts ) {
	extract( shortcode_atts( array(
		'id'		=>	'',
		'slug'		=>	'',
		'path' 		=> 	'',
		'img'		=>	'',
	), $atts ) );

	if( $id ){
		return get_permalink( $id );
	}
	if( $slug ){
		$posts = get_posts( array( 'name' => $slug , 'post_type' => 'any' ) );
		if( empty( $posts ) ){
			return site_url( '#slug_not_found' );
		}
		$post = $posts[0];
		return get_permalink( $post );
	}
	if( $path ){
		return site_url( $path );
	}
	if( $img ){
		return wp_get_attachment_url( $img );
	}


}
agility_add_shortcode( 'url' , 'agility_url' , array(), false );







/**
 * The Mosaic shortcode
 */
function agility_mosaic_shortcode( $atts ) {

	global $post;

	extract( shortcode_atts( array(
		'source'		=>	'attachments',	//query, attachments
		'source_id'		=>	0,			//query_id, post_id
		'column_size'	=>	'1-3',
		'show_title'	=>	'on',
		'post_id'		=>	$post->ID,

		'wrap'			=>	true,
		'column_container' => 16, //11

		'size'       	=> 'large',
		
	), $atts ) );


	$query;
	$queryStruct;

	switch( $source ){

		case 'query':
			$queryStruct = new QueryStruct( $query_id );
			$query = $queryStruct->query();
			break;

		case 'attachments':
			$args = array(
				'post_parent' 		=> $post_id,
				'post_status' 		=> 'inherit',
				'post_type' 		=> 'attachment',
				'post_mime_type'	=> 'image',
				'order' 			=> 'ASC',
				'orderby' 			=> 'menu_order ID'
			);

			$queryStruct = new QueryStruct();
			$queryStruct->loadSettings( $args );
			$query = $queryStruct->query();

	}

	ob_start();
	agility_mosaic( $query , $column_size , $atts );
	$html = ob_get_contents();
	ob_end_clean();

	$queryStruct->queryCompleted();

	return $html;
}

agility_add_shortcode( 'mosaic' , 'agility_mosaic_shortcode' , array(), false );


/**
 * The Social Media Icons shortcode
 */
function agility_social_media_icons_shortcode( $atts ) {

	global $shortcodeMaster;
	extract( shortcode_atts( $shortcodeMaster->getDefaults( 'social_media' ) , $atts ) );

	ob_start();

	if( $order ){
		$order = explode( ',' , $order );
		foreach( $order as $key => $val ){ $order[$key] = trim( $val ); }
		agility_social_media_icons( $order );
	}
	else{
		agility_social_media_icons();
	}
	$html = ob_get_contents();
	ob_end_clean();
	return $html;
}
agility_add_shortcode( 'social_media' , 'agility_social_media_icons_shortcode', array(
		'params'	=> array(
			'order'	=> array(
				'title'	=> __( 'Icon Order' , 'agility' ),
				'desc'	=> __( 'Set a manual order for the social media icons. This allows you to reorder and include only social media icons you want.  Use a comma-separated list of', 'agility' ).' twitter, facebook, forrst, dribbble, vimeo, youtube, linkedin, pinterest, flickr, tumblr',
				'type' 	=> 'text',
				'default' => 'twitter, facebook, forrst, dribbble, vimeo, youtube, linkedin, pinterest, flickr, tumblr'
			),
		),
		'content'	=> false,
	) ); // 'agility_social_media_icons' );



/**
 * The Testimonial shortcode
 */
function agility_testimonial_shortcode( $atts , $content ) {

	global $shortcodeMaster;
	extract( shortcode_atts( $shortcodeMaster->getDefaults( 'testimonial' ), $atts ) );

	$cite = '';
	if( $from ) $cite = '<cite>'.$from.'</cite>';

	$html = '<blockquote class="testimonial">'.$content.$cite.'</blockquote>';

	return $html;

}
agility_add_shortcode( 'testimonial', 'agility_testimonial_shortcode', array(
		'params' => array(
			'from'	=>	array(
				'title'		=> __( 'From', 'agility' ),
				'desc'		=> __( 'Citation from the entity that made this testimonial', 'agility' ),
				'type'		=> 'text',
				'default' 	=> '',
			),
		),

		'content'	=> array(
			'title'	=> __( 'Testimonial Content', 'agility' ),
			'desc'	=> __( 'The testimonial text itself', 'agility' ),
		)
	) );



/* COLUMNS SHORTCODES */


function agility_column( $columns , $atts, $content ){ /*$content, $alpha = 'off', $omega = 'off', $class = '' ){*/

	global $shortcodeMaster;
	extract( shortcode_atts( $shortcodeMaster->getDefaults( $columns ), $atts) );

	if( strpos( $columns, 'grid' ) === 0 ){
		$columns = substr( $columns , 5 );
		$columns = agility_grid_columns_class( $columns );
	}
	
	$_class = $columns. ' ' . agility_grid_columns_plural( $columns ); 
	if( $alpha == 'on' ) $_class.= ' alpha';
	if( $omega == 'on' ) $_class.= ' omega';
	if( $class ) $_class.= ' ' .$class;

	$content = do_shortcode( $content );

	return '<div class="'.$_class.'">'.$content.'</div>';

}

$columns_shortcodes_settings = array(
	'params'	=> array(
		'alpha' => array(
			'title'		=> __( 'Start row (alpha)', 'agility' ),
			'desc'		=> __( 'Check this if the column starts the row, and it is inside a column group (like the main content area or sidebar)', 'agility' ),
			'type'	 	=> 'checkbox',
			'default' 	=> 'off',
		),
		'omega' => array(
			'title'		=> __( 'End row (omega)', 'agility' ),
			'desc'		=> __( 'Check this if the column ends the row, and it is inside a column group (like the main content area or sidebar)', 'agility' ),
			'type'	 	=> 'checkbox',
			'default' 	=> 'off',
		),
		'class'	=> array(
			'title'	=> __( 'Custom Class', 'agility' ),
			'desc'	=> __( 'Add a custom class to this column', 'agility' ),
			'type' 	=> 'text',
			'default' => '',
		),
	),
	'content' => array(
		'title'	=> __( 'Column Content', 'agility' ),
		'desc'	=> __( 'You can add all the content you want between the start and end tags.', 'agility' ),
	),
	'column' => true,
);

function agility_column_shortcode_one_fourth( $atts, $content ){	
	return agility_column( 'one-fourth' , $atts, $content );
}
agility_add_shortcode( 'one-fourth' , 'agility_column_shortcode_one_fourth', $columns_shortcodes_settings );

function agility_column_shortcode_one_third( $atts, $content ){	
	return agility_column( 'one-third' , $atts, $content );
}
agility_add_shortcode( 'one-third' , 'agility_column_shortcode_one_third', $columns_shortcodes_settings );

function agility_column_shortcode_one_half( $atts, $content ){	
	return agility_column( 'one-half' , $atts, $content );
}
agility_add_shortcode( 'one-half' , 'agility_column_shortcode_one_half', $columns_shortcodes_settings );

function agility_column_shortcode_two_thirds( $atts, $content ){	
	return agility_column( 'two-thirds' , $atts, $content );
}
agility_add_shortcode( 'two-thirds' , 'agility_column_shortcode_two_thirds', $columns_shortcodes_settings );

function agility_column_shortcode_three_fourths( $atts, $content ){	
	return agility_column( 'three-fourths' , $atts, $content );
}
agility_add_shortcode( 'three-fourths' , 'agility_column_shortcode_three_fourths', $columns_shortcodes_settings );

function agility_column_shortcode_one( $atts, $content ){	
	return agility_column( 'grid-1' , $atts, $content );
}
agility_add_shortcode( 'grid-1' , 'agility_column_shortcode_one', $columns_shortcodes_settings );

function agility_column_shortcode_two( $atts, $content ){	
	return agility_column( 'grid-2' , $atts, $content );
}
agility_add_shortcode( 'grid-2' , 'agility_column_shortcode_two', $columns_shortcodes_settings );

function agility_column_shortcode_three( $atts, $content ){	
	return agility_column( 'grid-3' , $atts, $content );
}
agility_add_shortcode( 'grid-3' , 'agility_column_shortcode_three', $columns_shortcodes_settings );

function agility_column_shortcode_four( $atts, $content ){	
	return agility_column( 'grid-4' , $atts, $content );
}
agility_add_shortcode( 'grid-4' , 'agility_column_shortcode_four', $columns_shortcodes_settings );

function agility_column_shortcode_five( $atts, $content ){	
	return agility_column( 'grid-5' , $atts, $content );
}
agility_add_shortcode( 'grid-5' , 'agility_column_shortcode_five', $columns_shortcodes_settings );

function agility_column_shortcode_six( $atts, $content ){	
	return agility_column( 'grid-6' , $atts, $content );
}
agility_add_shortcode( 'grid-6' , 'agility_column_shortcode_six', $columns_shortcodes_settings );

function agility_column_shortcode_seven( $atts, $content ){	
	return agility_column( 'grid-7' , $atts, $content );
}
agility_add_shortcode( 'grid-7' , 'agility_column_shortcode_seven', $columns_shortcodes_settings );

function agility_column_shortcode_eight( $atts, $content ){	
	return agility_column( 'grid-8' , $atts, $content );
}
agility_add_shortcode( 'grid-8' , 'agility_column_shortcode_eight', $columns_shortcodes_settings );

function agility_column_shortcode_nine( $atts, $content ){	
	return agility_column( 'grid-9' , $atts, $content );
}
agility_add_shortcode( 'grid-9' , 'agility_column_shortcode_nine', $columns_shortcodes_settings );

function agility_column_shortcode_ten( $atts, $content ){	
	return agility_column( 'grid-10' , $atts, $content );
}
agility_add_shortcode( 'grid-10' , 'agility_column_shortcode_ten', $columns_shortcodes_settings );

function agility_column_shortcode_eleven( $atts, $content ){	
	return agility_column( 'grid-11' , $atts, $content );
}
agility_add_shortcode( 'grid-11' , 'agility_column_shortcode_eleven', $columns_shortcodes_settings );

function agility_column_shortcode_twelve( $atts, $content ){	
	return agility_column( 'grid-12' , $atts, $content );
}
agility_add_shortcode( 'grid-12' , 'agility_column_shortcode_twelve', $columns_shortcodes_settings );

function agility_column_shortcode_thirteen( $atts, $content ){	
	return agility_column( 'grid-13' , $atts, $content );
}
agility_add_shortcode( 'grid-13' , 'agility_column_shortcode_thirteen', $columns_shortcodes_settings );

function agility_column_shortcode_fourteen( $atts, $content ){	
	return agility_column( 'grid-14' , $atts, $content );
}
agility_add_shortcode( 'grid-14' , 'agility_column_shortcode_fourteen', $columns_shortcodes_settings );

function agility_column_shortcode_fifteen( $atts, $content ){	
	return agility_column( 'grid-15' , $atts, $content );
}
agility_add_shortcode( 'grid-15' , 'agility_column_shortcode_fifteen', $columns_shortcodes_settings );

function agility_column_shortcode_sixteen( $atts, $content ){	
	return agility_column( 'grid-16' , $atts, $content );
}
agility_add_shortcode( 'grid-16' , 'agility_column_shortcode_sixteen', $columns_shortcodes_settings );

function agility_column_shortcode_row( $atts, $content ){
	return '<div class="row">'.do_shortcode( $content ).'</div>';
}
agility_add_shortcode( 'row' , 'agility_column_shortcode_row' , array(
	'params'	=> array(),
	'content'	=> array(
		'title'	=> __( 'Row Content', 'agility' ),
		'desc'	=> __( 'Generally, you would place columns inside the row shortcode', 'agility' ),
	),
	'column' => true
));



/**
 * Agility's Gallery shortcode, implemented by filter
 */
add_filter( 'post_gallery', 'agility_gallery_shortcode', 10, 2 );

function agility_gallery_shortcode( $output, $attr ) {
	global $post;

	static $instance = 0;
	$instance++;

	// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
	if ( isset( $attr['orderby'] ) ) {
		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( !$attr['orderby'] )
			unset( $attr['orderby'] );
	}

	extract( shortcode_atts(array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post->ID,
		'itemtag'    => 'div',
		'icontag'    => 'div',
		'captiontag' => 'span',
		'columns'    => 3,
		'size'       => 'large',
		'include'    => '',
		'exclude'    => '',
		'lightbox'	 =>	'on',
		'caption'	 => 'off',
	), $attr) );

	$id = intval($id);
	if ( 'RAND' == $order )
		$orderby = 'none';

	if ( !empty($include) ) {
		$include = preg_replace( '/[^0-9,]+/', '', $include );
		$_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif ( !empty($exclude) ) {
		$exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
		$attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	} else {
		$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	}

	if ( empty($attachments) )
		return '';

	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $att_id => $attachment )
			$output .= wp_get_attachment_link($att_id, $size, true) . "\n";
		return $output;
	}

	$itemtag = tag_escape($itemtag);
	$captiontag = tag_escape($captiontag);
	$columns = intval($columns);
	$itemwidth = $columns > 0 ? floor(100/$columns) : 100;
	$float = is_rtl() ? 'right' : 'left';

	$selector = "gallery-{$instance}";

	$gallery_style = $gallery_div = '';
	
	$size_class = sanitize_html_class( $size );
	$gallery_div = "<div id='$selector' class='gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class} clearfix'>";
	$output = apply_filters( 'gallery_style', $gallery_style . "\n\t\t" . $gallery_div );

	$i = 0;
	$cols = agility_one_over( $columns );
	
	$output.= '<div class="row">';

	foreach ( $attachments as $id => $attachment ) {

		if( $lightbox == 'on' ){
			$id = intval( $id );
			$_post = & get_post( $id );

			if ( empty( $_post ) || ( 'attachment' != $_post->post_type ) || ! $url = wp_get_attachment_url( $_post->ID ) ){
				$link = __( 'Missing Attachment', 'agility' );
			}
			else{
				
				$post_title = esc_attr( $_post->post_title );

				if ( $size && 'none' != $size )
					$link_text =  wp_get_attachment_image( $id, $size, false, array( 'alt' => $post_title, 'class' => "attachment-$size scale-with-grid" ) );
				else
					$link_text = '';

				if ( trim( $link_text ) == '' )
					$link_text = $_post->post_title;

				$link = '<a href="'.$url.'" data-rel="prettyPhoto[gallery-'.$id.']" title="'.strip_tags( $attachment->post_excerpt ).'">'.$link_text.'</a>';
			}
		}	
		else{
			$link = isset($attr['link']) && 'file' == $attr['link'] ? wp_get_attachment_link($id, $size, false, false) : wp_get_attachment_link($id, $size, true, false);
		}

		$class = "one-$cols column";
		if( $i % $columns == 0 ) $class.= ' alpha';
		else if( ( $i + 1) % $columns == 0 ) $class.= ' omega';

		$output .= "<{$itemtag} class='gallery-item $class'>";
		$output .= 
			"<{$icontag} class='gallery-icon'>$link</{$icontag}>";
		if ( $caption == 'on' && $captiontag && trim($attachment->post_excerpt) ) {
			$output .= 
				"<{$captiontag} class='wp-caption-text gallery-caption'>" . 
				wptexturize($attachment->post_excerpt) . 
				"</{$captiontag}>";
		}
		$output .= "</{$itemtag}>";
		if ( $columns > 0 && ++$i % $columns == 0 )
			$output .= '</div><div class="row">';
	}

	$output .= "
			</div>
		</div>\n";

	return $output;


}

/**
 * Override the image caption shortcode so that links can be displayed in the 
 * caption.
 */
function agility_img_caption_shortcode( $a , $attr, $content = null) {

	extract(shortcode_atts(array(
		'id'	=> '',
		'align'	=> 'alignnone',
		'width'	=> '',
		'caption' => ''
	), $attr));

	if ( 1 > (int) $width || empty($caption) )
		return $content;

	$caption = html_entity_decode( $caption );

	if ( $id ) $id = 'id="' . esc_attr($id) . '" ';

	return '<div ' . $id . 'class="wp-caption ' . esc_attr($align) . '" style="width: ' . (10 + (int) $width) . 'px">'
	. do_shortcode( $content ) . '<p class="wp-caption-text">' . $caption . '</p></div>';
}
add_filter( 'img_caption_shortcode', 'agility_img_caption_shortcode', 10, 3 );



function agility_related_posts( $atts , $content ) {

	global $shortcodeMaster, $post;
	extract( shortcode_atts( $shortcodeMaster->getDefaults( 'simple_related_posts' ), $atts ) );
	$html = '';

	$query;
	$queryStruct;

	$post_tags = wp_get_post_tags( $post->ID );
	$tags = array();
	foreach( $post_tags as $tag ){
		$tags[] = $tag->term_id;
	}

	$post_cats = wp_get_post_categories( $post->ID );
	$cats = array();
	foreach( $post_cats as $cat ){
		$cats[] = $cat;
	}

	$args = array(

		'tax_query' => array(
			'relation' => 'OR',
		),
		'post__not_in' 	=> array( $post->ID ),
		'posts_per_page'=> $max,
	);

	if( !empty( $cats ) )
		$args['tax_query'][] =	array(
								'taxonomy' 	=> 'category',
								'field' 	=> 'id',
								'operator' 	=> 'IN',
								'terms' 	=> $cats
							);
	if( !empty( $tags ) )
		$args['tax_query'][] = array(
								'taxonomy' 	=> 'post_tag',
								'field' 	=> 'id',
								'operator' 	=> 'IN',
								'terms' 	=> $tags
							);


	$queryStruct = new QueryStruct();
	$queryStruct->loadSettings( $args );
	$query = $queryStruct->query();
	$posts_found = $query->post_count;

	ob_start();
	agility_bloglist( $query, 'eleven' );
	$html = ob_get_contents();
	ob_end_clean();

	$queryStruct->queryCompleted();

	if( $posts_found == 0 ) return '<h6>No Related Posts</h6>';

	$html = '<h6>Related Posts</h6>'.$html;

	return $html;

}

agility_add_shortcode( 'simple_related_posts', 'agility_related_posts', array(
		'params' => array(
			'max'		=>	array(
				'title'		=> __( 'Max Posts', 'agility' ),
				'desc'		=> __( 'Maximum number of posts to display' , 'agility' ),
				'type'		=> 'text',
				'default'	=> 2,
			),
		),

		'content'	=> false
	) );


/* Put shortcodes in text widgets */
add_filter( 'widget_text' , 'do_shortcode' );
