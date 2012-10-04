<?php

/**
 * BrickLayer Brick
 *
 * Abstract class to model an individual Brick.  All Bricks must
 * extend this class.
 *
 */


abstract class Brick{

	public $id;				//the brick type
	public $title;
	protected $settings;

	public $brick_id;
	public $brick_title;

	public $does_not_exist;

	/**
	 * $id must be the first part of the ClassName, e.g. for "TaglineBrick", ID would 
	 * be "Tagline"
	 */
	function __construct( $id, $title, $brick_id = -1 ){

		$this->id = $id;
		$this->title = $title;
		
		$this->brick_id = $brick_id;
		$this->settings = array();
		$this->does_not_exist = false;

		if( $this->brick_id == -1 ){
			$this->brick_title = __( 'Untitled', 'agility' ).' '.$this->title;
		}
		else{
			$post = get_post( $this->brick_id );
			if( is_null( $post ) ){
				$this->does_not_exist = true;
			}
			else{
				$this->brick_title = $post->post_title;
			}
		}

	}

	function addSetting( $setting ){
		$this->settings[$setting->id] = $setting;
	}

	function getSetting( $setting_id ){
		if( !isset( $this->settings[$setting_id] ) ){
			return get_post_meta( $this->brick_id , $setting_id , true );
		}
		$setting = $this->settings[$setting_id];

		return $setting->getValue();
	}


	function showUI(){

		if( get_post_meta( $this->brick_id , '_new_row', true ) == 'on' ): ?><li class="brick-newrow"></li><?php endif; 
		?><li class="brick brick-type-<?php echo $this->id; ?> draggable <?php 
			$gridColumns = get_post_meta( $this->brick_id , '_grid_columns' , true );
			if( $gridColumns ){	echo 'brick-column brick-grid-'.$gridColumns; echo '" data-columns="'.$gridColumns; }	?>">
			<div class="brick-innards">
			<?php 
				$this->buildHandle();
				$this->buildSettings();
			?>
			</div>
		</li><?php
	}

	function buildHandle(){
		?>
			<div class="brick-handle"><span class="brick-type"><?php echo $this->title; 
				?></span><span class="brick-title"><input type="text" value="<?php echo $this->brick_title; ?>" data-name="brick_title"/></span>
				<span class="brick-handle-arrow"></span></div>
		<?php
	}

	function buildSettings(){

		?>
		<!-- .brick-settings -->
		<div class="brick-settings">
			<input type="hidden" value="<?php echo $this->brick_id; ?>" data-name="brick_id"/>
			<input type="hidden" value="<?php echo $this->id; ?>" data-name="brick_type" />

		<?php foreach( $this->settings as $setting ){
			$setting->show( $this->id );
		} 

		$this->customUI();

		$this->buildControls();
		?>

		</div>
		<!-- end .brick-settings -->
		<?php
	}

	protected function customUI(){
		//Does nothing by default, can be overridden
	}

