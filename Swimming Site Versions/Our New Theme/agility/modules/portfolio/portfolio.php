<?php
/**
 * The Portfolio System
 */

if(!class_exists('AgilityCustomPostType')) require_once( get_template_directory().'/modules/custom_post_types/CustomPostType.class.php');

class PortfolioPostType extends AgilityCustomPostType {
	
	public $slug = 'portfolio-item';
	public $name = 'Portfolio Item';
	public $name_plural = 'Portfolio Items';
			
	public function __construct( $labels = array(), $post_args = array() ){

		global $agilitySettings;
		
		$this->labels = $labels;

		$post_arg_defaults = array(
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true, 
			'show_in_menu' => true, 
			'query_var' => true,
			'rewrite' => array(
				'slug'	=> $agilitySettings->op( 'custom_portfolio_item_slug' ) ? $agilitySettings->op( 'custom_portfolio_item_slug' ) : 'portfolio-item',
			),
			'capability_type' => 'post',
			'has_archive' => true, 
			'hierarchical' => false,
			'menu_position' => null,
			'supports' => array(
				'title',
				'editor',
				//'author',
				'thumbnail',
				'excerpt',
				//'comments'
				'custom-fields',
			)
		);

		$this->post_args = wp_parse_args($post_args, $post_arg_defaults);

		$this->addMetaBox(
			new PortfolioSettingsMetaBox(
				'portfolio_item_settings',
				__( 'Portfolio Item Settings', 'agility' ),
				$this->slug,
				'side',
				'core'
			)
		);
		
		$this->addTaxonomy( 'portfolio-categories', array(
			'label' 			=> __( 'Portfolio Categories' , 'agility' ),
			'label_sing'		=> __( 'Portfolio Category' , 'agility' ),
			'public'			=> true,
			'show_in_nav_menus'	=> true,
			'hierarchical'		=> true,
			'rewrite' 			=> array( 
									'slug' => 'portfolio-category',
									'hierarchical' => true 
									),
		));

		$this->addTaxonomy( 'portfolio-tags', array(
			'label' 			=> __( 'Portfolio Tags' , 'agility' ),
			'label_sing'		=> __( 'Portfolio Tag' , 'agility' ),
			'public'			=> true,
			'show_in_nav_menus'	=> true,
			'hierarchical'		=> false,
			'rewrite' 			=> array( 
									'slug' => 'portfolio-tag', 
									'hierarchical' => false 
									),		
		));
		
		// Call parent constructor
		parent::__construct();
		
		$this->add_action( 'do_meta_boxes', 'image_box');
		
	}

	function image_box() {
	
		remove_meta_box( 'postimagediv', 'portfolio-item', 'side' );
	
		add_meta_box( 'postimagediv', __( 'Portfolio Image' , 'agility' ), 'post_thumbnail_meta_box', 'portfolio-item', 'side', 'high' );
	
	}
	
}

class PortfolioSettingsMetaBox extends CustomMetaBox {
	
	public function __construct( $id, $title, $page, $context = 'side', $priority = 'default' ){
			
		parent::__construct( $id, $title, $page, $context, $priority );

		$this->addField( new SelectMetaField( 'feature_type', __( 'Feature Type', 'agility' ), '', array() ,
				agility_feature_types() ));

		$this->addField( new TextMetaField( 'featured_video', __( 'Featured Video URL (Embedded)', 'agility' ) , '' , array( 
					'description' => '<a target="_blank" href="http://codex.wordpress.org/Embeds#Okay.2C_So_What_Sites_Can_I_Embed_From.3F">What sites can '.
									'I embed from?</a>  (Use Vimeo or YouTube for responsive support)',
				) ) );

		global $agilitySettings;
		$this->addField( new CheckboxMetaField( 'crop_feature', __( 'Crop Featured Image?', 'agility' ), '', 
			array( 'description' => 'This affects the single item only.  Whether the entire portfolio will be cropped or not is set in the Portfolio Page settings.', 
				'default' => $agilitySettings->op( 'crop-feature' ) ? 'on' : 'off' ) ) );


		$this->addField( new CheckboxMetaField( 'display-title', __( 'Display Titles in Slider?', 'agility' ), '', 
			array( 'description' => '[Image Slider Only] Turn the titles on or off for slides in the Image Slider', 
				'default' => 'on' ) ) );

		$this->addField( new CheckboxMetaField( 'display-caption', __( 'Display Captions in Slider?', 'agility' ), '', 
			array( 'description' => '[Image Slider Only] Turn the captions on or off for slides in the Image Slider', 
				'default' => 'on' ) ) );
				
	}
	
}

