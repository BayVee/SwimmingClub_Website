<?php
/**
 * BrickLayer
 *
 * Builds the BrickLayer interface
 *
 */


require_once( 'BrickPostType.class.php' );
require_once( 'BrickLayoutPostType.class.php' );
require_once( 'BrickLayout.class.php' );
require_once( 'Blueprint.class.php' );


class BrickLayer{

	public $id;
	public $title;
	public $menu_page;
	public $menu_type;
	public $parent_slug;
	public $page_title;
	public $menu_title;
	public $capability;
	public $menu_slug;

	public $config;

	private $baseURL;


	private $layouts;
	private $brickLayout_id;
	private $brickLayout;

	private $nonce_action;
	private $nonce_name;

	function __construct( $id, $config = array() ){

		$this->id = $id;
		$this->config = $config;
		$this->baseURL = get_template_directory_uri().'/modules/'.basename( dirname( __FILE__ ) ).'/';

		$this->loadBricks();

		if( is_admin() ){

			//add_action( 'admin_menu' , array( $this , 'updateSettings' ) , 100 );
			add_action( 'admin_menu' , array( $this , 'initialize' ) , 110 );
			add_action( 'wp_ajax_bricklayer_save_layout', array( &$this , 'ajaxSave' ) );
			add_action( 'wp_ajax_bricklayer_delete_layout', array( &$this , 'ajaxDelete' ) );
		}

		$this->nonce_action = 'bricklayer_nonce_action';
		$this->nonce_name = 'bricklayer_wpnonce';

	}

	function loadBricks(){
		require_once( 'Brick.class.php' );
		$this->loadBrick( 'DummyBrick' );
		$this->loadBrick( 'TaglineBrick' );
		$this->loadBrick( 'PageContentBrick' );
		$this->loadBrick( 'BlogGridBrick' );
		$this->loadBrick( 'PortfolioBrick' );
		$this->loadBrick( 'SliderBrick' );
		$this->loadBrick( 'MapBrick' );
		$this->loadBrick( 'CustomBrick' );
		$this->loadBrick( 'SidebarBrick' );
		$this->loadBrick( 'TwitterBrick' );
		$this->loadBrick( 'HRBrick' );
		$this->loadBrick( 'FeaturedItemsBrick' );
	}

	function loadBrick( $brick_name ){
		require_once( 'bricks/'.$brick_name.'.class.php' );
	}

	function initialize(){

		extract( wp_parse_args( $this->config, array(
			
			//'type'		=>	'submenu_page',
			//'parent_slug'	=>	'options-general.php',
			'page_title'	=>	'BrickLayer',
			'menu_title'	=>	'BrickLayer',
			'capability'	=>	'manage_options',
			'menu_slug'		=>	$this->id,
			
		)));

		//echo "<br/><br/>$page_title | $menu_title | $capability | $menu_slug";
		
		$this->title 		= $menu_title;
		//$this->menu_type 	= $type;
		//$this->parent_slug 	= $parent_slug;
		$this->page_title 	= $page_title;
		$this->menu_title 	= $menu_title;
		$this->menu_slug 	= $menu_slug;
		$this->capability 	= $capability;

		$this->menu_page = add_submenu_page( 
			'agility-settings',
			$this->page_title , //'sparkoptions', 
			$this->menu_title , //'sparkoptions', 
			$this->capability, 
			$this->menu_slug,
			array( $this, 'showUI' )
			//$this->baseURL.'images/bolt.png'
		);

		$this->loadAssets();
		
	}
	
	function loadAssets(){
		add_action("admin_print_styles-{$this->menu_page}", array( $this , 'loadCSS' ) );
		add_action("admin_print_styles-{$this->menu_page}", array( $this , 'loadJS' ) );
	}
	
