<?php
/**
 * Generic Visual Query Constructor
 *
 */

require_once( 'QueryPostType.class.php' );

class QueryConstructor{
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

	private $query_id;
	protected $type;

	protected $temporary_swap_query;
	protected $shortcode;

	private $nonce_action;
	private $nonce_name;

	function __construct( $id, $type, $config = array() ){

		$this->id = $id;
		$this->type = $type;
		$this->config = $config;
		$this->baseURL = get_template_directory_uri().'/modules/'.basename( dirname( __FILE__ ) ).'/';
		$this->shortcode = isset( $config['shortcode'] ) ? $config['shortcode'] : '';

		if( is_admin() ){

			add_action( 'admin_menu' , array( $this , 'initialize' ) , 111 );
			$this->initAJAX();

		}

		$this->nonce_action = 'queryconstructor_nonce_action';
		$this->nonce_name = 'queryconstructor_wpnonce';

	}

	function initAJAX(){
		add_action( 'wp_ajax_query_constructor_results_'.$this->type, array( &$this , 'showResults' ) );
		add_action( 'wp_ajax_query_save_'.$this->type, array( &$this , 'ajaxSaveQuery' ) );
		add_action( 'wp_ajax_query_delete_'.$this->type, array( &$this , 'ajaxDelete' ) );
	}

	function initialize(){

		extract( wp_parse_args( $this->config, array(
			
			//'type'		=>	'submenu_page',
			'parent_slug'	=>	'agility-settings',
			'page_title'	=>	$this->type.'Constructor',
			'menu_title'	=>	$this->type.'Constructor',
			'capability'	=>	'manage_options',
			'menu_slug'		=>	$this->id,
			
		)));

		$this->title 		= $menu_title;
		//$this->menu_type 	= $type;
		$this->parent_slug 	= $parent_slug;
		$this->page_title 	= $page_title;
		$this->menu_title 	= $menu_title;
		$this->menu_slug 	= $menu_slug;
		$this->capability 	= $capability;

		$this->menu_page = add_submenu_page( 
			$this->parent_slug,
			$this->page_title ,
			$this->menu_title ,
			$this->capability, 
			$this->menu_slug,
			array( $this, 'showUI' )
		);

		$this->loadAssets();		
	}

	
	
	function loadAssets(){
		add_action("admin_print_styles-{$this->menu_page}", array( $this , 'loadCSS' ) );
		add_action("admin_print_styles-{$this->menu_page}", array( $this , 'loadJS' ) );
	}
	
	function loadCSS(){
		wp_enqueue_style( 'query-constructor-css', $this->baseURL.'query_constructor.css', false, false, 'all' );		
	}
	function loadJS(){
		wp_enqueue_script( 'jquery' );	// Load jQuery
		wp_enqueue_script( 'jquery-ui-sortable' ); //jQuery Sortable
		wp_enqueue_script( 'jquery-ui-draggable' );
		wp_enqueue_script( 'jquery-ui-droppable' );
		wp_enqueue_script( 'query-constructor-js' , $this->baseURL.'query_constructor.js' , array( 'jquery', 'jquery-ui-sortable' ) , false , true );
	}

	function createQueryStruct(){
		$className = $this->type.'QueryStruct';
		return new $className( $this->query_id );
	}