class PortfolioMetaBox extends CustomMetaBox{

	public function __construct( $id, $title, $page, $context = 'side', $priority = 'default' ){
			
		parent::__construct( $id, $title, $page, $context, $priority );

		//$this->addField( new TextAreaMetaField( 'portfolio_subtitle', 'Portfolio Subtitle' ) );
		
		$this->addField( new SelectMetaField( 'grid_columns', __( 'Columns', 'agility' ), '', array( 
			'description' => __( 'The number of columns for this portfolio (or items per row)', 'agility' ) ), array(
					'f'		=>	__( 'One', 'agility' ),
					'1-2'	=>	__( 'Two', 'agility' ),
					'1-3'	=>	__( 'Three', 'agility' ),
					'1-4'	=>	__( 'Four', 'agility' ),
				)));

		$this->addField( new SelectMetaField( 'content_order', __( 'Display page contents', 'agility' ), '', array(), array(
					'above'	=>	__( 'Above Portfolio', 'agility' ),
					'below'	=>	__( 'Below Portfolio', 'agility' ),
				)));

		$this->addField( new CheckboxMetaField( 'portfolio_show_title', __( 'Show Title', 'agility' ) , '', array( 'default' => 'on', 'description' =>
				__( 'Display the title of each portfolio item below the featured image.  Required if you want to link to the Portfolio Item itself.', 'agility' ) ) ) );
		$this->addField( new CheckboxMetaField( 'portfolio_show_excerpt', __( 'Show Excerpt', 'agility' ) , '', array( 'default' => 'on', 'description' =>
				__( 'Display the excerpt for each portfolio item below the featured image', 'agility' ) ) ) );

		$this->addField( new CheckboxMetaField( 'portfolio_crop_items', __( 'Crop Items', 'agility' ) , '', array( 'default' => 'off' , 'description' => 
				__( 'Crop all items to a consistent aspect ratio as defined in the Agility Control Panel', 'agility' ) ) ) );

		//$this->addField( new TaxonomyMultiselectField( 'portfolio_category', 'Include items by Category' , '', 'portfolio-categories', array() ) );
		//$this->addField( new TaxonomyMultiselectField( 'portfolio_tag', 'Include items by Tag' , '', 'portfolio-tags', array() ) );

		$this->addField( new PortfolioQueryField( 'portfolio_query' , __( 'Portfolio', 'agility' ), '', array( 'description' => 
				__( 'Select the portfolio to display', 'agility' ).', or <a href="'.admin_url( 'admin.php?page=portfolioconstructor' ).'" target="_blank">create a new portfolio</a>') ) );

		$this->addField( new CheckboxMetaField( 'portfolio_isotope', __( 'Make Filterable', 'agility' ) , '', array( 'default' => 'off', 
			'description' => __( 'Allow the portfolio to be dynamically filtered by one of the criteria below', 'agility' ) ) ) );
		$this->addField( new CheckboxMetaField( 'portfolio_filterby_author', __( 'Filter by author', 'agility' ) , '', array( 'default' => 'off' ) ) );
		$this->addField( new CheckboxMetaField( 'portfolio_filterby_portfolio_category', __( 'Filter by Portfolio Category', 'agility' ) , '', array( 'default' => 'off' ) ) );
		$this->addField( new CheckboxMetaField( 'portfolio_filterby_portfolio_tag', __( 'Filter by Portfolio Tag', 'agility' ) , '', array( 'default' => 'off' ) ) );
		$this->addField( new CheckboxMetaField( 'portfolio_filterby_post_category', __( 'Filter by Post Category', 'agility' ) , '', array( 'default' => 'off',
			'description' => __( 'Specifically for use with Portfolios constructed from Posts', 'agility' ) ) ) );
		$this->addField( new CheckboxMetaField( 'portfolio_filterby_post_tag', __( 'Filter by Post Tag', 'agility' ) , '', array( 'default' => 'off' ,
			'description' => __( 'Specifically for use with Portfolios constructed from Posts', 'agility' ) ) ) );

	}

}