	function loadCSS(){
		//$tmp = plugins_url().'/'.str_replace( basename( __FILE__ ),"",plugin_basename( __FILE__ ));
		wp_enqueue_style( 'bricklayer-css', $this->baseURL.'bricklayer.css', false, false, 'all' );
		//wp_enqueue_style( 'jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/smoothness/jquery-ui.css');
		
	}
	function loadJS(){
		//$tmp = plugins_url().'/'.str_replace( basename( __FILE__ ),"",plugin_basename( __FILE__ ));
		wp_enqueue_script( 'jquery' );	// Load jQuery
		wp_enqueue_script( 'jquery-ui-sortable' ); //jQuery Sortable
		wp_enqueue_script( 'jquery-ui-draggable' );
		wp_enqueue_script( 'jquery-ui-droppable' );
		wp_enqueue_script( 'jquery-ui-nested-sortable' , $this->baseURL.'jquery.ui.nestedSortable.js' , array( 'jquery', 'jquery-ui-sortable' ) , false , true );
		wp_enqueue_script( 'bricklayer-js' , $this->baseURL.'bricklayer.js' , array( 'jquery', 'jquery-ui-sortable' ) , false , true );

		//wp_enqueue_script( 'sparkoptions-js', 	$tmp.'/sparkoptions.js', 	false, '1.1', 'all');
		
	}

	function setLastLayout(){
		//echo '<br/><br/>set last query : qc_last_query_'.$this->type.' :: '.$this->query_id;
		update_option( 'bricklayer_last_layout', $this->brickLayout_id );
	}
	function getLastLayout(){
		return get_option( 'bricklayer_last_layout', 0 );	
	}