	function showUI(){

		if ( !current_user_can( $this->capability ) ) {
			wp_die( __('You do not have sufficient permissions to access this page.', 'agility') );
		}

		$this->query_id = -1;	//TODO (get from GET)
		$query_title = 'New '.$this->type.' (click to rename)';

		//If a specific query is to be loaded (includes -1 for new query)
		if( isset( $_GET['query_id'] ) ){
			$this->query_id = $_GET['query_id'];
		}
		//If nothing is defined, and not a new query, load the last used
		else{
			$this->query_id = $this->getLastQuery();
		}

		//If we've found a query to load (either via GET param, or the last query used), load it
		if( $this->query_id > 0 ){
			$query = get_post( $this->query_id );
			if( !$query ){
				echo '<div class="alert alert-warning">Query does not exist!</div>';
				return;
			}
			$query_title = $query->post_title;

			$this->setLastQuery();
		}
		

		$queryStruct = $this->createQueryStruct();

		?>
		<div>

			<div id="ssui">

				<h2 id="ssui_title"><?php echo $this->type; ?>Constructor <img src="<?php echo $this->baseURL.'images/bolt_red.png'; ?>" /></h2>
				<span id="qchelp" class="info-container tooltip" data-tooltip="<?php _e( 'Click for help', 'agility' ); ?>">?</span>

				<form id="queryconstructor_form" method="POST" action="<?php echo admin_url( 'admin.php?page='.$this->id ); ?>">

					<?php wp_nonce_field( $this->nonce_action, $this->nonce_name ); ?>

					<div id="query_meta">
						<div id="query_notifications"></div>

						<div id="query_selector">
							<input type="hidden" name="query_id" id="query_id" value="<?php echo $this->query_id; ?>" />
							<input type="hidden" name="query_type" id="query_type" value="<?php echo $this->type; ?>" />
							<span id="query_current"><input type="text" name="query_title" value="<?php 
								echo $query_title; ?>" class="tooltip" data-tooltip="<?php printf( __( 'Click the title to rename this %s', 'agility' ), $this->type ); ?>"/></span>
							<span id="query_selector_arrow" class="tooltip" data-tooltip="<?php printf( __( 'Click to switch %1$ss or create a new %2$s', 'agility' ), $this->type, $this->type ); ?>."></span>
							<ul>
								<?php
								$queries = QueryConstructor::getSavedQueries( $queryStruct->query_type );
								foreach( $queries as $q ){
									?><li><a href="<?php echo admin_url( 'admin.php?page='.$this->id.'&query_id='.$q->ID ); ?>" ><?php 
									echo $q->post_title; ?></a></li>
									<?php
								}

								?>
								<li><a href="<?php echo admin_url( 'admin.php?page='.$this->id.'&query_id=-1' ); 
									?>"><?php printf( __( 'Create New %s' , 'agility' ), $this->type ); ?></a></li>
								
							</ul>

						</div>

						<div id="query_save">
							<input type="submit" value="<?php printf( __( 'Save %s' , 'agility' ), $this->type ); ?>" class="button-red tooltip" data-tooltip="<?php printf( __( 'Commit your changes and save your %s', 'agility' ), $this->type ); ?>"/>
							<?php if( $this->query_id != -1 ): ?>
							<div id="query_delete" class="tooltip" data-tooltip="<?php printf( __( 'Completely delete this %s.  This cannot be undone.', 'agility' ), $this->type ); ?>"></div>
							<?php endif; ?>
						</div>

					</div>

					<div id="primary">
						<?php $queryStruct->showInterface(); ?>
						<div class="clear">
						<?php //ssd( get_posts( array( 'post_type' => 'query_definition' , 'meta_key' => 'query_type', 'meta_value' => $this->type ) ) ); ?>
						</div>
					</div>

					<div id="secondary">

						<?php $this->showShortcode(); ?>

						<h2><?php _e( 'Results (Matching Items)', 'agility' ); ?></h2>

						<p class="desc"><?php _e( 'This preview shows which items will appear, and what order they will appear in.  If you are not
							sorting manually, this may change over time as you create new posts/portfolio items/slides that 
							match the selection criteria on the left.  If you reorder the slides (sort manually), they will
							not change.', 'agility' ); ?></p>

						<div id="query_results">
							<?php echo $queryStruct->previewResults( $queryStruct->getSettings() ); ?>
						</div>

					</div>
				</form>

			</div>

			<div id="loading"></div>

		</div>
		<?php



	}

	function setLastQuery(){
		//echo '<br/><br/>set last query : qc_last_query_'.$this->type.' :: '.$this->query_id;
		update_option( 'qc_last_query_'.$this->type, $this->query_id );
	}
	function getLastQuery(){
		return get_option( 'qc_last_query_'.$this->type, 0 );	
	}

	static function getSavedQueries( $query_type='' ){

		$args = array( 'post_type' => 'query_definition' , 'numberposts' => -1 );
		if( $query_type ){
			$args['meta_key'] = 'query_type';
			$args['meta_value'] = $query_type;
		}

		$queries = get_posts( $args );
		return $queries;

	}

	function showResults(){

		//Check Nonce!
		check_ajax_referer( $this->nonce_action, $this->nonce_name );

		$form_data = $_POST['form_data'];
		//$temporarySettings = array();
		$temporarySettings = $this->parse_str( $form_data );	//unserialize array
		$queryStruct = $this->createQueryStruct();
		$preview = $queryStruct->previewResults( $temporarySettings );
		//ssd($queryStruct->getSettings());

		$result = array();
		$result['preview'] = $preview;
		$result['temp'] = $temporarySettings;
		$result['settings'] = $queryStruct->getSettings();
		$result['status'] = 0;
		$result['post_count'] = $queryStruct->getPostCount();
		$result['type'] = $this->type;
		$result['nonce'] = wp_create_nonce( $this->nonce_action );
		
		echo json_encode( $result );
		die();

	}

	function parse_str($str) {
		$arr = array();

		// split on outer delimiter
		$pairs = explode('&', $str);

		// loop through each pair
		foreach ($pairs as $i) {
			// split into name and value
			list($name,$value) = explode('=', $i, 2);

			$value = urldecode( $value );	//important to convert '+' to spaces
    
			// if name already exists
			if( isset( $arr[$name] ) ) {
				// stick multiple values into an array
				if( is_array($arr[$name]) ) {
					$arr[$name][] = $value;
				}
				else {
					$arr[$name] = array($arr[$name], $value );
				}
			}
			// otherwise, simply stick it in a scalar
			else {
				$arr[$name] = $value;
			}
		}

  		//return result array
		return $arr;
	}

	function ajaxSaveQuery(){

		//Check Nonce!
		check_ajax_referer( $this->nonce_action, $this->nonce_name );
		
		$form_data = $_POST['form_data'];
		$settings = $this->parse_str( $form_data );	//unserialize array

		$this->query_id = $settings['query_id'];
		$queryStruct = $this->createQueryStruct();
		$result = $queryStruct->save( $settings );
		$result['nonce'] = wp_create_nonce( $this->nonce_action );

		//$result['settings'] = $queryStruct->getSettings();
		echo json_encode( $result );
		die();
	}

	function ajaxDelete(){

		//Check Nonce!
		check_ajax_referer( $this->nonce_action, $this->nonce_name );

		$query_id = $_POST['query_id'];
		wp_delete_post( $query_id );
		update_option( 'qc_last_query_'.$this->type, 0 );
		echo json_encode( array(
			'redirect'	=>	admin_url( 'admin.php?page='.$this->id ),
			'status'	=>	0,
		));
		die();

	}

	function showShortcode(){
		$qid = $this->query_id > 0 ? $this->query_id : '<em>'.sprintf( __( 'Please save the %s before using the shortcode', 'agility' ), $this->shortcode ).'</em>';
		if( $this->shortcode ): ?>
		<h2>Shortcode</h2>
		<div id="query_shortcode" class="tooltip" data-tooltip="<?php 
			printf( __( 'You can place this shortcode in posts, pages, etc to display this %s.', 'agility' ), $this->shortcode ); 
			?>" >&#91;raw&#93;&#91;<?php echo $this->shortcode; ?> id=&quot;<span id="query_shortcode_id"><?php 
			echo $qid; ?></span>&quot;&#93;&#91;/raw&#93;</div>
		<br/>
		<?php endif;
	}

}

$sliderConstructor = new QueryConstructor( 'sliderconstructor', 'Slider', array( 'shortcode' => 'slider' ) );
$portfolioConstructor = new QueryConstructor( 'portfolioconstructor', 'Portfolio' , array( 'shortcode' => 'portfolio' ) );



class QueryStruct{

