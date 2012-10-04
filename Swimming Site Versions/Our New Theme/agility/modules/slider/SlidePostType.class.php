<?php
/**
 * SlidePostType
 *
 * Custom Post Type for Slides
 */

if(!class_exists('AgilityCustomPostType')) require_once( get_template_directory().'/modules/custom_post_types/CustomPostType.class.php');

class SlidePostType extends AgilityCustomPostType {
	
	public $slug = 'slide';
	public $name = 'Slide';
	public $name_plural = 'Slides';
			
	public function __construct( $labels = array(), $post_args = array() ){
		
		$this->baseURL = get_template_directory_uri().'/modules/'.basename( dirname( __FILE__ ) ).'/';
		
		$this->labels = $labels;
		
		$post_arg_defaults = array(
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true, 
			'show_in_menu' => true, 
			'query_var' => true,
			'rewrite' => true,
			'capability_type' => 'post',
			'has_archive' => true, 
			'hierarchical' => false,
			'menu_position' => null,
			'supports' => array(
				'title',
				'editor',
				//'author',
				'thumbnail',
				//'excerpt',
				//'comments'
			)
		);
		
		$this->post_args = wp_parse_args($post_args, $post_arg_defaults);
		
		// Call parent constructor
		parent::__construct();


		$this->addMetaBox(
			new SlideMetaBox(
				'm1',
				__( 'Slide Settings', 'agility' ),
				$this->slug,
				'side',
				'core'
			)
		);
		
		$this->addTaxonomy( 'sliders', array(
			'label' 			=> __( 'Slide Categories' , 'agility' ),
			'label_sing'		=> __( 'Slide Category' , 'agility' ),
			'public'			=> true,
			'show_in_nav_menus'	=> false,
			'hierarchical'		=> true,
			'rewrite' 			=> false, /*array( 
									'slug' => 'sliders', 
									'hierarchical' => true 
									),*/		
		));
		
		
		$this->add_action( 'do_meta_boxes', 'slide_image_box' );
		
		$this->registerAdminScript( 'slide-js', $this->baseURL.'slider.admin.js' );


		$this->registerScript( 'flexslider-js', $this->baseURL.'flexslider/jquery.flexslider-min.js' );
		$this->registerStylesheet( 'flexslider-css', $this->baseURL.'flexslider/flexslider.css');
	}

	function slide_image_box() {
	
		remove_meta_box( 'postimagediv', 'slide', 'side' );
	
		add_meta_box( 'postimagediv', __( 'Slide Image', 'agility' ), 'post_thumbnail_meta_box', 'slide', 'side', 'high' );
	
	}
	
}

class SlideMetaBox extends CustomMetaBox {
	
	public function __construct( $id, $title, $page, $context = 'side', $priority = 'default' ){
			
		parent::__construct( $id, $title, $page, $context, $priority );
		
		//$this->addField( new TextMetaField( 'text-1', 'Text' ) );
		$this->addField( new CheckboxMetaField( 'display-title', __( 'Display Title', 'agility' ), '', array( 'default' => 'on' ) ) );
		$this->addField( new CheckboxMetaField( 'display-caption', __( 'Display Caption', 'agility' ), '', array( 'default' => 'on' ) ) );

		
		$this->addField( new RadioMetaField( 'slide_type', __( 'Slide Type', 'agility' ), '', array( 'default' => 'image' ) ,
				array(
					'image'			=>	__( 'Image', 'agility' ),
					//'video-embed'	=>	'Embedded Video',
					'text'			=>	__( 'Text', 'agility' )
				)));

		$this->addField( new TextMetaField( 'slide_link', __( 'Link', 'agility' ), '', array( 'description' => __( 'Link the slide to this URL (optional)', 'agility' ) ) ) );

		/*$this->addField( new TextMetaField( 'featured_video', 'Featured Video URL (Embedded)' , '' , array( 
					'description' => '<a target="_blank" href="http://codex.wordpress.org/Embeds#Okay.2C_So_What_Sites_Can_I_Embed_From.3F">What sites can '.
									'I embed from?</a>  (Use Vimeo or YouTube for responsive support)',
				) ) );
		*/
	}
	
}

//$GLOBALS['cpt_slide'] = new SlidePostType();