	function showUI(){

		if ( !current_user_can( $this->capability ) ) {
			wp_die( __('You do not have sufficient permissions to access this page.', 'agility') );
		}

		$this->brickLayout_id = -1;	//TODO (get from GET)
		if( isset( $_GET['layout_id'] ) ){
			$this->brickLayout_id = $_GET['layout_id'];
		}
		else{
			//TODO get last saved
			$this->brickLayout_id = $this->getLastLayout();
		}

		if( $this->brickLayout_id > 0 ){
			$this->setLastLayout();
		}

		$this->brickLayout = new BrickLayout( $this->brickLayout_id );
		$blueprint = $this->brickLayout->getBlueprint();

		?>
		<div>

			<div id="bricklayer_ui">

				<h2 id="brick_layer_title">BrickLayer <img src="<?php echo $this->baseURL.'images/bolt_red.png'; ?>" /></h2>
				<span id="brickhelp" class="info-container tooltip" data-tooltip="Click for help">?</span>

				<div id="brick_recycle">
					<span class="recycle-icon"></span>
					<ul class="sortable connectedSortable tooltip" data-tooltip="<?php _e( 'Drag bricks to the recycling bin to remove them from the layout, but not delete them from the database.', 'agility' ); ?>">
						<li class="info"><?php _e( 'Drag items to the recycling bin to remove them.', 'agility' ); ?></li>
					</ul>
				</div>

				<form id="bricklayer_form" method="POST">

					<?php wp_nonce_field( $this->nonce_action, $this->nonce_name ); ?>

					<div id="layout_meta">
						<div id="brick_notifications"></div>
						<div id="layout_selector">
							<input type="hidden" name="layout_id" id="layout_id" value="<?php echo $this->brickLayout->getID(); ?>" />
							<span id="layout_current" class="tooltip" data-tooltip="<?php _e( 'Click the title to rename this layout', 'agility' ); ?>"><input type="text" name="layout_title" value="<?php 
								echo $this->brickLayout->getTitle(); ?>"/></span>
							<span id="layout_selector_arrow"  class="tooltip" data-tooltip="<?php _e( 'Click to switch layouts or create a new layout.', 'agility' ); ?>"></span>
							<ul>
								<?php
								$layouts = $this->getSavedLayouts();
								$num_layouts = count( $layouts );
								foreach( $layouts as $layout ){
									?><li><a href="<?php echo admin_url( 'admin.php?page=bricklayer&layout_id='.$layout->ID ); ?>" ><?php 
									echo $layout->post_title; ?></a></li>
									<?php
								}
								?>

								<li><a href="<?php echo admin_url( 'admin.php?page=bricklayer&layout_id=-1' ); 
									?>"><?php _e( 'Create New Layout', 'agility' ); ?></a></li>
								
							</ul>

						</div>

						<div id="layout_save">
							<input type="submit" value="Save Layout" class="button-red tooltip" data-tooltip="<?php _e( 'Commit your changes and save your layout', 'agility' ); ?>"/>
							<?php if( $this->brickLayout_id != -1 ): ?>
							<div id="bricklayer_delete" class="tooltip" data-tooltip="<?php _e( 'Completely delete this layout.  This cannot be undone.', 'agility' ); ?>"></div>
							<?php endif; ?>
						</div>

					</div>

					<div id="layout_builder">
						<div id="secondary">

							<div id="layout_settings" class="toggle toggle-closed" >
								<h3><?php _e( 'Layout Settings', 'agility' ); ?></h3>

								<div class="toggle-content">
									<label for="layout_display_title"><?php _e( 'Display Title', 'agility' ); ?></label>
									<?php $display_title = get_post_meta( $this->brickLayout->getID() , '_display_title', true );  if( !$display_title ) $display_title = 'content';  ?>
									<br/><input type="radio" value="off" name="layout_display_title" id="layout_display_title_off" <?php checked( $display_title , 'off' ); ?> /><label for="layout_display_title_off"><?php _e( 'Off', 'agility' ); ?></label>
									<br/><input type="radio" value="feature" name="layout_display_title" id="layout_display_title_feature" <?php checked( $display_title , 'feature' ); ?> /><label for="layout_display_title_feature"><?php _e( 'Above Featured Area', 'agility' ); ?></label>
									<br/><input type="radio" value="content" name="layout_display_title" id="layout_display_title_content" <?php checked( $display_title , 'content' ); ?> /><label for="layout_display_title_content"><?php _e( 'Above Content Area', 'agility' ); ?></label>
								</div>
							</div>


							<div id="scaffold_picker" class="toggle toggle-closed">
								<h3 class="tooltip" data-tooltip="<?php _e( 'Select the basic structure of your layout.  You can change this any time.', 'agility' ); ?>"><?php _e( 'Select a Blueprint', 'agility' ); ?></h3>
								<div class="toggle-content">

									<?php 
									$blueprint_op_right = Blueprint::createBlueprint( '1sidebar_right' );
									$blueprint_op_right->showMiniBlueprint( $blueprint->blueprint_type );

									$blueprint_op_left = Blueprint::createBlueprint( '1sidebar_left' );
									$blueprint_op_left->showMiniBlueprint( $blueprint->blueprint_type );																		

									$blueprint_op_none = Blueprint::createBlueprint( '0sidebar' );
									$blueprint_op_none->showMiniBlueprint( $blueprint->blueprint_type );

									?>
								</div>

							</div>

							<div id="bricks" class="toggle brick-side">
								<h3 class="tooltip" data-tooltip="<?php _e( 'Drag bricks from this area into the layout areas to build your layout', 'agility' ); ?>">Bricks</h3>

								<div class="toggle-content">
									<ul>
										<?php
										
										$page_content = new PageContentBrick();
										$page_content->showUI();

										$tagline = new TaglineBrick();
										$tagline->showUI();

										$portfolio = new PortfolioBrick();
										$portfolio->showUI();

										$featuredItems = new FeaturedItemsBrick();
										$featuredItems->showUI();

										$slider = new SliderBrick();
										$slider->showUI();

										$blogGrid = new BlogGridBrick();
										$blogGrid->showUI();

										$map = new MapBrick();
										$map->showUI();

										$custom = new CustomBrick();
										$custom->showUI();

										$hr = new HRBrick();
										$hr->showUI();

										$twitter = new TwitterBrick();
										$twitter->showUI();

										$sidebar = new SidebarBrick();
										$sidebar->showUI();

										?>

									</ul>
								</div>
							</div>

							<?php $saved_bricks = $this->getSavedBricks();
							if( count( $saved_bricks ) ): ?>
							<div id="saved_bricks" class="toggle brick-side">

								<h3><?php _e( 'Bookmarked Bricks', 'agility' ); ?></h3>

								<div class="toggle-content">
									<ul class="sortable connectedSortable">
										<?php
										foreach( $saved_bricks as $brick ){
											?>
											<li><?php
												$brick = Brick::createBrick( $brick->ID );
												if($brick) $brick->showUI();
											?></li>
											<?php
										}
										?>
									</ul>
								</div>

							</div>
							<?php endif; ?>

						</div>

						<div id="primary">
							<div id="layout" class="layout-blueprint_<?php echo $blueprint->blueprint_type; ?>">
								<h4 class="tooltip" data-tooltip="<?php _e( 'This area serves as the canvas for your layout.  
									Drag bricks into the Feature, Content, or Sidebar areas to build your layout.', 'agility' ); ?>"><?php _e( 'Layout', 'agility' ); ?></h4>

								

								<div id="feature_area" class="brick-container brick-container-16">
									<h5 class="tooltip" data-tooltip="<?php _e( 'The Feature Area is great for sliders or other elements that should appear above the main content', 'agility' ); ?>"><?php _e( 'Feature Area', 'agility' ); ?></h5>
									<ul class="sortable connectedSortable">
										<?php $this->brickLayout->showFeatureBricks(); ?>
									</ul>
								</div>

								<div id="content_area" class="brick-container brick-container-<?php echo $blueprint->columns['content'] == 'sixteen' ? 16 : 11; ?>">
									<h5 class="tooltip" data-tooltip="<?php _e( 'The Content Area should hold the main content for the page.', 'agility' ); ?>"><?php _e( 'Content Area', 'agility' ); ?></h5>
									<ul class="sortable connectedSortable">
										<?php $this->brickLayout->showContentBricks(); ?>
									</ul>
								</div>

								<div id="sidebar_area" class="brick-container brick-container-4">
									<h5 class="tooltip" data-tooltip="<?php _e( 'The Sidebar Area holds secondary content', 'agility' ); ?>" ><?php _e( 'Sidebar Area', 'agility' ); ?></h5>
									<ul class="sortable connectedSortable">
										<?php $this->brickLayout->showSidebarBricks(); ?>
									</ul>
								</div>

								<div style="clear:both;"></div>
							</div>
						</div>
					</div>
				</form>



			</div>

			<div id="loadingBricks"></div>

			<div id="brickLayer_welcome" class="<?php if( $num_layouts > 0 ) echo 'welcome-hide'; ?>">
				<div id="brickLayer_welcome_inner">
					<a href="#" class="brickLayer_welcome_close get-started">&times;</a>
					<ul class="tabs">
						<li><a href="#tab-1" class="active">Overview</a></li>
						<li><a href="#tab-2">BrickLayer Anatomy</a></li>
						<li><a href="#tab-3">Video Tutorials</a></li>
					</ul>
					<ul class="tabs-content">
						<li id="tab-1" class="active">

							<h2>Welcome to BrickLayer!</h2>
							
							<p>BrickLayer is Agility's visual page layout builder.  Here are some quick tips:</p>

							<ol>
								<li><strong>Using BrickLayer is completely optional.</strong> <br/>
									<p>Agility comes with a variety of 
									Page templates that will likely suit the majority of your needs.  You can 
									also fully customize those templates using a Child Theme.  BrickLayer is 
									here to add a bit of extra customizability for those who aren't developers,
									but need more advanced layouts</p></li>

								<li><strong>Use this drag and drop interface to create a layout.</strong><br/>
									<p><em>Bricks</em> are units of content that will be displayed on the front end 
									of your site.  Drag the blue bricks onto the layout area and arrange them how 
									you like.  Each brick displays different content, and many can be customized.</p></li>

								<li><strong>Applying Layouts</strong><br/>
									<p>Give your layout a descriptive title, and save it.  You can then apply that
									layout to any Page (but not posts) by selecting <strong>BrickLayer Custom Template</strong> 
									as your <em>Page Template</em>, and then selecting your new BrickLayer Layout
									in the BrickLayer meta box</p></li>

								<li>You can find detailed instructions with screenshots here: 
									<strong><a class="button" target="_blank" href="http://agility.sevenspark.com/help/#bricklayer">BrickLayer Instructions</a></strong>
								</li>
							</ol>
						</li>
						<li id="tab-2">
							<a href="http://i.imgur.com/Acemn.png" target="_blank"><img width="800" src="http://i.imgur.com/Acemn.png" /></a>
						</li>
						<li id="tab-3">
							<p>The playlist of BrickLayer tutorials is below.  <a target="_blank" href="http://www.youtube.com/playlist?list=PL5296DA8801029AB9">View on YouTube</a>
							</p>
							<br/>
							<iframe width="800" height="407" src="http://www.youtube.com/embed/videoseries?list=PL5296DA8801029AB9&amp;hl=en_US" frameborder="0" allowfullscreen></iframe>
						</li>
					</ul>
					<p>
					<a href="#" class="button button-red get-started">Get Started</a>
					</p>
				</div>
			</div>
		</div>

		<?php

	}