	protected $id;
	protected $query;

	protected $settings;
	protected $defaults;

	protected $config;

	protected $post;

	public $query_type;


	function __construct( $id = 0 ){

		$this->id = $id;
		$this->loadDefaults();
		$this->loadConfig();
		$this->private = array();

		$settings = array();	//Pull from DB by post ID $id
		if( $this->id > 0 ){
			$this->post = get_post( $this->id );
			$settings = get_post_meta( $this->id, 'query_settings' , true );
		}

		//ssd( $settings );
		//ssd( $this->defaults );

		$this->settings = wp_parse_args( $settings , $this->defaults );
		//echo "Settings: $this->id";
		//ssd( $this->settings );
		$this->query_type = "Query";

	}

	function loadDefaults(){

		$this->defaults = array(
			'post_type'				=>	'post',
			'post_status'			=>	'publish',
			'orderby'				=>	'date',
			'order'					=>	'ASC',
			'posts_per_page'		=>	-1,
			'paged'					=>	get_query_var( 'paged' ),
			'ignore_sticky_posts'	=>	true
		);

	}

	function loadConfig(){
		$this->config = array(

			'post_type'		=>	array(
				'type'		=>	'select',
				'multiple'	=>	true,
				'public'	=>	true,
				'title'		=>	__( 'Post Type', 'agility' ),
				'ops'		=>	array(
					'any'				=>	__( 'Any', 'agility' ),
					'post'				=>	__( 'Post', 'agility' ),
					'page'				=>	__( 'Page', 'agility' ),
					'slide'				=>	__( 'Slide', 'agility' ),
					'portfolio-item' 	=>	__( 'Portfolio Item' , 'agility')
				),
				'desc'		=>	__( 'Select the post type(s) to pull items from.', 'agility' ),
				'advanced'	=>	true
			),

			'author' 		=>	array(
				'type'		=>	'select',
				'multiple'	=>	true,
				'title'		=>	__( 'Author', 'agility' ),
				'public'	=>	true,
				'ops'		=>	$this->getAuthors(),
				'desc'		=>	__( 'Select the post author(s) to pull items from.  If you select zero authors, then all authors will be included.', 'agility' ),
				'advanced'	=>	true
			),

			'cat'			=>	array(
				'type'		=>	'taxonomy_select',
				'multiple'	=>	true,
				'public'	=>	true,
				'title'		=>	__( 'Blog Post Category', 'agility' ),
				'ops'		=>	$this->getCategories(),
				'desc'		=>	__( 'Select the post category(ies) to pull items from.  Remember, this applies to all items in the set, but only POSTs can have this property  You can choose whether a post must match one, all, or none of the categories to be included.', 'agility' ),
				'advanced'	=>	true
			),

			'posts_per_page'=>	array(
				'type'		=>	'text',
				'public'	=>	true,
				'title'		=>	__( 'Limit / Posts Per Page', 'agility' ),
				'desc'		=>	__( 'This will limit the number of items retrieved.  Use -1 for no limit.', 'agility' ),
				'advanced'	=>	false
			),

			'orderby'		=>	array(
				'type'		=>	'select',
				'public'	=>	true,
				'title'		=>	__( 'Sort by', 'agility' ),
				'ops'		=>	array(
					'none'		=>	__( 'None', 'agility' ),
					'ID'		=>	__( 'ID', 'agility' ),
					'author'	=>	__( 'Author', 'agility' ),
					'title'		=>	__( 'Title', 'agility' ),
					'date'		=>	__( 'Date', 'agility' ),
					'modified'	=>	__( 'Date Modified', 'agility' ),
					'rand'		=>	__( 'Random', 'agility' ),
					'menu_order'=>	__( 'Page order', 'agility' ),
					'post__in'	=>	__( 'Manual Order', 'agility' )
				),
				'desc'		=>	__( 'Set the parameter by which to sort the items.  To manually sort the items, set it to Manual.  When the sort is in manual mode, only posts listed in the Manual Order field will be returned.', 'agility' ),
				'advanced'	=>	true,
			),

			'order'			=>	array(
				'type'		=>	'select',
				'public'	=>	true,
				'title'		=>	__( 'Sort order', 'agility' ),
				'ops'		=>	array(
					'ASC'		=>	__( 'Ascending', 'agility' ),
					'DESC'		=>	__( 'Descending', 'agility' ),
					//'manual'	=>	__( 'Manual' , 'agility' ),
				),
				'desc'		=>	__( 'Set the sort order.  For Date sorting, "Ascending" is oldest first and "Descending" is most recent first.', 'agility' ),
				'advanced'	=>	false

			),

			'post__in'		=>	array(
				'type'		=>	'text',
				'public'	=>	true,
				'title'		=>	__( 'Manual Order', 'agility' ),
				'desc'		=>	__( 'This field allows you to manually set the sort order.  When you update a field in the Constructor, this field will update automatically.  When you drag the results around to reorder, this field will update automatically, and the sort by will change to Manual.  Once the sort is changed to manual, only items listed in this field will be returned.', 'agility' ),
				'advanced'	=>	true,
			),

		);
	}