class PortfolioQueryField extends MetaField{

	public function __construct( $id, $label, $name='', $args = array() ){
		
		$this->type = 'select';	
		parent::__construct( $id, $label, $name, $args , array() );
		
	}

	public function showField( $value = '' ){
		
		$this->options = agility_portfolio_ops();

		?>
		<p>
		<strong><label for="<?php echo $this->id; ?>"><?php echo $this->label; ?></label></strong><br/>
		<select name="<?php echo $this->name; ?>" id="<?php echo $this->id; ?>" >
			<?php foreach( $this->options as $id => $name ): ?>
			<option value="<?php echo $id; ?>" <?php selected( $id, $value ); ?>><?php echo $name; ?></option>
			<?php endforeach; ?>
		</select>
		<?php $this->showDescription(); ?>
		</p>
		<?php
		
	}	

}

class TaxonomyMultiselectField extends MetaField{

	private $taxonomy;

	public function __construct( $id, $label, $name='', $taxonomy, $args = array() ){
		
		$this->type = 'multiselect';	
		parent::__construct( $id, $label, $name, $args , array() );			
		$this->taxonomy = $taxonomy;
		
	}

	public function showField( $value = '' ){
		
		$this->options = get_terms( $this->taxonomy );

		?>
		
		<label for="<?php echo $this->id; ?>"><?php echo $this->label; ?></label><br/>
		<select name="<?php echo $this->name; ?>[]" id="<?php echo $this->id; ?>" multiple="multiple" >
			<?php foreach( $this->options as $term ): ?>
			<option value="<?php echo $term->term_id; ?>" ><?php echo $term->name; ?></option>
			<?php endforeach; ?>
		</select>
		<?php
		
	}	

}

//$GLOBALS['cpt_portfolio'] = new PortfolioPostType();
//$GLOBALS['cpt_portfolio_metabox'] = new PortfolioMetaBox( 'portfolio_settings', 'Portfolio Settings', 'page', 'side' );


/**
 * Template Tag: Featured Items
 */
if ( ! function_exists( 'agility_featured_items' ) ) :
function agility_featured_items( $query_id = 0, $config_type = 'query_id', $set_alphaomega = false , $crop = false ){

	$query = null;
	$queryStruct = null;

	switch( $config_type ){

		case 'query_id':
			$queryStruct = new PortfolioQueryStruct( $query_id );
			$query = $queryStruct->query();
			break;

		default:
			if( is_null( $query ) ){
				global $wp_query;
				$query = $wp_query;
			}	
			break;
	}

	global $featured_image_size;
	$temp_crop_size;
	if( $crop ){
		$temp_crop_size = $featured_image_size;
		$featured_image_size = 'crop_640';
	}

	
	$k = 0;	

	?>

			<div class="mosaic row cf" id="featured-columns">
				<?php while ( $query->have_posts() ){
					$query->the_post();
					
					$GLOBALS['alphaomega'] = '';
					if( $set_alphaomega ) $GLOBALS['alphaomega'] = agility_alphaomega( $k , 3 );

					get_template_part( 'content', 'featured-item' );
					$k++;
				} ?>				
			</div>


	<?php

	if( $queryStruct) $queryStruct->queryCompleted();

	if( $crop ){
		//reset
		$featured_image_size = $temp_crop_size;
	}
}
endif; 