	public function buildControls(){
		$bookmark = get_post_meta( $this->brick_id , '_bookmark' , true ) == 'on' ? 'on' : 'off';
		$newRow = get_post_meta( $this->brick_id , '_new_row' , true ) == 'on' ? 'on' : 'off';
		$gridColumns = get_post_meta( $this->brick_id , '_grid_columns' , true );
		?>
		<div class="brick-controls">

			<div class="brick-controls-block">
				<!-- Grid Columns -->
				<!--<a href="#" title="Decrease Grid Columns" class="brick-control brick-control-grid-smaller"></a>-->
				<a href="#" title="Set Grid Columns" class="brick-control brick-control-grid tooltip" 
					data-tooltip="Change the width of this brick (in grid columns).">
				<span class="grid-columns">
					<ul>
						<li data-val="1-4" class="over-4" <?php $this->gridColumnsCurrent( $gridColumns, '1-4' ); ?>>1/4 (One Quarter)</li>
						<li data-val="1-3" class="over-4" <?php $this->gridColumnsCurrent( $gridColumns, '1-3' ); ?>>1/3 (One Third)</li>
						<li data-val="1-2" class="over-4" <?php $this->gridColumnsCurrent( $gridColumns, '1-2' ); ?> >1/2 (One Half)</li>
						<li data-val="2-3" class="over-4" <?php $this->gridColumnsCurrent( $gridColumns, '2-3' ); ?>>2/3 (Two Thirds)</li>
						<li data-val="3-4" class="over-4" <?php $this->gridColumnsCurrent( $gridColumns, '3-4' ); ?>>3/4 (Three Quarters)</li>
						
						
						<li data-val="" class="over-4" >&ndash;</li>						
						
						<li data-val="2" <?php $this->gridColumnsCurrent( $gridColumns, '2' ); ?>>2 (Two Columns)</li>
						<li data-val="3" <?php $this->gridColumnsCurrent( $gridColumns, '3' ); ?>>3 (Three Columns)</li>
						<li data-val="4" <?php $this->gridColumnsCurrent( $gridColumns, '4' ); ?>>4 (Four Columns)</li>
						<li data-val="5" class="over-4" <?php $this->gridColumnsCurrent( $gridColumns, '5' ); ?>>5 (Five Columns)</li>
						<li data-val="6" class="over-4" <?php $this->gridColumnsCurrent( $gridColumns, '6' ); ?>>6 (Six Columns)</li>
						<li data-val="7" class="over-4" <?php $this->gridColumnsCurrent( $gridColumns, '7' ); ?>>7 (Seven Columns)</li>
						<li data-val="8" class="over-4" <?php $this->gridColumnsCurrent( $gridColumns, '8' ); ?>>8 (Eight Columns)</li>
						<li data-val="9" class="over-4" <?php $this->gridColumnsCurrent( $gridColumns, '9' ); ?>>9 (Nine Columns)</li>
						<li data-val="10" class="over-4" <?php $this->gridColumnsCurrent( $gridColumns, '10' ); ?>>10 (Ten Columns)</li>
						<li data-val="11" class="over-4" <?php $this->gridColumnsCurrent( $gridColumns, '11' ); ?>>11 (Eleven Columns)</li>
						<li data-val="12" class="over-11 over-4" <?php $this->gridColumnsCurrent( $gridColumns, '12' ); ?>>12 (Twelve Columns)</li>
						<li data-val="13" class="over-11 over-4" <?php $this->gridColumnsCurrent( $gridColumns, '13' ); ?>>13 (Thirteen Columns)</li>
						<li data-val="14" class="over-11 over-4" <?php $this->gridColumnsCurrent( $gridColumns, '14' ); ?>>14 (Fourteen Columns)</li>
						<li data-val=""  class="over-11" <?php $this->gridColumnsCurrent( $gridColumns, '' ); ?>><?php _e( 'Full Width', 'agility' ); ?></li>
					</ul>
					<input type="hidden" data-name="grid_columns" value="<?php echo $gridColumns; ?>" />
				</span></a>
				<!--<a href="#" title="Increase Grid Columns" class="brick-control brick-control-grid-larger"></a>-->
			</div>


			<div class="brick-controls-block">
				<!-- New Row -->
				<a href="#" title="New Row" class="brick-control brick-control-new-row <?php if( $newRow == 'on' ) echo 'brick-control-active'; ?> tooltip" 
					data-tooltip="<?php _e( 'End the previous row and start a new row before this brick.', 'agility' ); ?>"></a>
				<input type="hidden" value="<?php echo $newRow; ?>" data-name="new_row" />

				<!-- Bookmark -->
				<a href="#" title="Bookmark Brick" class="brick-control brick-control-bookmark <?php if( $bookmark == 'on' ) echo 'brick-control-active'; ?> tooltip" 
					data-tooltip="<?php _e( 'Bookmark this brick instance to make it available for use in other layouts.', 'agility' ); ?>"></a>
				<input type="hidden" value="<?php echo $bookmark; ?>" data-name="bookmark_brick" />

				<!-- Trash -->
				<a href="#" title="Delete Brick" class="brick-control brick-control-delete tooltip" 
					data-tooltip="<?php _e( 'Mark this brick for deletion.  It will be deleted next time you save the layout, and will no longer be available for any layout.', 'agility' ); ?>"></a>
				<input type="hidden" value="off" data-name="delete_brick" />
			</div>
		</div>
		<?php
	}

