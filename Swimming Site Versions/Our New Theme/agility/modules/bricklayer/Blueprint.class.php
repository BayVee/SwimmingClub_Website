<?php
/**
 * Blueprint
 *
 * Models a meta-layout for BrickLayer.  
 *
 */


class Blueprint{

	public $brickLayout;
	public $blueprint_type;

	protected $enabled;
	public $columns;
	protected $applyColumnsToContainer;
	public $container_columns;

	function __construct(){

		$this->enabled = array(

			'feature'	=>	true,
			'content'	=>	true,
			'sidebar'	=>	true,

		);

		$this->columns = array(

			'feature'	=>	'sixteen',
			'content'	=>	'eleven',
			'sidebar'	=>	'four offset-by-one',

		);

		$this->container_columns = array(

			'feature'	=>	16,
			'content'	=>	11,
			'sidebar'	=>	4,

		);

		$this->applyColumnsToContainer = array(
			'feature'	=>	true,
			'content'	=>	true,
			'sidebar'	=>	true
		);
	}

	function drawBlueprint( &$brickLayout ){
		$this->brickLayout = $brickLayout;
		?>

		<!-- #main-container container -->
		<div id="main-container" class="container blueprint-<?php echo $this->blueprint_type;?>">

			<?php $this->drawFeature(); ?>
			
			<?php $this->drawContent(); ?>
		
			<?php $this->drawSidebar(); ?>
		
		</div>
		<!-- end #main-container .container -->


		<?php

	}

	function drawFeature(){
		$area = 'feature';
		if( !$this->enabled[$area] || !$this->brickLayout->hasBricks( $area ) ) return;

		$brickColumns = $this->columns[$area] .' columns';
		?>
			<div id="feature" class="feature-content"><!-- <?php echo $this->columns[$area]; ?> columns">-->

				<?php if( get_post_meta( $this->brickLayout->getID(), '_display_title', true ) == 'feature' ): ?>
				<header class="entry-header page-header">
					<h2 class="entry-title page-title"><?php the_title(); ?></h2>
				</header>
				<?php endif; ?>

				<?php $this->brickLayout->drawFeatureBricks( $this->container_columns[$area] , $brickColumns ); ?>

			</div>
		<?php
	}

	function drawContent(){

		$area = 'content';
		if( !$this->enabled[$area] ) return;

		$columns = $this->columns[$area] .' columns';
		$brickColumns = '';
		if( !$this->applyColumnsToContainer[$area] ){
			$brickColumns = $columns;
			$columns = '';
		}
		
		?>
			<!-- #primary .site-content -->
			<div id="primary" class="site-content <?php echo $columns; ?>"> 

				<?php if( get_post_meta( $this->brickLayout->getID(), '_display_title', true ) == 'content' ): ?>
				<header class="entry-header page-header <?php echo $brickColumns; ?>">
					<h2 class="entry-title page-title"><?php the_title(); ?></h2>
				</header>
				<?php endif; ?>

				<div id="content" role="main" class="clearfix">

					<?php $this->brickLayout->drawContentBricks( $this->container_columns[$area] , $brickColumns ); ?>

				</div>
				<!-- end #content -->
			</div>
			<!-- end #primary .site-content -->

		<?php

	}

	function drawSidebar(){
		$area = 'sidebar';
		if( !$this->enabled[$area] ) return;
		?>
			<div id="secondary" class="widget-area sidebar <?php echo $this->columns[$area]; ?> columns" role="complementary">
				
				<?php $this->brickLayout->drawSidebarBricks( $this->container_columns[$area] , '' ); ?>
				
			</div>
			<!-- end #secondary .sidebar .widget-area -->
		<?php
	}

	public static function createBlueprint( $blueprint_type ){

		switch( $blueprint_type ){

			case '1sidebar_right':

				return new RightSidebarBlueprint();

			case '1sidebar_left':

				return new LeftSidebarBlueprint();

			case '0sidebar':

				return new NoSidebarBlueprint();

		}

	}

	function showMiniBlueprint( $current_blueprint_type ){
		$current = $current_blueprint_type == $this->blueprint_type ? true : false;
		?>
									<input type="radio" name="blueprint" value="<?php echo $this->blueprint_type; ?>" <?php 
										checked( $current_blueprint_type , $this->blueprint_type ); ?> id="blueprint_<?php echo $this->blueprint_type; ?>_radio" >
									<label for="blueprint_<?php echo $this->blueprint_type; ?>_radio">
										<div id="blueprint_<?php echo $this->blueprint_type; ?>" class="mini-blueprint <?php if($current) echo 'mini-blueprint-selected'; ?>" data-content-cols="<?php echo $this->columns['content'] == 'sixteen' ? 16 : 11; ?>">
											<div class="blueprint-area blueprint-feature">Feature</div><div class="blueprint-area blueprint-content">Content</div><div class="blueprint-area blueprint-sidebar"><span>Sidebar</span></div>
										</div>
									</label>
		<?php
	}


	function disable( $area ){
		$this->enabled[$area] = false;
	}

	function columns( $area, $number_columns, $apply_to_container = true ){
		$this->columns[$area] = $number_columns;
		$this->applyColumnsToContainer[$area] = $apply_to_container;
	}

}

class RightSidebarBlueprint extends Blueprint{
	function __construct(){
		parent::__construct();

		$this->blueprint_type = '1sidebar_right';
	}
}

class LeftSidebarBlueprint extends Blueprint{
	function __construct(){
		parent::__construct();

		$this->blueprint_type = '1sidebar_left';

		$this->columns( 'content', 'eleven offset-by-one' );
		$this->columns( 'sidebar', 'four' );

	}
}

class NoSidebarBlueprint extends Blueprint{
	function __construct(){
		parent::__construct();

		$this->blueprint_type = '0sidebar';

		$this->disable( 'sidebar' );
		$this->columns( 'content', 'sixteen', false );

		$this->container_columns = array(

			'feature'	=>	16,
			'content'	=>	16,
			'sidebar'	=>	0,

		);

	}
}