//Add post categories and tags to portfolio classes
function agility_portfolio_post_class_filter( $classes, $class, $post_id ){
	$post = get_post( $post_id );
	if( $post->post_type == 'portfolio-item' ){

		//Categories
		$terms = agility_get_post_terms( $post_id , 'portfolio-categories' );
		foreach( $terms as $term ){
			$classes[] = 'portfolio-category-'.$term->slug;
		}

		//Tags
		$terms = agility_get_post_terms( $post_id , 'portfolio-tags' );
		foreach( $terms as $term ){
			$classes[] = 'portfolio-tag-'.$term->slug;
		}

	}
	return $classes;
}
add_filter( 'post_class', 'agility_portfolio_post_class_filter', 10, 3 );



function agility_portfolio_filters( $post, $portfolio_query, $portfolio_query_id ){
	//Filters / Isotope
	$filterable 		= get_post_meta( $post->ID , 'portfolio_isotope' , true ) == 'on' ? true : false;
	$filterby_author 	= get_post_meta( $post->ID , 'portfolio_filterby_author' , true ) == 'on' ? true : false;
	$filterby_port_cat 	= get_post_meta( $post->ID , 'portfolio_filterby_portfolio_category' , true ) == 'on' ? true : false;
	$filterby_port_tag 	= get_post_meta( $post->ID , 'portfolio_filterby_portfolio_tag' , true ) == 'on' ? true : false;
	$filterby_post_cat 	= get_post_meta( $post->ID , 'portfolio_filterby_post_category' , true ) == 'on' ? true : false;
	$filterby_post_tag 	= get_post_meta( $post->ID , 'portfolio_filterby_post_tag' , true ) == 'on' ? true : false;

	if( $filterable == true ): ?>
								<div class="isotope-filters-wrap sixteen columns cf">

								<?php
									//ssd( get_post_custom( $post->ID ) );
								if( $portfolio_query->have_posts() ): 

									$filterby_author_arr	= array();
									$filterby_port_cat_arr 	= array();
									$filterby_port_tag_arr 	= array();
									$filterby_post_cat_arr 	= array();
									$filterby_post_tag_arr 	= array();
									
									while( $portfolio_query->have_posts() ){
										$portfolio_query->the_post(); 
										global $post;

										if( $filterby_author ){
											//$filterby_author_html.= '<li><a class="button" href="#" data-filter=".author-'.$post->post_author.'">'.
											//						get_the_author().'</a></li>';
											$filterby_author_arr[$post->post_author] = get_the_author();
										}
										if( $filterby_post_cat ){
											$terms = agility_get_post_terms( $post->ID, 'category');
											foreach( $terms as $term ){
  												$filterby_post_cat_arr[$term->slug] = $term->name;
  											}
										}
										if( $filterby_port_tag ){
											$terms = agility_get_post_terms( $post->ID, 'portfolio-tags' );
											foreach( $terms as $term ){
  												$filterby_port_tag_arr[$term->slug] = $term->name;
  											}
										}

										if( $filterby_port_cat ){
											$terms = agility_get_post_terms( $post->ID, 'portfolio-categories' );
											foreach( $terms as $term ){
  												$filterby_port_cat_arr[$term->slug] = $term->name;
  											}
										}

										if( $filterby_post_tag ){
											$terms = agility_get_post_terms( $post->ID, 'post_tag' );
											foreach( $terms as $term ){
  												$filterby_post_tag_arr[$term->slug] = $term->name;
  											}
										}

									}

									if( $filterby_author ){
										?>
										<ul class="isotope-filters filterby-author cf" data-portfolio="<?php echo $portfolio_query_id; ?>">
											<li><a class="button" href="#" data-filter="*"><?php _e( 'Show all authors', 'agility' ); ?></a></li>

											<?php foreach( $filterby_author_arr as $author_id => $author_name ): ?>
											<li><a class="button" href="#" data-filter=".author-<?php echo $author_id; ?>"><?php echo $author_name; ?></a></li>
											<?php endforeach; ?>

										</ul>
										<?php
									}
									
									

									if( $filterby_port_cat ){
										?>
										<ul class="isotope-filters filterby-port-cat cf" data-portfolio="<?php echo $portfolio_query_id; ?>">
											<li><a class="button" href="#" data-filter="*"><?php _e( 'Show all Portfolio Categories', 'agility' ); ?></a></li>

											<?php foreach( $filterby_port_cat_arr as $slug => $name ): ?>
											<li><a class="button" href="#" data-filter=".portfolio-category-<?php echo $slug; ?>"><?php echo $name; ?></a></li>
											<?php endforeach; ?>

										</ul>
										<?php
									}

									if( $filterby_port_tag ){
										?>
										<ul class="isotope-filters filterby-post-tag cf" data-portfolio="<?php echo $portfolio_query_id; ?>">
											<li><a class="button" href="#" data-filter="*"><?php _e( 'Show all Portfolio Tags', 'agility' ); ?></a></li>

											<?php foreach( $filterby_port_tag_arr as $slug => $name ): ?>
											<li><a class="button" href="#" data-filter=".portfolio-tag-<?php echo $slug; ?>"><?php echo $name; ?></a></li>
											<?php endforeach; ?>

										</ul>
										<?php
									}

									if( $filterby_post_cat ){
										?>
										<ul class="isotope-filters filterby-post-cat cf" data-portfolio="<?php echo $portfolio_query_id; ?>">
											<li><a class="button" href="#" data-filter="*"><?php _e( 'Show all Categories', 'agility' ); ?></a></li>

											<?php foreach( $filterby_post_cat_arr as $slug => $name ): ?>
											<li><a class="button" href="#" data-filter=".category-<?php echo $slug; ?>"><?php echo $name; ?></a></li>
											<?php endforeach; ?>

										</ul>
										<?php
									}

									if( $filterby_post_tag ){
										?>
										<ul class="isotope-filters filterby-post-tag cf" data-portfolio="<?php echo $portfolio_query_id; ?>">
											<li><a class="button" href="#" data-filter="*"><?php _e( 'Show all Tags', 'agility' ); ?></a></li>

											<?php foreach( $filterby_post_tag_arr as $slug => $name ): ?>
											<li><a class="button" href="#" data-filter=".tag-<?php echo $slug; ?>"><?php echo $name; ?></a></li>
											<?php endforeach; ?>

										</ul>
										<?php
									}

								?>									

								</div>
								<?php endif; ?>					

	<?php 

		return true;

	endif;

	return false;
}