	function getID(){
		return $this->id;
	}

	function loadSettings( $settings ){

		$this->settings = wp_parse_args( $settings , $this->defaults );

	}

	function getSettings(){
		return $this->settings;
	}

	function query(){		
		$this->specialSettings();
		$this->query = new WP_Query( $this->settings );
		$this->swapQuery();
		return $this->query;
	}

	function swapQuery(){
		global $wp_query;
		$this->temporary_swap_query = $wp_query;
		$wp_query = $this->query;
	}
	function unswapQuery(){
		global $wp_query;
		$wp_query = $this->temporary_swap_query;
	}

	function showInterface(){
		?>
		<h2><?php echo $this->query_type; ?> <?php _e( 'Parameters', 'agility' ); ?></h2>
		<p class="interface-tip"><?php printf( __( 'Below, set the parameters which items must match in order to be included in the %s.	The results preview will automatically update as you change parameters.', 'agility' ), $this->query_type ); ?>
		</p>
		<?php	

		foreach( $this->config as $param => $settings ){
			if( isset( $settings['public'] ) && $settings['public'] == true ){
				$this->showParameter( $param , $settings );
			}
		}
		?>

		<div class="clear">
			<br/><br/>
			<a href="#" id="toggle-advanced-button" class="button tooltip" data-tooltip="<?php _e( 'By default, only the basic options are displayed.  Click to show more advanced options.', 'agility' ); ?>"><?php 
				_e( 'Toggle Advanced Options', 'agility' ); ?></a>			
		</div>
		<?php

		$this->showHelp();

	}

	function showHelp(){}

