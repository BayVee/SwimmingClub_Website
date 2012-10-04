<?php
/**
 * BrickLayer Brick: Sidebar
 *
 * Displays a sidebar / widget area
 *
 */


class SidebarBrick extends Brick{

	private $widgetCount;
	private $baseClass;

	function __construct( $brick_id = -1 ){

		parent::__construct( 'Sidebar' , __( 'Sidebar / Widget Area', 'agility' ) , $brick_id );

		$this->addSetting( new BrickSetting( 'details', __( 'This brick will include your selected sidebar/widget area. ', 'agility' ),
			'info', '', $this->id , $this->brick_id ) );

		$this->addSetting( new BrickSetting( 'title', __( 'Title', 'agility' ), 'text', '', $this->id, $this->brick_id, array( 'desc' => __( 'The title will be displayed above the widget area', 'agility' ) ) ) );

		$config = array(
			'ops'	=> agility_sidebar_ops( 'bricks' )
		);
		$this->addSetting( new BrickSetting( 'sidebar_id', __( 'Sidebar', 'agility' ), 
			'select', '', $this->id , $this->brick_id , $config ) );

		$this->addSetting( new BrickSetting( 'widget_cols' , __( 'Widget Grid Columns', 'agility' ) , 'select', 'natural', $this->id, $this->brick_id,
			array( 'ops' => array(
				'natural'=>	__( 'Natural (no columns)', 'agility' ),
				'f'		=>	__( 'Full Width', 'agility' ),
				'1-3' 	=>	__( 'One Third', 'agility' ),
				'1-2'	=>	__( 'One Half', 'agility' ),
				'1-4'	=>	__( 'One Quarter', 'agility' ),
				'2'		=>	__( 'Two', 'agility' ),
				'3'		=>	__( 'Three', 'agility' ),
				'4'		=>	__( 'Four', 'agility' ),
				'5'		=>	__( 'Five', 'agility' ),
				'6'		=>	__( 'Six', 'agility' ),
				'7'		=>	__( 'Seven', 'agility' ),
				'8'		=>	__( 'Eight', 'agility' ),
				'9'		=>	__( 'Nine', 'agility' ),
				'10'	=>	__( 'Ten', 'agility' ),
				'11'	=>	__( 'Eleven', 'agility' ),
				
			), 'desc' => __( 'The width of the individual widgets within this area in grid columns.  If in the sidebar, leave this set to Natural.', 'agility' ) ) ) );
	}

	public function draw( $container_cols, $columns = '' ){

		global $wp_registered_sidebars; 
		$sidebar_id = $this->getSetting( 'sidebar_id' );
		$sidebar = $wp_registered_sidebars[$sidebar_id];

		if( isset( $sidebar['agility'] ) && !$sidebar['agility']['wrap_cols'] ){
			$columns = '';
		}

		$this->before( $columns );
		

		if( $this->getSetting( 'title' ) ): ?>

		<h3 class="brick-title <?php if( $container_cols == 16 ) echo $this->getColumnsClass( $this->getSetting( '_grid_columns' ) ); ?> cf"><?php echo $this->getSetting( 'title' ); ?></h3>

		<?php endif;
		

		$this->widgetGridCols = $this->getSetting( 'widget_cols' ); //one-third column';
		if( $this->widgetGridCols != 'natural' ){

			add_filter( 'dynamic_sidebar_params', array( &$this, 'dynamic_sidebar_params' ) , 20 );
			$this->widgetCount = 0;
			
			$brick_grid_columns = $this->getSetting( '_grid_columns' );

			$container = in_array( $brick_grid_columns , array( 'full-width' , '' ) ) ? $container_cols : $brick_grid_columns;
			
			$this->items_per_row = agility_divide_columns( $this->widgetGridCols, $container , 3 );
			$this->widgetGridClass = agility_grid_columns_class( $this->widgetGridCols );
			$this->beforeWidget = $sidebar['before_widget'];
			//echo 'adding filter : '.substr( $this->widgetGridCols , 0 , strpos( $this->widgetGridCols , ' column' ) ). ' / '. $container_cols.' :: '.$this->items_per_row;
		}
		
		?>
		<div class="widget-area cf" role="complementary">
			<?php do_action( 'before_sidebar' ); ?>
			<?php if ( ! dynamic_sidebar( $sidebar_id ) ) : ?>

				<aside id="no_widgets" class="widget clearfix">
					<div class="alert alert-warning">
						<h6><?php 
							if( isset( $wp_registered_sidebars[$sidebar_id])) echo $wp_registered_sidebars[$sidebar_id]['name']; 
							?></h6>
						<p>You haven't added any widgets to this Widget Area yet!
						Log into your admin panel and navigate to Appearance &gt; Widgets
						to get started.</p>
					</div>
				</aside>

			<?php endif; // end sidebar widget area ?>
		</div>
		<?php

		if( $this->widgetGridCols != 'natural' ) remove_filter( 'dynamic_sidebar_params', array( &$this, 'dynamic_sidebar_params' ) , 20 );

		$this->after();
	}

	function dynamic_sidebar_params( $params ){

		global $wp_registered_widgets;
		$id = $params[0]['widget_id'];

		if( $this->widgetCount == 0 ){
			$this->baseClass = $params[0]['class'];
		}

		// Substitute HTML id and class attributes into before_widget
		$classname_ = '';
		foreach ( (array) $wp_registered_widgets[$id]['classname'] as $cn ) {
			if ( is_string($cn) )
				$classname_ .= '_' . $cn;
			elseif ( is_object($cn) )
				$classname_ .= '_' . get_class($cn);
		}
		$classname_ = ltrim($classname_, '_');


		$classname_.= ' '.$this->widgetGridClass.' '.agility_alphaomega( $this->widgetCount , $this->items_per_row );

		$params[0]['before_widget'] = sprintf( $this->beforeWidget, $id, $classname_);

		$this->widgetCount++;

		return $params;

	}


}
