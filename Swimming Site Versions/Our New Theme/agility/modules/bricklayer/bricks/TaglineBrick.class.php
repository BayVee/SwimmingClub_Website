<?php
/**
 * BrickLayer Brick: Tagline
 *
 * Displays a tagline
 *
 */


class TaglineBrick extends Brick{

	function __construct( $brick_id = -1 ){

		parent::__construct( 'Tagline' , __( 'Tagline', 'agility' ) , $brick_id );

		$this->addSetting( new BrickSetting( 'details', __( 'This brick will display a custom tagline. ', 'agility' ),
			'info', '', $this->id , $this->brick_id ) );

		$this->addSetting( new BrickSetting( 'text', __( 'Tagline Text', 'agility' ), 'textarea' , 
			'', $this->id , $this->brick_id ) );
		$this->addSetting( new BrickSetting( 'fleuron', __( 'Include Fleuron', 'agility' ), 'checkbox', 
			false, $this->id, $this->brick_id ), array( 'desc' => __( 'The fleuron is the floral ornamentation displayed below the tagline.', 'agility' ) ) );
		$this->addSetting( new BrickSetting( 'class', __( 'Custom Class', 'agility' ), 'text', 
			false, $this->id, $this->brick_id , array( 'desc' => __( 'Add custom classes to the .tagline div if desired', 'agility' ) ) ) );
	}

	public function draw( $container_cols, $columns = '' ){
		$this->before( $columns );
		?>
		<div class="tagline <?php echo $this->getSetting( 'class' ); ?>"><?php echo $this->getSetting( 'text' ); ?>
		<?php if( $this->getSetting( 'fleuron' )): ?>
		<span class="fleuron"></span>
		<?php endif; ?>
		</div>
		<?php
		$this->after();
	}

}