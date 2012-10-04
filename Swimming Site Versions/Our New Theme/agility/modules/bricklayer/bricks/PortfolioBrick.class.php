<?php
/**
 * BrickLayer Brick: Portfolio 
 *
 * Displays a Portfolio
 *
 */


class PortfolioBrick extends Brick{

	function __construct( $brick_id = -1 ){

		parent::__construct( 'Portfolio' , __( 'Portfolio', 'agility' ) , $brick_id );

		$this->addSetting( new BrickSetting( 'details', __( 'This brick is designed to be used full-width within its container.  If placed in a sidebar, should be set to full-width.  It will display the selected portfolio, with the same options as a Portfolio Page Template', 'agility' ),
			'info', '', $this->id , $this->brick_id ) );

		$this->addSetting( new BrickSetting( 'title', __( 'Title', 'agility' ), 'text', '', $this->id, $this->brick_id ) );

		$pq_config = array(
			'ops'	=> agility_portfolio_ops(),
			'desc'	=> 'Choose from portfolios you have created or <a href="'.
						admin_url('admin.php?page=portfolioconstructor'). '" target="_blank">create a new portfolio</a> with the PortfolioConstructor'
		);
		$this->addSetting( new BrickSetting( 'portfolio_query_id', __( 'Portfolio', 'agility' ), 
			'select', '', $this->id , $this->brick_id , $pq_config ) );

		$this->addSetting( new BrickSetting( 'item_grid_columns', __( 'Portfolio Item Grid Columns', 'agility' ), 
			'select', '', $this->id , $this->brick_id , array( 'ops' => array(
				'f'		=>	__( 'Full (Full Width)', 'agility' ),
				'1-2'	=>	__( 'Half (1/2)', 'agility' ),
				'1-3'	=>	__( 'Thirds (1/3)', 'agility' ),
				'1-4'	=>	__( 'Quarters (1/4)', 'agility' ),
				'2'		=>	__( 'Two Grid Columns', 'agility' ),
				'3'		=>	__( 'Three Grid Columns', 'agility' ),
				'4'		=>	__( 'Four Grid Columns', 'agility' ),
				), 'desc'	=>	__( 'The fraction columns (one third, one half, etc) are only valid if this brick is full-width.', 'agility' ) ) ) );

		$this->addSetting( new BrickSetting( 'portfolio_items_per_row', __( 'Items per row', 'agility' ),  'text',
			'', $this->id, $this->brick_id , array( 'desc' => __( 'Must be set if brick is not full-width within the container.  If this is a full-width brick, items per row will be auto-calculated', 'agility' ) ) ) );

		$this->addSetting( new BrickSetting( 'portfolio_show_title' , __( 'Show Portfolio Item Titles', 'agility' ), 
			'checkbox', 'off', $this->id, $this->brick_id , array( 'desc' => __( 'Display the title of each portfolio item', 'agility' ) ) ) );

		$this->addSetting( new BrickSetting( 'portfolio_show_excerpt' , __( 'Show Portfolio Item Excerpts', 'agility' ), 
			'checkbox', 'off', $this->id, $this->brick_id , array( 'desc' => __( 'Display the excerpt for each portfolio item', 'agility' ) ) ) );

		$this->addSetting( new BrickSetting( 'portfolio_isotope', __( 'Make Filterable', 'agility' ) , 
			'checkbox', 'off', $this->id, $this->brick_id , array( 'prefix' => '' , 'desc' => __( 'Allow dynamic filtering.  You must enable one of the "Filter by" options below', 'agility' ) ) ) );
		$this->addSetting( new BrickSetting( 'portfolio_filterby_author', __( 'Filter by author', 'agility' ) , 
			'checkbox', 'off', $this->id, $this->brick_id , array( 'prefix' => '' ) ) );
		$this->addSetting( new BrickSetting( 'portfolio_filterby_portfolio_category', __( 'Filter by Portfolio Category', 'agility' ) , 
			'checkbox', 'off', $this->id, $this->brick_id , array( 'prefix' => '' ) ) );
		$this->addSetting( new BrickSetting( 'portfolio_filterby_portfolio_tag', __( 'Filter by Portfolio Tag', 'agility' ) , 
			'checkbox', 'off', $this->id, $this->brick_id , array( 'prefix' => '' ) ) );
		$this->addSetting( new BrickSetting( 'portfolio_filterby_post_category', __( 'Filter by Post Category', 'agility' ) , 
			'checkbox', 'off', $this->id, $this->brick_id , array( 'prefix' => '', 'desc' => __( 'Specific to portfolios of Posts (rather than Portfolio Items)', 'agility' ) ) ) );
		$this->addSetting( new BrickSetting( 'portfolio_filterby_post_tag', __( 'Filter by Post Tag', 'agility' ) , 
			'checkbox', 'off', $this->id, $this->brick_id , array( 'prefix' => '', 'desc' => __( 'Specific to portfolios of Posts (rather than Portfolio Items)', 'agility' ) ) ) );
		
		$this->addSetting( new BrickSetting( 'portfolio_crop_items', __( 'Crop Items', 'agility' ), 
			'checkbox', '', $this->id , $this->brick_id , array( 'default' => 'off', 'prefix' => '', 'desc' => __( 'Crop all items to a consistent aspect ratio as defined in the Agility Control Panel', 'agility' ) ) ) );


	}

