<?php
/**
 * BrickLayer Brick: Custom HTML
 *
 * Displays any custom HTML, including shortcodes
 *
 */


class CustomBrick extends Brick{

	function __construct( $brick_id = -1 ){

		parent::__construct( 'Custom' , __( 'Custom', 'agility' ) , $brick_id );

		$this->addSetting( new BrickSetting( 'details', __( 'Add any content you like to this Brick, including shortcodes.' , 'agility' ), 
			'info', '', $this->id , $this->brick_id ) );

		$this->addSetting( new BrickSetting( 'html', __( 'Custom HTML' , 'agility' ), 'textarea' , 
			'', $this->id , $this->brick_id ) );
	}

	public function draw( $container_cols, $columns = '' ){
		$this->before( $columns );
		echo do_shortcode( $this->getSetting( 'html' ) );
		$this->after();
	}

}