	function ajaxSave(){

		//Check Nonce!
		check_ajax_referer( $this->nonce_action, $this->nonce_name );

		$brick_data_serial 	= $_POST['brick_data'];		//serialized
		$layout_areas 		= $_POST['layout_areas'];	//array (json)
		$brick_data 		= array();
		parse_str( $brick_data_serial, $brick_data );
		
		//print_r($layout_areas);
		//print_r($brick_data);

		$layout_id = $brick_data['layout_id'];
		$layout_title = $brick_data['layout_title'];

		$brickLayout = new BrickLayout( $layout_id , true , $layout_title , false );
		$result = $brickLayout->save( $layout_areas, $brick_data );

		//if this was a new BrickLayout, update the ID
		$result['layout_id'] = $brickLayout->getID();
		$result['nonce'] = wp_create_nonce( $this->nonce_action );

		echo json_encode( $result );

		die();
	}

	function ajaxDelete(){

		//Check Nonce!
		check_ajax_referer( $this->nonce_action, $this->nonce_name );

		$layout_id = $_POST['layout_id'];
		wp_delete_post( $layout_id );
		update_option( 'bricklayer_last_layout', 0 );
		echo json_encode( array(
			'redirect'	=>	admin_url( 'admin.php?page='.$this->id ),
			'status'	=>	0,
			'layout_id' => $layout_id
		));
		die();

	}