/*
 * Set Portfolio ID
 * Set Portfolio Meta
 * Figure out Items Per Row
 * 
 */
//function agility_setup_portfolio( $grid_columns , $container_columns = 16 , $items_per_row = 1 ){}


/**
 * The Agility Portfolio Shortcode
 */
function agility_portfolio_shortcode( $atts ) {

	global $shortcodeMaster;
	extract( shortcode_atts( $shortcodeMaster->getDefaults( 'portfolio' ), $atts ) );

	$items_per_row = agility_divide_columns( $grid_columns, $container );
	$col_x = 'col-'.$items_per_row;

	//Portfolio Query
	$queryStruct 		= new PortfolioQueryStruct( $id );
	$portfolio_query 	= $queryStruct->query();
	$filtered 			= false;	//can't filter because it's based on post params

	$GLOBALS['portfolio_id'] = $id;
	$GLOBALS['portfolio_meta'] = array();
	global $portfolio_meta;
	$portfolio_meta['grid_columns'][0] = $grid_columns;
	$portfolio_meta['portfolio_show_title'][0] = $title;
	$portfolio_meta['portfolio_show_excerpt'][0] = $excerpt;
	$portfolio_meta['items_per_row'] = $items_per_row;
	$portfolio_meta['wrap'] = $wrap == 'on' ? true : false;
	$portfolio_meta['portfolio_crop_items'][0] = $crop == 'on' ? true : false;

	ob_start();
	?>

	<div id="portfolio-<?php echo $id; ?>" class="portfolio <?php echo $col_x; ?> <?php if( $filtered ): ?>isotope-container <?php endif; ?>clearfix">

	<?php

		if( $portfolio_query->have_posts() ){ 
			$k = 0;
			while( $portfolio_query->have_posts() ){

				$GLOBALS['portfolio_index'] = $k;
				
				$portfolio_query->the_post();
				get_template_part( 'content', 'portfolio-grid' );

				$k++;
			}
		}

		$queryStruct->queryCompleted();
	?>

	</div>

	<?php
	$html = ob_get_contents();
	ob_end_clean();
	return $html;
}
agility_add_shortcode( 'portfolio' , 'agility_portfolio_shortcode', array( 
		'params'	=> array(
			'id'			=>	array(
				'title'		=> 'Portfolio',
				'desc'		=> 'ID of the Portfolio form the Portfolio Constructor',
				'type'		=> 'select',
				'ops'		=> 'agility_portfolio_ops',
				'default' 	=> '',
			),
			'grid_columns'	=>	array(
				'title'		=> 'Grid Columns',
				'desc'		=> 'The width of an individual portfolio item in grid columns. If placed in a full-width (16 column) layout, this would create a 4-column portfolio (16/4 = 4). Normally you would want to choose a number that divides evenly into the container value. f indicates full-width. The fraction values will work with either 11 or 16 column containers.',
				'type'		=> 'select',
				'ops'		=> array(
					'1-4'	=> 'Quarter (1/4)',
					'1-3'	=> 'Third (1/3)',
					'1-2'	=> 'Half (1/2)',
					'2-3'	=> 'Two Thirds (2/3)',
					'f'		=> 'Full Width (1/1)',
					'2'		=> '2',
					'3'		=> '3',
					'4'		=> '4',
					'5'		=> '5',
					'6'		=> '6',
					'7'		=> '7',
					'8'		=> '8',
					'9'		=> '9',
					'10'	=> '10',
					'11'	=> '11',
					'12'	=> '12',
					'13'	=> '13',
					'14'	=> '14',
					'15'	=> '15',
					'16'	=> '16',

				),
				'default' 	=> '1-4',
			),
			'container'		=>	array(
				'title'		=> 'Container Columns',
				'desc'		=> 'The width of the portfolio\'s container in grid columns. For example, if placed in a blog post or a non-full-width page, you would set this to 11. This is important so that Agility can properly calculate the number of items per row.',
				'type'		=> 'select',
				'ops'		=> array( '16' => '16', '11' => '11' ),
				'default' => 16, //if not 11 or 16, must set
			),
			'title'			=>	array(
				'title'		=> 'Display Title',
				'desc'		=> 'Display the title of each item',
				'type'		=> 'checkbox',
				'default' 	=> 'on',
			),
			'excerpt'		=>	array(
				'title'		=> 'Excerpt',
				'desc'		=> 'Display the excerpt',
				'type'		=> 'checkbox',
				'default' 	=> 'off',
			),
			'wrap'			=>	array(
				'title'		=> 'Wrap',
				'desc'		=> 'Enable if this portfolio is already wrapped in columns - so it will use alpha and omega appropriately',
				'type'		=> 'checkbox',
				'default' 	=> 'on',
			),
			'crop'			=> array(
				'title'		=> 'Crop',
				'desc'		=> 'Crop the portfolio images to the aspect ratio prescribed in the Control Panel.',
				'type'		=> 'checkbox',
				'default' 	=> 'off',
			),
		),
		'content'	=> false,

	) );