	function showParameter( $param , $settings ){

		?>
		<div class="param <?php if( isset( $settings['advanced'] ) && $settings['advanced'] === true ) echo 'param-advanced'; ?>">
			<?php if( isset( $settings['title'] ) ): ?>
			<h3><?php echo $settings['title']; ?></h3>
			<?php endif; ?>

			<?php

			switch( $settings['type'] ){

				case 'select':
					//if( $param == 'portfolio-categories' ) ssd($this->settings);
					$ops = $settings['ops'];
					?>
					
					<select name="<?php echo $param; ?>" <?php if( isset( $settings['multiple'] ) ) echo 'multiple="multiple"'; ?> >
						<?php foreach( $ops as $key => $val ): ?>
							<?php $selected = '';
								if( isset( $this->settings[$param] ) ){
									if( is_array( $this->settings[$param] ) ){
										if( in_array( $key , $this->settings[$param] ) ){
											$selected = 'selected="selected"';
										}
									}
									else if( $key == $this->settings[$param] ){
										$selected = 'selected="selected"';
									}
								}
							?>
							<option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $val; ?></option>
						<?php endforeach; ?>
					</select>
					<?php
					break;

				case 'taxonomy_select':
					//if( $param == 'portfolio-categories' ) ssd($this->settings);
					$ops = $settings['ops'];
					?>

					<select name="<?php echo $param; ?>-operator">
						<option value="IN"><?php _e( 'Any of the selected (IN)', 'agility' ); ?></option>
						<option value="AND"><?php _e( 'All of the selected (AND)', 'agility' ); ?></option>
						<option value="NOT IN"><?php _e( 'None of the selected (NOT IN)', 'agility' ); ?></option>
					</select>
					
					<select name="<?php echo $param; ?>" multiple="multiple" ?> >
						<?php foreach( $ops as $key => $val ): ?>
							<?php $selected = '';
								if( isset( $this->settings[$param] ) ){
									if( is_array( $this->settings[$param] ) ){
										if( in_array( $key , $this->settings[$param] ) ){
											$selected = 'selected="selected"';
										}
									}
									else if( $key == $this->settings[$param] ){
										$selected = 'selected="selected"';
									}
								}
							?>
							<option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $val; ?></option>
						<?php endforeach; ?>
					</select>
					<?php
					break;

				case 'text':
					$val = '';
					if( isset( $this->settings[$param] ) ){
						if( is_array( $this->settings[$param] ) ){
							$val = implode( ',', $this->settings[$param] );
						}
						else{
							$val = $this->settings[$param];
						}
					}
					?>
					<input type="text" name="<?php echo $param; ?>" value="<?php echo $val ?>" />
					<?php
					break;


			}

			$desc = isset( $settings['desc'] ) ? $settings['desc'] : '';
			echo '<div class="desc">'.$desc.'</div>';
		?>
		</div>
		<?php
	}

	function getAuthors( $args = array() ){
		global $wpdb;
		$defaults = array(
			'orderby' => 'name', 'order' => 'ASC', 'number' => '',
			'optioncount' => false, 'exclude_admin' => true,
			'show_fullname' => false, 'hide_empty' => true,
			'feed' => '', 'feed_image' => '', 'feed_type' => '', 'echo' => true,
			'style' => 'list', 'html' => true
		);

		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );

		$return = '';

		$query_args = wp_array_slice_assoc( $args, array( 'orderby', 'order', 'number' ) );
		$query_args['fields'] = 'ids';
		$authors = get_users( $query_args );

		$author_array = array();
		foreach( $authors as $author_id ){
			$author = get_userdata( $author_id );
			$author_array[$author_id] = $author->display_name;
		}

		return $author_array;
	}

	function getCategories(){
		$cats = get_categories();
		$cat_array = array();
		foreach( $cats as $cat ){
			$cat_array[$cat->term_id] = $cat->cat_name;
		}
		return $cat_array;
	}



	function previewResults( $temporarySettings = array() ){
		$this->loadSettings( $temporarySettings );
		$this->query();

		$html = '<ul class="query-results-list">';
		while( $this->query->have_posts() ) {
			$this->query->the_post();
			global $post;
			$html.= '<li data-post-id="'.$post->ID.'" class="post"><strong>'.$post->post_title.'</strong>&nbsp; <span class="post-info">['.$post->post_type.' '.$post->ID.']</span> <span class="post-remove">&times;</span> </li>';
		}
		$html.= '</ul>';

		$this->queryCompleted();

		return $html;
	}

	function queryCompleted(){
		$this->unswapQuery();
		wp_reset_postdata();
	}

