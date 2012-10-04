<?php

/**
 * AgilityCustomPostType
 * 
 * Abstract class used to quickly build Custom Post Types
 *
 */


abstract class AgilityCustomPostType {
	
	public $slug = null;
	public $name = null;
	public $name_plural = null;
	
	public $meta_boxes = null;
	
	public $labels = null;
	public $post_args = null;
	
	public $taxonomies = array();
	
	public $baseURL;
	public $scripts = array();
	public $stylesheets = array();
	public $adminScripts = array();
	
	/*
	 * 
	 * INITIALIZATION
	 * 
	 */
	
	public function __construct(){
			
		$this->processLabels();
		$this->register();		
		
		if( is_admin() ){
			$this->add_action( 'admin_print_styles-post.php', 'loadAdminResources' );
		}
		else{
			$this->add_action( 'wp_enqueue_scripts', 'loadResources' );
		}

	}

	/*
	 * Setup all the labels for this post type, using overrides or defaults
	 */
	public function processLabels(){
		
		$label_defaults = array(
			'name' 					=> _x( $this->name_plural, 'post type general name' ),
			'singular_name' 		=> _x( $this->name, 'post type singular name' ),
			'add_new'	 			=> _x( 'Add New', $this->slug ),
			'add_new_item' 			=> sprintf(__( 'Add New %s' , 'agility' ), 			$this->name),
			'edit_item' 			=> sprintf(__( 'Edit %s', 'agility'), 				$this->name ),
			'new_item' 				=> sprintf(__( 'New %s' , 'agility'), 				$this->name ),
			'all_items' 			=> sprintf(__( 'All %s' , 'agility'), 				$this->name_plural ),
			'view_item' 			=> sprintf(__( 'View %s' , 'agility'), 				$this->name ),
			'search_items' 			=> sprintf(__( 'Search %s' , 'agility'), 			$this->name_plural ),
			'not_found' 			=> sprintf(__( 'No %s found', 'agility'), 			$this->name_plural ),
			'not_found_in_trash' 	=> sprintf(__( 'No %s found in Trash', 'agility'), 	$this->name_plural), 
			'parent_item_colon' 	=> '',
			'menu_name' 			=> $this->name_plural
		);
		
		$this->labels = wp_parse_args($this->labels, $label_defaults);
		
	}
	
	/*
	 * Register the registration action
	 */
	public function register(){
		$this->post_args['labels']  = $this->labels;
		$this->add_action( 'init', 'register_post_type' );
	}

	/*
	 * Actually register the post type with WordPress
	 */
	public function register_post_type(){
		register_post_type( $this->slug, $this->post_args );
	}
	
	/*
	 * Register the updated message filter
	 */
	private function addUpdatedMessagesFilters(){
		//add filter to ensure the text custom post type name is displayed when user updates a cpt 
		$this->add_filter( 'post_updated_messages', 'filterUpdatesMessages' );
	}

