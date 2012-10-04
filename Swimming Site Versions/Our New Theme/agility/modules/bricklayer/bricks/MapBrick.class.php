<?php
/**
 * BrickLayer Brick: Map
 *
 * Displays a Google Map
 *
 */


class MapBrick extends Brick{

	function __construct( $brick_id = -1 ){

		parent::__construct( 'Map' , __( 'Map', 'agility' ) , $brick_id );

		$this->addSetting( new BrickSetting( 'details', __( 'This brick will display a map based on the parameters you set below. ', 'agility' ),
			'info', '', $this->id , $this->brick_id ) );

		$this->addSetting( new BrickSetting( 'title', __( 'Title', 'agility' ), 'text', '', $this->id, $this->brick_id ) );

		$zoom_config = array(
			'ops'	=> array(
				0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23
			),
		);
		$this->addSetting( new BrickSetting( 'zoom', __( 'Zoom', 'agility' ), 
			'select', 8, $this->id , $this->brick_id , $zoom_config ) );

		$this->addSetting( new BrickSetting( 'lat' , __( 'Latitude' , 'agility' ), 
			'text', '', $this->id , $this->brick_id , array( 'desc' => __( 'Decimal Latitude', 'agility' ).'.  e.g. "-34.397".  You can <a target="_blank" href="http://www.gpsvisualizer.com/geocode">geocode your address here</a>' ) ) );

		$this->addSetting( new BrickSetting( 'lng' , __( 'Longitude' , 'agility' ), 
			'text', '', $this->id , $this->brick_id , array( 'desc' => __( 'Decimal Longitude', 'agility' ).'.  e.g. "150.644".  You can <a target="_blank" href="http://www.gpsvisualizer.com/geocode">geocode your address here</a>' ) ) );

		$this->addSetting( new BrickSetting( 'address' , __( 'Address' , 'agility' ), 
			'text', '', $this->id , $this->brick_id , array( 'desc' => __( 'The address will be geocoded on the fly', 'agility' ) ) ) );

		$this->addSetting( new BrickSetting( 'map_title' , __( 'Map Marker Title' , 'agility' ), 
			'text', '', $this->id , $this->brick_id , array( 'desc' => __( 'The map marker title will appear when your mouse hovers over the map marker', 'agility' ) ) ) );

		$this->addSetting( new BrickSetting( 'height' , __( 'Height' , 'agility' ), 
			'text', '250px', $this->id , $this->brick_id , array( 'desc' => __( 'Height of the map.  Include CSS-valid units.', 'agility' ) ) ) );

		$this->addSetting( new BrickSetting( 'width' , __( 'Width' , 'agility' ), 
			'text', '100%', $this->id , $this->brick_id , array( 'desc' => __( 'For responsiveness, leave this at 100%', 'agility' ) ) ) );

	}

	public function draw( $container_cols, $columns = '' ){

		$this->before( $columns );


		if( $this->getSetting( 'title' ) ): ?>

		<h3 class="brick-title"><?php echo $this->getSetting( 'title' ); ?></h3>

		<?php endif;

		$height = $this->getSetting( 'height' );
		if( is_numeric( $height ) ) $height.= 'px';
		$width = $this->getSetting( 'width' );
		if( is_numeric( $width ) ) $width.= 'px';
		
		$atts = array();
		$atts['zoom']	= $this->getSetting( 'zoom' );
		$atts['lat']	= $this->getSetting( 'lat' );
		$atts['lng']	= $this->getSetting( 'lng' );
		$atts['address']= $this->getSetting( 'address' );
		$atts['title']	= $this->getSetting( 'map_title' );
		$atts['height']	= $height;
		$atts['width']	= $width;
		
		echo agility_shortcode_map( $atts );
	
		$this->after();
	}


}