	function specialSettings(){

		//Category IN/AND/NOT IN
		if( isset( $this->settings['cat'] ) ){
			//&& is_array( $this->settings['cat'] )
			//ssd( $this->settings['cat'] );

			$multi = is_array( $this->settings['cat'] );
			switch( $this->settings['cat-operator'] ){
				case 'IN':
					if( $multi ){
						$this->settings['cat'] = implode( ',' , $this->settings['cat'] );
					}
					break;

				case 'AND':
					if( $multi ){
						$this->settings['category__and'] = $this->settings['cat'];
						unset( $this->settings['cat'] );
					}
					break;

				case 'NOT IN':
					if( $multi ){
						$not_in = array();
						foreach( $this->settings['cat'] as $cat ){
							$not_in[] = '-'.$cat;
						}
						$this->settings['cat'] = implode( ',' , $not_in );
					}
					else{
						$this->settings['cat'] = '-'.$this->settings['cat'];
					}
					break;
			}
			//unset ( $this->settings['cat-operator'] );
		}

		//Author
		if( isset( $this->settings['author'] ) && is_array( $this->settings['author'] ) ){
			$this->settings['author'] = implode( ',' , $this->settings['author'] );
		}
		
		//If manual, and post__in is set, and not empty
		if( 	isset( $this->settings['orderby'] ) && 'post__in' == $this->settings['orderby'] 
			&& 	isset( $this->settings['post__in'] ) && !empty( $this->settings['post__in'] ) ){
			if( !is_array( $this->settings['post__in'] ) ) $this->settings['post__in'] = explode( ',' , $this->settings['post__in'] );
			//unset( $this->settings['order'] );
			//unset( $this->settings['orderby'] );
		}
		//Ignore post__in unless manual
		else{
			unset( $this->settings['post__in'] );
		}
	}

	function addTaxQuery( $taxonomy, $terms, $operator = 'IN' , $field = 'id' ){

		if( !isset( $this->settings['tax_query'] ) ){
			$this->settings['tax_query'] = array();
		}

		$this->settings['tax_query'][] = array(
			'taxonomy'	=>	$taxonomy,
			'terms'		=>	$terms,
			'field'		=>	$field,
			'operator'	=>	$operator
		);

	}

	function save( $settings = array() ){


		$this->loadSettings( $settings );

		$new_title = $settings['query_title'];

		//Update title if necessary, create new post if necessary
		if( $this->id <= 0 || ( $new_title != $this->post->post_title ) ) {
			
			//update/create new
			$post = array(
				'post_title'	=>	wp_strip_all_tags( $new_title ),
				'post_status' 	=> 'publish',
				'post_type'		=> 'query_definition'
			);

			//update
			if( $this->id > 0 ){
				$post['ID']		=	$this->id;
			}

			$this->id = wp_insert_post( $post );
		}

		//Save Meta
		unset( $this->settings['paged'] ); //This is determined dynamically when the query is run
		update_post_meta( $this->id, 'query_settings', $this->settings );	//query settings
		update_post_meta( $this->id, 'query_type', $this->query_type );	//query settings

		$status = 0; 				//0 = success, 1 = warning, 2 = error
		$message = sprintf( __( '%s Saved', 'agility' ), $this->query_type );

		return array(
			'settings'	=>	$this->settings,
			'status'	=>	$status, 
			'message'	=>	$message,
			'query_id'	=>	$this->id,
		);
	}

	function moveToEnd( $params ){

		foreach( $params as $param ){
			$temp = $this->config[$param];
			unset( $this->config[$param] );
			$this->config[$param] = $temp;
		}

	}

	function getPostCount(){
		if( $this->query ) return $this->query->post_count;
		return 0;
	}

	function getTerms( $taxonomy ){
		$terms = get_terms( $taxonomy );
		$terms_array = array();
		
		foreach( $terms as $term ){
			$term_array[$term->term_id] = $term->name;
		}
		return $term_array;
	}

}

//Menu for this specific type of Query Interface
//Manage Sliders
//When choice changes, automatically run query, allow sorting - if sorted, switch to sortby: manual order
class SliderQueryStruct extends QueryStruct{

	function __construct( $id = 0 ){

		parent::__construct( $id );
		$this->query_type = "Slider";
	}

	function loadDefaults(){	
		parent::loadDefaults();
		$this->defaults['post_type'] = 'slide';
	}

	function loadConfig(){
		
		parent::loadConfig();

		$this->config['post_type']['ops'] = array( 
			'slide'				=>	__( 'Slide', 'agility' ),
			'post'				=>	__( 'Post', 'agility' ),
			'portfolio-item' 	=>	__( 'Portfolio Item' , 'agility')
		);

		$this->config['sliders'] = array(
			'type'		=>	'taxonomy_select',
			'multiple'	=>	true,
			'public'	=>	true,
			'title'		=>	__( 'Slide Category', 'agility' ),
			'ops'		=>	$this->getTerms( 'sliders' ),
			'desc'		=>	__( 'Select the Slider category(ies) to pull items from.  Remember, this applies to all items in the slider, but only SLIDEs can have this property.  You can choose whether a slide must match one, all, or none of the categories to be included.', 'agility' ),
			'advanced'	=>	false
		);

		$this->config['portfolio-categories'] = array(
			'type'		=>	'taxonomy_select',
			'multiple'	=>	true,
			'public'	=>	true,
			'title'		=>	__( 'Portfolio Category', 'agility' ),
			'ops'		=>	$this->getTerms( 'portfolio-categories' ),
			'advanced'	=>	true
		);

		$this->config['posts_per_page']['title'] = __( 'Limit (Maximum Slides)', 'agility' );
		$this->config['posts_per_page']['desc']	= __( 'This will set the maximum number of items in the slider.  Use -1 for unlimited.', 'agility' );

		//$this->moveToEnd( array( 'orderby', 'order', 'post__in' ) );

		$this->moveToEnd( array( 'posts_per_page', 'orderby', 'order', 'post__in' ) );
	}

	

