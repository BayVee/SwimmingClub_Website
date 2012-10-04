<?php

/**
 * BrickLayout
 *
 * Models an individual custom Layout.  Stores references to
 * Bricks and Blueprint.
 * 
 */

class BrickLayout{

	private $id;
	private $title;

	private $brickLayout;


	private $featureBricks;	//_feature_bricks
	private $contentBricks;	//_content_bricks
	private $sidebarBricks;	//_sidebar_bricks

	private $blueprint_type;		//_blueprint
	private $blueprint;

	function __construct( $layout_id = -1, $create = false , $title = "New Layout (click to rename)" , $load = true ){
		
		//Search for post, if it does not exist, create a new one

		$this->id = $layout_id;
		$this->featureBricks = array();
		$this->contentBricks = array();
		$this->sidebarBricks = array();

		//Load BrickLayout post
		if( $this->id != -1 ){

			$this->brickLayout = get_post( $this->id );
			if( $this->brickLayout ) $this->title = $this->brickLayout->post_title;
			else $this->id = -1;

		}

		//No associated BrickLayout post, or post id was invalid - show blank
		if( $this->id == -1 ){
			$this->title = $title;

			if( $create ){

				$this->id = $this->createBrickLayout( $this->title );
				$this->brickLayout = get_post( $this->ID );

			}

		}
		

		if( $load ) $this->load();


	}

	public function getID(){
		return $this->id;
	}
	public function getTitle(){
		return $this->title;
	}


	/**
	 * Create a BrickLayout post
	 */
	public function createBrickLayout( $layoutTitle ){

		// Create post object
		$post = array(
			'post_title' 	=> wp_strip_all_tags( $layoutTitle ),
			'post_status' 	=> 'publish',
			'post_type'		=> 'brick_layout'
		);

		// Insert the post into the database
		return wp_insert_post( $post );

	}

	public function save( $layout_areas , $brick_data ){

		$new_title = $brick_data['layout_title'];

		//Update title if necessary
		if( $new_title != $this->title ){
			$post = array(
				'ID'			=>	$this->id,
				'post_title'	=>	wp_strip_all_tags( $new_title ),
				'post_status' 	=> 'publish',
				'post_type'		=> 'brick_layout'
			);

			wp_insert_post( $post );
		}

		//Save Meta

		//Blueprint
		$this->blueprint_type = $brick_data['blueprint'];
		update_post_meta( $this->id, '_blueprint', $this->blueprint_type );

		//Layout Settings

		//Display Title
		$display_title = isset( $brick_data['layout_display_title'] ) ? $brick_data['layout_display_title'] : 'content';
		update_post_meta( $this->id, '_display_title', $display_title );

		//print_r( $layout_areas );

		$brick_index_id_map = array();

		foreach( $layout_areas as $layout_id => $layout_bricks ){

			foreach( $layout_bricks as $brick_index ){

				//Get brick ID, 
				//Find Brick post
				//If it doesn't exist, create it
				$brick_id = $brick_data['brick_id'][$brick_index];
				$brick_type = $brick_data['brick_type'][$brick_index];
				$brick = Brick::createBrick( $brick_id , $brick_type );
				$brick_id = $brick->save( $brick_data , $brick_index );

				if( $brick_id != -1 ){
					$this->addBrick( $brick_id , $layout_id );
				}

				$brick_index_id_map[$brick_index] = $brick_id;

			}

		}

		//Now save feature, content, sidebar bricks as meta
		update_post_meta( $this->id, '_feature_bricks', $this->featureBricks );
		update_post_meta( $this->id, '_content_bricks', $this->contentBricks );
		update_post_meta( $this->id, '_sidebar_bricks', $this->sidebarBricks );

		$status = 0; 				//0 = success, 1 = warning, 2 = error
		$message = 'Layout Saved';

		return array(
			'status'	=>	$status, 
			'message'	=>	$message,
			'brick_ids'	=>	$brick_index_id_map
		);

	}

	function addBrick( $brick_id , $layout_id ){


		switch( $layout_id ){

			case 'feature_area':
				$this->featureBricks[] = $brick_id;
				break;

			case 'content_area':
				$this->contentBricks[] = $brick_id;
				break;

			case 'sidebar_area':
				$this->sidebarBricks[] = $brick_id;
				break;


		}

	}

	/**
	 * Load the BrickLayout from the database, and load all the bricks
	 * and blueprint
	 */
	public function load(){

		$this->featureBricks = get_post_meta( $this->id, '_feature_bricks', true );
		if( !is_array( $this->featureBricks ) ) $this->featureBricks = array();
		$this->contentBricks = get_post_meta( $this->id, '_content_bricks', true );
		if( !is_array( $this->contentBricks ) ) $this->contentBricks = array();
		$this->sidebarBricks = get_post_meta( $this->id, '_sidebar_bricks', true );
		if( !is_array( $this->sidebarBricks ) ) $this->sidebarBricks = array();


		//Blueprint
		$this->blueprint_type = get_post_meta( $this->id, '_blueprint', true );
		if( !$this->blueprint_type ) $this->blueprint_type = '1sidebar_right';
		$this->blueprint = Blueprint::createBlueprint( $this->blueprint_type );
	}

	/**
	 * 
	 */
	public function showFeatureBricks(){

		//$brick = new PageContentBrick();
		//$brick->showUI();

		if( count( $this->featureBricks ) ){
			foreach( $this->featureBricks as $brick_id ){
				$brick = Brick::createBrick( $brick_id );
				$brick->showUI();
			}
		}
	}

	public function showContentBricks(){
		
		if( count( $this->contentBricks ) ){
			foreach( $this->contentBricks as $brick_id ){
				$brick = Brick::createBrick( $brick_id );
				$brick->showUI();
			}
		}

	}

	public function showSidebarBricks(){

		if( count( $this->sidebarBricks ) ){
			foreach( $this->sidebarBricks as $brick_id ){
				$brick = Brick::createBrick( $brick_id );
				$brick->showUI();
			}
		}

	}

	public function getAllBricks(){
		return array_merge( $this->featureBricks, $this->contentBricks, $this->sidebarBricks );
	}

	public function getBlueprint(){
		return $this->blueprint;
	}



	/* Front End */


	public function showLayout(){

		$this->blueprint->drawBlueprint( $this );

	}

	function drawFeatureBricks( $container_cols , $columns = '' ){
		$this->drawBricks( $this->featureBricks , $container_cols , $columns );
	}
	function drawContentBricks( $container_cols , $columns = '' ){
		$this->drawBricks( $this->contentBricks , $container_cols , $columns );
	}
	function drawSidebarBricks( $container_cols , $columns = '' ){
		$this->drawBricks( $this->sidebarBricks , $container_cols , $columns );
	}


	function drawBricks( $bricks , $container_cols , $columns = '' ){

		if( empty( $bricks ) ) return;

		?>
		<!-- new brick container row -->
		<div class="row row-first" <?php if( isset( $bricks[0] ) ) echo 'id="row-before-brick-'.$bricks[0].'"'; ?> >
		<?php

		foreach( $bricks as $brick_id ){
			$brick = Brick::createBrick( $brick_id );
			$brick->draw( $container_cols , $columns );
		}

		?>
		</div>
		<!-- end brick container row -->
		<?php

	}

	function hasBricks( $area ){

		switch( $area ){
			case 'feature':
				return !empty( $this->featureBricks );
				break;
			case 'content':
				return !empty( $this->contentBricks );
				break;
			case 'sidebar':
				return !empty( $this->sidebarBricks );
				break;
		}

	}


}