	public function draw( $container_cols, $columns = '' ){

		$this->before( '' );
		$col_x = 'col-3';

		$brick_grid_columns = $this->getSetting( '_grid_columns' );
		$item_grid_columns = $this->getSetting( 'item_grid_columns' );

		$container = in_array( $brick_grid_columns , array( 'full-width' , '' ) ) ? $container_cols : $brick_grid_columns;
		$items_per_row = agility_divide_columns( $item_grid_columns, $container );

		//Check if we need to wrap every element in grid columns or not
		$wrap_cols = !( $container_cols == 16 && in_array( $brick_grid_columns, array( 'sixteen', 'full-width', '' ) ) );

		if( $this->getSetting( 'title' ) ): ?>

		<h3 class="brick-title <?php if( !$wrap_cols ) echo $this->getColumnsClass( $brick_grid_columns ); ?>"><?php echo $this->getSetting( 'title' ); ?></h3>

		<?php endif;

		
		//Portfolio Query
		$portfolio_query_id = $this->getSetting( 'portfolio_query_id' );
		$queryStruct 		= new PortfolioQueryStruct( $portfolio_query_id );
		$portfolio_query 	= $queryStruct->query();


		$GLOBALS['portfolio_id'] = $portfolio_query_id;
		$GLOBALS['portfolio_meta'] = array();
		global $portfolio_meta;
		$portfolio_meta['grid_columns'][0] = $item_grid_columns;
		$portfolio_meta['portfolio_show_title'][0] = $this->getSetting( 'portfolio_show_title' ) ? 'on' : 'off';
		$portfolio_meta['portfolio_show_excerpt'][0] = $this->getSetting( 'portfolio_show_excerpt' ) ? 'on' : 'off';
		$portfolio_meta['items_per_row'] = $items_per_row;
		$portfolio_meta['wrap'] = $wrap_cols;
		$portfolio_meta['portfolio_crop_items'][0] = $this->getSetting( 'portfolio_crop_items' ) ? 'on' : 'off';

		$brick = get_post( $this->brick_id );
		$filtered = agility_portfolio_filters( $brick, $portfolio_query, $portfolio_query_id ); 
		?>

		<div id="portfolio-<?php echo $portfolio_query_id; ?>" class="portfolio <?php echo $col_x; ?> <?php if( $filtered ): ?>isotope-container <?php endif; ?>clearfix">

		<?php

			if( $filtered ) rewind_posts();
			if( $portfolio_query->have_posts() ){ 
				$k = 0;
				while( $portfolio_query->have_posts() ){
					$portfolio_query->the_post();
					
					get_template_part( 'content', 'portfolio-grid' );
					$k++;
				}
			}
			?>

			<div class="entry-content">
			<?php agility_content_nav( 'nav-below' , true, '' ); ?>
			</div>

			<?php

			$queryStruct->queryCompleted();
		?>

		</div>

		<?php
		$this->after();
	}


}