	function specialSettings(){

		parent::specialSettings();

		//If we're not using slides, ignore sliders
		if( is_array( $this->settings['post_type'] ) && !in_array( 'slide' , $this->settings['post_type'] ) ||
			!is_array( $this->settings['post_type'] ) && 'slide' != $this->settings['post_type'] ) {
				unset( $this->settings['sliders'] );
		}
		if( is_array( $this->settings['post_type'] ) && !in_array( 'portfolio-item' , $this->settings['post_type'] ) ||
			!is_array( $this->settings['post_type'] ) && 'portfolio-item' != $this->settings['post_type'] ) {
				unset( $this->settings['portfolio-categories'] );
		}

		if( isset( $this->settings['sliders'] ) ){
			$this->addTaxQuery( 'sliders' , $this->settings['sliders'] , $this->settings['sliders-operator']);
			unset( $this->settings['sliders'] );
			unset( $this->settings['sliders-operator'] );
		}
		if( isset( $this->settings['portfolio-categories'] ) ){
			$this->addTaxQuery( 'portfolio-categories' , $this->settings['portfolio-categories'], $this->settings['portfolio-categories-operator'] );
			unset( $this->settings['portfolio-categories'] );
			unset( $this->settings['portfolio-categories-operator'] );
		}


	}

	function showHelp(){

		?>

		<div id="qc_welcome" class="<?php echo 'welcome-hide'; ?>">
				<div id="qc_welcome_inner">
					<a href="#" class="qc_welcome_close get-started">&times;</a>
					<ul class="tabs">
						<li><a href="#tab-1" class="active">Overview</a></li>
						
						<li><a href="#tab-3">Video Tutorials</a></li>
					</ul>
					<ul class="tabs-content">
						<li id="tab-1" class="active">

							<h2>Welcome to SliderConstructor!</h2>
							
							<p>You can use the SliderConstructor to create collections of slides that form sliders.  Here are some quick tips:</p>

							<ol>

								<li><strong>Slider Parameters.</strong><br/>
									<p>Use the Slider Parameters to set the criteria that an individual slide must meet in order to
										be included in the slider group.  When you adjust a parameter, the matching slides will appear
										on the right in the Results preview.</p></li>

								<li><strong>Results</strong><br/>
									<p>Once you have the slides you want in the Results area, you can reorder them by dragging and 
										dropping.</p></li>
								<li><strong>Displaying Sliders</strong><br/>
									<p>There are several ways to display a slider:</p>
									<ol>
										<li>On the <b>Home page</b>, by selecting the Slider you have created in the dropdown options</li>
										<li>In a <b>BrickLayer</b> brick, by selecting the Slider you have created in the dropdown options</li>
										<li>By inserting the <b>shortcode</b> provided in a post or page</li>
									</ol>
								</li>
								<li>You can find detailed instructions here: 
									<strong><a class="button" target="_blank" href="http://agility.sevenspark.com/help/#slider">SliderConstructor Instructions</a></strong>
								</li>
								

							</ol>
						</li>
						<li id="tab-3">
							<iframe width="780" height="380" src="http://www.youtube.com/embed/Wr7k7gsCG28" frameborder="0" allowfullscreen></iframe>
							<br/><br/>
							<iframe width="780" height="380" src="http://www.youtube.com/embed/fmV6_AD5L1U" frameborder="0" allowfullscreen></iframe>
						</li>
					</ul>
					<p>
					<a href="#" class="button button-red get-started">Get Started</a>
					</p>
				</div>
			</div>

			<?php
	}



}

class PortfolioQueryStruct extends QueryStruct{

	function __construct( $id = 0 ){

		parent::__construct( $id );
		$this->query_type = "Portfolio";
	}

	function loadDefaults(){	
		parent::loadDefaults();
		$this->defaults['post_type'] = 'portfolio-item';
		
	}