	/*
	 * 
	 */
	private function filterUpdatesMessages($messages){
		global $post, $post_ID;
		//TODO
		$messages[$this->slug] = array(
			0 => '', // Unused. Messages start at index 1.
			1 => sprintf( __('%s updated. <a href="%s">View book</a>'), $this->name, esc_url( get_permalink($post_ID) ) ),
			2 => __('Custom field updated.', 'agility'),
			3 => __('Custom field deleted.', 'agility'),
			4 => sprintf(__('%s updated.', 'agility'), $this->name),
			/* translators: %s: date and time of the revision */
			5 => isset($_GET['revision']) ? sprintf( __('Book restored to revision from %s', 'agility'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 => sprintf( __('Book published. <a href="%s">View book</a>', 'agility'), esc_url( get_permalink($post_ID) ) ),
			7 => __('Book saved.', 'agility'),
			8 => sprintf( __('Book submitted. <a target="_blank" href="%s">Preview book</a>', 'agility'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
			9 => sprintf( __('Book scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview book</a>', 'agility'),
				// translators: Publish box date format, see http://php.net/date
				date_i18n( __( 'M j, Y @ G:i' , 'agility'), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
			10 => sprintf( __('Book draft updated. <a target="_blank" href="%s">Preview book</a>', 'agility'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		);
		
		return $messages;
		
	}
	
	public function add_action($action, $method){
		add_action( $action, array( &$this, $method ) );
	}
	
	public function add_filter($filter, $method){
		add_filter( $filter, array( &$this, $method ) );
	}
	
	
	public function registerScript( $handle, $src ){
		$this->scripts[$handle] = $src;
	}
	public function registerStylesheet( $handle, $src ){
		$this->stylesheets[$handle] = $src;
	}

	public function loadResources(){
		
		foreach( $this->scripts as $handle => $src ){
			wp_enqueue_script( $handle, $src, array(), false, true );
		}

		foreach( $this->stylesheets as $handle => $src ){
			wp_enqueue_style( $handle, $src, array(), false, 'all' );
		}
		
	}




	public function registerAdminScript( $handle, $src ){
		$this->adminScripts[$handle] = $src;
	}
	
	public function loadAdminResources(){
		global $pagenow, $typenow;
		if( empty( $typenow ) && !empty( $_GET['post'] ) ) {
			$post = get_post($_GET['post']);
			$typenow = $post->post_type;
		}
		
		
		//Only load for this post type
		if( is_admin() && $typenow == $this->slug ){
    		if ($pagenow == 'post-new.php' || $pagenow == 'post.php' ) { 
		
				foreach( $this->adminScripts as $handle => $src ){
					wp_enqueue_script( $handle, $src, array(), false, true );
				}
		
			}
		}
	}
	
	
	
	public function addMetaBox(/*CustomMetaBox*/ $mb){
		
	}
	
	/*public function saveMetaBoxes(){
		
	}*/
	
	
	public function addTaxonomy($slug, $args){
		
		if( !isset( $args['labels'] ) ){
				
			$label = $args['label'];
			$label_sing = $args['label_sing'];
			
			
			//TODO sprintf
			$args['labels'] = array(
				'name' 				=> _x( $label, 'taxonomy general name' ),
			    'singular_name' 	=> _x( $label_sing, 'taxonomy singular name' ),
			    'search_items' 		=> __( 'Search '.$label ),
			    'all_items' 		=> __( 'All '.$label ),
			    'parent_item' 		=> __( 'Parent '.$label_sing ),
			    'parent_item_colon' => __( 'Parent '.$label_sing.':' ),
			    'edit_item' 		=> __( 'Edit '.$label_sing ), 
			    'update_item' 		=> __( 'Update '.$label_sing ),
			    'add_new_item' 		=> __( 'Add New '.$label_sing ),
			    'new_item_name' 	=> __( 'New '.$label_sing.' Name' ),
			    'menu_name' 		=> __( $label ),
			);
		}
		
		$this->taxonomies[$slug] = $args;
		
		$this->add_action( 'init', 'register_taxonomies' );
			
	}

	public function register_taxonomies(){
		//echo 'hi';
		foreach( $this->taxonomies as $tax_slug => $args ){
			register_taxonomy(
				$tax_slug,
				$this->slug,
				$args
			);
		}
	}
	
	
}

class CustomMetaBox {
	
	
	public $fields = array();
	public $id;
	public $title;
	public $page;
	public $context;	//normal, advanced, side
	public $priority;	//high, core, default, low
	
	protected $noncename;
	
	
	public function __construct( $id, $title, $page, $context = 'side', $priority = 'default'){
		
		$this->id = $id;
		$this->title = $title;
		$this->page = $page;
		$this->context = $context;
		$this->priority = $priority;
		
		$this->add_action( 'admin_menu', 'add_meta_box' );
		$this->add_action( 'save_post', 'save' );
		
		$this->noncename = "metabox-$this->id-nonce";
		
	}
		
	public function add_meta_box(){
		add_meta_box( $this->id, $this->title, array( &$this, 'showMetaBox' ), $this->page, $this->context, $this->priority );
		//add_meta_box('wpt_events_date', 'Event Date', 'wpt_events_date', 'events', 'side', 'default');
	}

	public function showMetaBox(){
		
		//$fields
		//d($this->fields);
		global $post_id;
		//echo $post_id;
		$values = get_post_custom( $post_id );
		
		foreach($this->fields as $id => $field){
			//ssd($values);
			//if( !isset( $values[$id] ) ) $values[$id] = get_post_meta($post_id, $id, true);

			?>
			<div id="metabox-field-wrap-<?php echo $id; ?>" class="metabox-field-wrap">
				<?php 
					$val = isset( $values[$id] ) ? $values[$id][0] : $field->getDefault();
					$field->showField( $val ); ?>
			</div>
			<?php	
			
			
		}

		echo '<input type="hidden" name="'.$this->noncename.'" id="'.$this->noncename.'" value="' .
					wp_create_nonce( $this->noncename ) . '" />';

		?>
		
		<?php
		
	}
	
	public function addField( $field ){
		$this->fields[$field->getID()] = $field;
	}
	
	
	public function save($post_id){
		//echo 'save '.$post_id; 		
		
		
		// verify this came from the our screen and with proper authorization.
		if ( !isset($_POST[$this->noncename]) ||
			 !wp_verify_nonce( $_POST[$this->noncename], $this->noncename )) {
			return $post_id;
		}
 
		// verify if this is an auto save routine. If it is our form has not been submitted, so we dont want to do anything
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
			return $post_id;
 
		// Check permissions
		if ( !current_user_can( 'edit_post', $post_id ) )
			return $post_id;
 
 
		// OK, we're authenticated: we need to find and save the data	
		$post = get_post($post_id);
		if ($post->post_type == $this->page) {
			foreach($this->fields as $id => $field){
				$field->save($post_id, $_POST[$field->id]);
			}
		}
		return $post_id;
		
	}
	
	public function add_action($action, $method){
		add_action( $action, array( &$this, $method ) );
	}
	
}

abstract class MetaField {
	
	public $name;
	public $label;
	public $id;
	public $type;
	
	public $args;
	
	public function __construct($id, $label, $name='', $args=array() ){
		
		$this->id = $id;
		
		$this->label = $label;
		
		if( empty( $name ) ) $name = $id;
		$this->name = $name;
		
		$this->args = $args;
	
	}
	
	public function getID(){
		return $this->id;
	}
	
	abstract public function showField( $value = '' );
	
	public function getValue($value){
		return $value;
	}

	public function getDefault(){
		return isset( $this->args['default'] ) ? $this->args['default'] : '';
	}
	
	protected function validateField(){
		return array(
			'valid'	=>	true,
			'msg'	=>	''
		);
	}

	public function save($post_id, $value){
		update_post_meta($post_id, $this->id, esc_attr($value) );
	}

	public function showDescription(){
		if( isset( $this->args['description'] ) ):?>
		<br/><em style="color:#999;"><?php echo $this->args['description']; ?></em>
		<?php endif;
	}
	
}

class TextMetaField extends MetaField{
	
	public function __construct( $id, $label, $name='', $args = array() ){
		
		$this->type = 'text'; 
		
		parent::__construct($id, $label, $name, $args);
		
	}
	
	public function showField( $value = '' ){
		
		?>
		<p>
		<strong><label for="<?php echo $this->id; ?>"><?php echo $this->label; ?></label></strong><br/>
		<input type="text" name="<?php echo $this->name; ?>" id="<?php echo $this->id; ?>" value="<?php echo $value; ?>" size="43"/>
		<?php $this->showDescription(); ?>
		</p>
		<?php
		
	}
	
}

class TextAreaMetaField extends MetaField{
	
	public function __construct( $id, $label, $name='', $args = array() ){
		
		$this->type = 'textarea'; 
		
		parent::__construct($id, $label, $name, $args);
		
	}
	
	public function showField( $value = '' ){
		
		?>
		<p>
		<strong><label for="<?php echo $this->id; ?>"><?php echo $this->label; ?></label></strong><br/>
		<textarea name="<?php echo $this->name; ?>" id="<?php echo $this->id; ?>" ><?php echo $value; ?></textarea>
		<?php $this->showDescription(); ?>
		</p>
		<?php
		
	}
	
}

class CheckboxMetaField extends MetaField{
	
	public function __construct( $id, $label, $name='', $args = array() ){
		
		$this->type = 'checkbox'; 
		
		parent::__construct($id, $label, $name, $args);
		
	}
	
	public function showField( $value = '' ){
		
		?>
		<p>
		<strong><label for="<?php echo $this->id; ?>"><?php echo $this->label; ?></label></strong>
		<input type="checkbox" name="<?php echo $this->name; ?>" id="<?php echo $this->id; ?>" <?php checked( $value , 'on' ); ?> />
		<?php $this->showDescription(); ?>
		</p>
		<?php
		
	}	

	public function save($post_id, $value){
		if( 'on' !== $value ) $value = 'off';
		update_post_meta($post_id, $this->id, esc_attr($value) );
	}
}

class SelectMetaField extends MetaField{
	
	public $options;
	
	public function __construct( $id, $label, $name='', $args = array(), $options = array() ){
		
		$this->type = 'select';
		parent::__construct($id, $label, $name, $args, $options);
		
		$this->options = $options;
	}
	
	public function showField( $value = '' ){

		if( !is_array( $this->options ) && function_exists( $this->options ) ){
			$func = $this->options;
			$this->options = $func();
		}
		
		?>
		<p>
		<strong><label for="<?php echo $this->id; ?>"><?php echo $this->label; ?></label></strong><br/>
		<select name="<?php echo $this->name; ?>" id="<?php echo $this->id; ?>" <?php if( isset( $this->args['multiple'] ) && $this->args['multiple'] == true ) echo 'multiple="multiple"'; ?> >
			<?php foreach($this->options as $val => $text): ?>
			<option value="<?php echo $val; ?>"  <?php selected( $val, $value ); ?> ><?php echo $text; ?></option>
			<?php endforeach; ?>
		</select>
		<?php $this->showDescription(); ?>
		</p>
		<?php
		
	}	

}

class RadioMetaField extends MetaField{
	
	public $options;
	
	public function __construct( $id, $label, $name='', $args = array(), $options = array() ){
		
		$this->type = 'radio';
		parent::__construct($id, $label, $name, $args, $options);
		
		$this->options = $options;
	}
	
	public function showField( $value = '' ){

		if( !is_array( $this->options ) && function_exists( $this->options ) ){
			$func = $this->options;
			$this->options = $func();
		}

		$k = 0;
		
		?>
		<p>
		<strong><label for="<?php echo $this->id; ?>"><?php echo $this->label; ?></label></strong><br/>
		<?php foreach($this->options as $val => $text): ?>
		<input type="radio" name="<?php echo $this->name; ?>" id="<?php echo $this->name.'-'.$k; ?>" value="<?php echo $val; ?>"  <?php checked( $val, $value ); 
			?> ><label for="<?php echo $this->name.'-'.$k; ?>"><?php echo $text; ?></label>
		<br/>
		<?php $k++; endforeach; ?>
		<?php $this->showDescription(); ?>
		</p>
		<?php
		
	}	

}