	function getSavedLayouts(){

		if( is_array( $this->layouts ) ){
			return $this->layouts;
		}

		$args = array(
			'numberposts'	=> -1,
			'offset'		=> 0,
			'orderby'		=> 'post_date',
			'order'			=> 'DESC',
			'post_type'		=> 'brick_layout',
		); 

		$this->layouts = get_posts( $args );
		return $this->layouts;
	}

	static function _getSavedLayouts(){
		$args = array(
			'numberposts'	=> -1,
			'offset'		=> 0,
			'orderby'		=> 'post_date',
			'order'			=> 'DESC',
			'post_type'		=> 'brick_layout',
		); 

		return get_posts( $args );
	}

	function getSavedBricks(){

		$args = array(
			'numberposts'	=> -1,
			'offset'		=> 0,
			'orderby'		=> 'post_date',
			'order'			=> 'DESC',
			'post_type'		=> 'brick',
			'exclude'		=> $this->brickLayout->getAllBricks(),
			'meta_query' 	=> array(
				array(
					'key' 		=> '_bookmark',
					'value' 	=> 'on',
					'compare'	=> '='
				)
			)
		); 

		return get_posts( $args );

	}

}

$GLOBALS['brickLayer'] = new BrickLayer( 'bricklayer' );




class BrickLayerMetaBox extends CustomMetaBox{

	public function __construct( $id, $title, $page, $context = 'side', $priority = 'default' ){
			
		parent::__construct( $id, $title, $page, $context, $priority );

		$savedlayouts = BrickLayer::_getSavedLayouts();
		$layouts = array();
		foreach( $savedlayouts as $layout ){
			$layouts[$layout->ID] = $layout->post_title;
		}
		
		$this->addField( new SelectMetaField( 'bricklayer_layout', 'BrickLayer Layout', '', array(), $layouts ));
	}

}

//$GLOBALS['cpt_bricklayer_metabox'] = new BrickLayerMetaBox( 'bricklayer_settings', 'BrickLayer', 'page', 'side' );