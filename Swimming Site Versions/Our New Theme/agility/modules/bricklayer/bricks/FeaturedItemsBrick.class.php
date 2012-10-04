<?php
/**
 * BrickLayer Brick: Featured Items
 *
 * Displays a Featured Items content block based on Portfolio
 *
 */


class FeaturedItemsBrick extends Brick{

	function __construct( $brick_id = -1 ){

		parent::__construct( 'FeaturedItems' , __( 'Featured Items' , 'agility' ) , $brick_id );

		$this->addSetting( new BrickSetting( 'details', __( 'This brick should always be full-width within its container.  It displays a three-column "featured item" display of a portfolio, as displayed in the home page templates', 'agility' ), 
			'info', '', $this->id , $this->brick_id ) );

		$this->addSetting( new BrickSetting( 'title', __( 'Title', 'agility' ), 'text', '', $this->id, $this->brick_id ) );

		$sq_config = array(
			'ops'	=> agility_portfolio_ops(),
			'desc'	=> __( 'Choose from portfolios you have created', 'agility' ) .' or <a href="'.
						admin_url('admin.php?page=portfolioconstructor'). '" target="_blank">create a new portfolio</a> with the PortfolioConstructor'
		);
		$this->addSetting( new BrickSetting( 'portfolio_query_id', __( 'Portfolio', 'agility' ), 
			'select', '', $this->id , $this->brick_id , $sq_config ) );

	}

	public function draw( $container_cols, $columns = '' ){

		$set_alphaomega = false;
		if( $container_cols != 16 ) $set_alphaomega = true;

		$this->before( '' );


		if( $this->getSetting( 'title' ) ): ?>

		<h6 class="<?php if( $container_cols == 16 ) echo $this->getColumnsClass( $this->getSetting( '_grid_columns' ) ); ?>"><?php echo $this->getSetting( 'title' ); ?></h6>

		<?php endif;
		
		$query_id 	= $this->getSetting( 'portfolio_query_id' );
		agility_featured_items( $query_id , 'query_id' , $set_alphaomega );

		$this->after();
	}


}
