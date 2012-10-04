<?php
/**
 * BrickLayer Brick: Page Content
 *
 * Displays the content of the current Page
 *
 */


class PageContentBrick extends Brick{

	function __construct( $brick_id = -1 ){

		parent::__construct( 'PageContent' , __( 'Page Content', 'agility' ) , $brick_id );

		$this->addSetting( new BrickSetting( 'details', __( 'This brick will automatically include the content from the current page that this layout is applied to.', 'agility' ), 
			'info', '', $this->id , $this->brick_id ) );

	}

	public function draw( $container_cols, $columns = '' ){

		$this->before( $columns );
//TODO change 'page' to current post type?
		while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', 'page-notitle' ); ?>

		<?php endwhile; // end of the loop. 

		$this->after();
	}


}