	private function gridColumnsCurrent( $gridColumns , $col ){
		if( $gridColumns == $col ): ?>class="grid-columns-current"<?php endif; 
	}

	public function save( $brick_data , $brick_index ){

		//Check if deleting
		if( $brick_data['delete_brick'][$brick_index] == 'on' ){
			$this->selfDestruct();
			return -1;
		}

		$brick_title = $brick_data['brick_title'][$brick_index];

		$post = array(
			'post_title' 	=> wp_strip_all_tags( $brick_title ),
			'post_status' 	=> 'publish',
			'post_type'		=> 'brick'
		);

		//Update Brick, set ID
		if( $this->brick_id != -1 ){
			$post['ID']	= $this->brick_id;
		}

		$this->brick_id = wp_insert_post( $post );


		$bookmark = $brick_data['bookmark_brick'][$brick_index];
		$new_row = $brick_data['new_row'][$brick_index];
		$grid_columns = $brick_data['grid_columns'][$brick_index];

		
		update_post_meta( $this->brick_id , '_brick_type' , $this->id );
		update_post_meta( $this->brick_id , '_bookmark', $bookmark );
		update_post_meta( $this->brick_id , '_new_row' , $new_row );
		update_post_meta( $this->brick_id , '_grid_columns' , $grid_columns );

		//Now go through the settings and save
		foreach( $this->settings as $setting ){
			if( $setting->type != 'info' ){
				$value = $setting->retrieveValue( $brick_data, $brick_index );
				$setting->save( $this->brick_id , $value );
			}
		}

		return $this->brick_id;

	}

	private function selfDestruct(){
		wp_delete_post( $this->brick_id );
	}

	public static function createBrick( $brick_id , $brick_type = '' ){

		if( $brick_type == '' ){
			$brick_type = get_post_meta( $brick_id , '_brick_type' , true );
			if( !$brick_type ){
				$brick_type = 'Dummy';
				//return false;
			}
		}
		$classname = $brick_type.'Brick';
		return new $classname( $brick_id );

	}



	/* Front end */
	abstract function draw( $container_cols, $columns = '' );

	function before( $columns ){
		$grid_columns = get_post_meta( $this->brick_id , '_grid_columns' , true );
		if( $grid_columns ){
			$columns = $this->getColumnsClass( $grid_columns );
		}

		?>

		<?php if( get_post_meta( $this->brick_id , '_new_row', true ) == 'on' ): ?>
		</div>
		<!-- end row -->

		<!-- begin new row within blueprint area -->
		<div class="row row-before-<?php echo $this->id; ?>" id="row-before-brick-<?php echo $this->brick_id; ?>">
		<?php endif; ?>

		<!-- Brick :: <?php echo $this->id .' :: '.$this->brick_title; ?> -->
		<div class="brick brick-<?php echo $this->id; ?> <?php echo $columns; ?>" id="brick-<?php echo $this->brick_id; ?>" >
		<?php
	}

	function after(){
		?>
		</div>
		<!-- end Brick :: <?php echo $this->id; ?> -->
		<?php
	}

	function getColumnsClass( $columns ){

		switch( $columns ){

			case '1-2':
				return 'one-half column';
			case '1-3':
				return 'one-third column';
			case '2-3':
				return 'two-thirds column';
			case '1-4':
				return 'one-fourth column';
			case '3-4':
				return 'three-fourths column';

			case 2:
				return 'two columns';
			case 3:
				return 'three columns';
			case 4:
				return 'four columns';
			case 5:
				return 'five columns';
			case 6:
				return 'six columns';
			case 7:
				return 'seven columns';
			case 8:
				return 'eight columns';
			case 9:
				return 'nine columns';
			case 10:
				return 'ten columns';
			case 11:
				return 'eleven columns';
			case 12:
				return 'twelve columns';
			case 13:
				return 'thirteen columns';
			case 14:
				return 'fourteen columns';
			case 15: 
				return 'fifteen columns';
			default:
				return 'full-width columns';


		}

	}
}

