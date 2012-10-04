<?php
/**
 * BrickLayer Brick: Dummy
 *
 * This Brick is used to fill in if a brick has been deleted or cannot be located.
 *
 */


class DummyBrick extends Brick{


	function __construct(){

	}


	function showUI(){
		?>
			<li class="brick-deleted">
				<?php _e( 'Brick has been deleted or is missing, and will be removed from '.
				'this layout on the next save.', 'agility' ); ?>
			</li>
		<?php
	}


	public function draw( $container_cols, $columns = '' ){
		?>
		<!-- BrickLayer was asked to draw a deleted brick.  This brick should be removed from the Layout -->
		<?php
	}

	
}