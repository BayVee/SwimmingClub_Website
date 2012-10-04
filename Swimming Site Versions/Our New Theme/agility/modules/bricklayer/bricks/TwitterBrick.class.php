<?php
/**
 * BrickLayer Brick: Twitter
 *
 * Displays the latest tweet
 *
 */


class TwitterBrick extends Brick{

	function __construct( $brick_id = -1 ){

		parent::__construct( 'Twitter' , __( 'Twitter', 'agility' ) , $brick_id );

		$this->addSetting( new BrickSetting( 'details', __( 'This brick will display the latest Tweet from the specified Twitter account.', 'agility' ),
			'info', '', $this->id , $this->brick_id ) );

		$this->addSetting( new BrickSetting( 'title', __( 'Title', 'agility' ), 'text' , 
			'', $this->id , $this->brick_id ,array( 'desc' => __( 'The title will be displayed above the latest Tweet', 'agility' ) ) ) );
		$this->addSetting( new BrickSetting( 'twitter_name', __( 'Twitter Name', 'agility' ), 'text', 
			'', $this->id, $this->brick_id , array( 'desc' => __( 'Leave blank to use name set in the Agility Control Panel', 'agility' ) ) ) );
		
	}

	public function draw( $container_cols, $columns = '' ){

		global $agilitySettings;
		$twitter_name = $this->getSetting( 'twitter_name' );
		if( !$twitter_name ) $twitter_name = $agilitySettings->op( 'twitter' );

		$this->before( $columns );

		?>

		<?php if( $title = $this->getSetting( 'title' ) ): ?>
		<h6><?php echo $title; ?></h6>
		<?php endif; ?>

		<div id="tweet" data-account="<?php echo $twitter_name; ?>"><?php _e( 'Loading Tweets...', 'agility' ); ?></div>
		<?php

		$this->after();
	}

}