	function loadConfig(){
		
		parent::loadConfig();

		//unset( $this->config['cat'] );

		$this->config['post_type']['ops'] = array( 
		//	'slide'				=>	__( 'Slide', 'agility' ),
			'post'				=>	__( 'Post', 'agility' ),
			'portfolio-item' 	=>	__( 'Portfolio Item' , 'agility')
		);

		$this->config['portfolio-categories'] = array(
			'type'		=>	'taxonomy_select',
			'multiple'	=>	true,
			'public'	=>	true,
			'title'		=>	__( 'Portfolio Category', 'agility' ),
			'ops'		=>	$this->getTerms( 'portfolio-categories' ),
			'advanced'	=>	false,
			'desc'		=>	__( 'Select the Portfolio category(ies) to pull items from.  Remember, this applies to all items in the portfolio, but only PORTFOLIO ITEMS can have this property.  You can choose whether a portfolio item must match one, all, or none of the categories to be included.', 'agility' ),
		);

		$this->config['posts_per_page']['title'] = __( 'Posts per page', 'agility' );
		$this->config['posts_per_page']['desc']	= __( 'This will set the maximum number of items per page in the portfolio.  Use -1 to show all matching items on one page.', 'agility' );

		$this->moveToEnd( array( 'posts_per_page', 'orderby', 'order', 'post__in' ) );
	}

	function specialSettings(){

		parent::specialSettings();

		
		if( is_array( $this->settings['post_type'] ) && !in_array( 'portfolio-item' , $this->settings['post_type'] ) ||
			!is_array( $this->settings['post_type'] ) && 'portfolio-item' != $this->settings['post_type'] ) {
				unset( $this->settings['portfolio-categories'] );
		}

		if( isset( $this->settings['portfolio-categories'] ) ){
			$this->addTaxQuery( 'portfolio-categories' , $this->settings['portfolio-categories'] , $this->settings['portfolio-categories-operator'] );
			unset( $this->settings['portfolio-categories'] );
			unset( $this->settings['portfolio-categories-operator'] );
		}


	}

	function showHelp(){

		?>

		<div id="qc_welcome" class="<?php echo 'welcome-hide'; ?>">
				<div id="qc_welcome_inner">
					<a href="#" class="qc_welcome_close get-started">&times;</a>
					<ul class="tabs">
						<li><a href="#tab-1" class="active">Overview</a></li>
						
						<li><a href="#tab-3">Video Tutorials</a></li>
					</ul>
					<ul class="tabs-content">
						<li id="tab-1" class="active">

							<h2>Welcome to PortfolioConstructor!</h2>
							
							<p>You can use the PortfolioConstructor to create collections of portfolio items that form portfolios.  Here are some quick tips:</p>

							<ol>

								<li><strong>Portfolio Parameters</strong><br/>
									<p>Use the Portfolio Parameters to set the criteria that an individual portfolio item must meet in order to
										be included in the portfolio.  When you adjust a parameter, the matching portfolio items will appear
										on the right in the Results preview.</p></li>

								<li><strong>Results</strong><br/>
									<p>Once you have the portfolio items you want in the Results area, you can reorder them by dragging and 
										dropping.</p></li>
								<li><strong>Displaying Portfolios</strong><br/>
									<p>There are several ways to display a portfolio:</p>
									<ol>
										<li>By creating a Page that uses the <b>Portfolio Template</b>, and selecting the Portfolio you have created from the dropdown options</li>
										<li>In a <b>BrickLayer</b> brick, by selecting the Portfolio you have created in the dropdown options</li>
										<li>By inserting the <b>shortcode</b> provided in a post or page</li>
									</ol>
								</li>
								<li>You can find detailed instructions here: 
									<strong><a class="button" target="_blank" href="http://agility.sevenspark.com/help/#portfolio">PortfolioConstructor Instructions</a></strong>
								</li>
								

							</ol>
						</li>
						<li id="tab-3">
							<iframe width="640" height="360" src="http://www.youtube.com/embed/htdVV-jQn0M" frameborder="0" allowfullscreen></iframe>
							<br/><br/>
							<iframe width="640" height="360" src="http://www.youtube.com/embed/asy2h5RzzIk" frameborder="0" allowfullscreen></iframe>
						</li>
					</ul>
					<p>
					<a href="#" class="button button-red get-started">Get Started</a>
					</p>
				</div>
			</div>

			<?php
	}

}

add_filter( 'posts_orderby', 'queryconstructor_sort_query_by_post_in', 10, 2 );
	
function queryconstructor_sort_query_by_post_in( $sortby, $thequery ) {
	if ( !empty($thequery->query['post__in']) && isset($thequery->query['orderby']) && $thequery->query['orderby'] == 'post__in' )
		$sortby = "find_in_set(ID, '" . implode( ',', $thequery->query['post__in'] ) . "')";
	
	return $sortby;
}

