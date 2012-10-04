<?php
/**
 * BrickLayer Brick: Horizontal Rule
 *
 * Displays a Horizontal Rule
 *
 */


class HRBrick extends Brick{

	function __construct( $brick_id = -1 ){

		parent::__construct( 'HR' , '&lt;HR&gt;' , $brick_id );

		$this->addSetting( new BrickSetting( 'details', __( 'This brick will print a horizontal rule of your choice ', 'agility' ),
			'info', '', $this->id , $this->brick_id ) );
		
		$config = array( 'ops' => array( 
			'default'	=>	__( 'Default', 'agility' ),
			'fat'		=>	__( 'Fat', 'agility' ),
			'mini'		=>	__( 'Mini', 'agility' ),
			'stripes'	=>	__( 'Stripes', 'agility' ),
			'fleuron' 	=> 	__( 'Fleuron', 'agility' ),
			)
		);
		$this->addSetting( new BrickSetting( 'hr_type', __( 'Type', 'agility' ), 
			'select', '', $this->id , $this->brick_id , $config ) );

		$this->addSetting( new BrickSetting( 'hr_class', __( 'Custom Class', 'agility' ), 
			'text', '', $this->id , $this->brick_id ) );

	}

	public function draw( $container_cols, $columns = '' ){

		$this->before( $columns );


		$hr_type 	= $this->getSetting( 'hr_type' );
		$hr_class 	= $this->getSetting( 'hr_class' );

		switch( $hr_type ){

			case 'default':
				?>

				<hr <?php if( $hr_class ) echo 'class="'.$hr_class.'"'; ?>/>

				<?php
				break;

			case 'mini':
			case 'fat':
			case 'stripes':
				$class = $hr_type.' '.$hr_class;
				?>

				<hr class="<?php echo $class; ?>"/>

				<?php

				break;

			case 'fleuron':
				?>

				<span class="fleuron <?php if( $hr_class ) echo $hr_class; ?>"></span>

				<?php
				break;


		}

		$this->after();
	}


}