/**
 * BrickLayer Brick Setting
 *
 * Models an individual setting for a Brick.  Responsible for
 * displaying and saving itself.
 *
 */

class BrickSetting{


	public $id;
	public $title;
	public $type;
	public $default;
	public $value;
	public $config;

	public $brick_type;
	public $brick_id;
	private $prefix;

	function __construct( $id, $title, $type, $default, $brick_type, $brick_id, $config = array() ){
		$this->id = $id;
		$this->title = $title;
		$this->type = $type;
		$this->default = $default;
		$this->config = $config;

		$this->brick_type = $brick_type;
		$this->brick_id = $brick_id;

		$this->prefix = isset( $config['prefix'] ) ? $config['prefix'] : '_';

	}

	function show(){
		$value = $this->getValue();		

		?>
		<div class="brick-setting brick-setting-<?php echo $this->type; ?> <?php echo "{$this->brick_type}-{$this->id}_container"; ?>">
			
		<?php
		//Title
		?>
		<label><?php echo $this->title; ?></label>
		<?php

		$class = "{$this->brick_type}-{$this->id}";
		$name = $this->getName();

		//Form Element
		switch( $this->type ){

			case 'text':
				?>
				<input type="text" class="<?php echo $class; ?>" data-name="<?php echo $name; ?>" value="<?php echo stripslashes( $value ); ?>" />
				<?php

				break;

			case 'select':
				
				$ops = $this->config['ops'];
				?>
				
				<select data-name="<?php echo $name; ?>" <?php if( isset( $settings['multiple'] ) ) echo 'multiple="multiple"'; ?> >
					<?php foreach( $ops as $key => $val ): ?>
						<?php $selected = '';
							if( isset( $value ) ){
								if( is_array( $value ) ){
									if( in_array( $key , $value ) ){
										$selected = 'selected="selected"';
									}
								}
								else if( $key == $value ){
									$selected = 'selected="selected"';
								}
							}
						?>
						<option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $val; ?></option>
					<?php endforeach; ?>
				</select>
				<?php

				break;

			case 'textarea';

				?>
				<textarea class="<?php echo $class; ?>" data-name="<?php echo $name; ?>"><?php echo $value; ?></textarea>
				<?php

				break;

			case 'checkbox':
				$ischecked = $value;// == 'on' ? true : false;

				?>
				<input type="checkbox" id="<?php echo $name; ?>" data-name="<?php echo $name; ?>" <?php echo checked( $ischecked, true, false ); ?> />
				<?php
				//$html.= $desc;

				break;

			case 'radio':

				break;

			case 'info':
				
				break;

		}
		if( isset( $this->config['desc'] ) ) echo '<span class="brick-setting-desc">'.$this->config['desc'].'</span>'; 
		?>
		</div>
		<?php
	}

	public function getName(){
		return "{$this->brick_type}_{$this->id}";
	}

	function save( $brick_id , $value ){
		update_post_meta( $brick_id , $this->prefix.$this->id, $value );
	}

	function getValue(){
		if( $this->brick_id != -1 ){
			$value = get_post_meta( $this->brick_id , $this->prefix.$this->id , true );

			switch( $this->type ){

				case 'checkbox':
					$value = $value == 'on' ? true : false;
					break;

				default:
					return $value;

			}
		}
		else{
			$value = $this->default;	//set to default;
		}
		return $value;
	}

	function retrieveValue($brick_data, $brick_index ){
		$name = $this->getName();
		if( isset(  $brick_data[$name] ) ){
			if( isset( $brick_data[$name][$brick_index] ) ){
				return $brick_data[$name][$brick_index];
			}
		}
		return $this->default;

	}

}