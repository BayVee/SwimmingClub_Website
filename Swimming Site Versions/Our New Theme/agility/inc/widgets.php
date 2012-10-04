<?php
/**
 * Agility Widgets
 *
 * @package Agility
 * @since Agility 1.0
 */



class Agility_Recent_Posts_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
	 		'agility_recent_posts_widget', // Base ID
			'Agility Recent Posts', // Name
			array( 'description' => __( 'Agility Recent Posts Widget', 'agility' ) ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $before_widget;
		if ( ! empty( $title ) ){
			echo $before_title . $title . $after_title;
		}
		
		$cols = isset( $instance['cols'] ) ? $instance['cols'] : 'eight';
		$num = isset( $instance['num'] ) ? $instance['num'] : 2;
		agility_latest_bloglist( array( 'posts_per_page' => $num ) ,  $cols );

		echo $after_widget;
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['cols'] = strip_tags( $new_instance['cols'] );
		$instance['num'] = strip_tags( $new_instance['num'] );

		return $instance;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'New title', 'agility' );
		}
		if ( isset( $instance[ 'cols' ] ) ) {
			$cols = $instance[ 'cols' ];
		}
		else {
			$cols = 'eight';
		}
		if ( isset( $instance[ 'num' ] ) ) {
			$num = $instance[ 'num' ];
		}
		else {
			$num = 2;
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' , 'agility'); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'cols' ); ?>"><?php _e( 'Grid Columns:' , 'agility'); ?></label> 
		<select class="widefat" id="<?php echo $this->get_field_id( 'cols' ); ?>" name="<?php echo $this->get_field_name( 'cols' ); ?>">
			<option value="eight" <?php selected( $cols , 'eight' ); ?> >Eight (8) - Home Page</option>
			<option value="eleven" <?php selected( $cols , 'eleven' ); ?> >Eleven (11) - Main Content</option>
			<option value="four" <?php selected( $cols , 'four' ); ?> >Four (4) - Sidebar</option>
		</select>
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'num' ); ?>"><?php _e( 'Number of Posts:' , 'agility' ); ?></label> 
		<select class="widefat" id="<?php echo $this->get_field_id( 'num' ); ?>" name="<?php echo $this->get_field_name( 'num' ); ?>">
			<option value="1" <?php selected( $num , 1 ); ?> >1</option>
			<option value="2" <?php selected( $num , 2 ); ?> >2</option>
			<option value="3" <?php selected( $num , 3 ); ?> >3</option>
			<option value="4" <?php selected( $num , 4 ); ?> >4</option>
			<option value="5" <?php selected( $num , 5 ); ?> >5</option>
			<option value="6" <?php selected( $num , 6 ); ?> >6</option>
			<option value="7" <?php selected( $num , 7 ); ?> >7</option>
			<option value="8" <?php selected( $num , 8 ); ?> >8</option>
			<option value="9" <?php selected( $num , 9 ); ?> >9</option>
			<option value="10" <?php selected( $num , 10 ); ?> >10</option>
		</select>
		</p>
		<?php 
	}

} // class Author_Profile_Widget

// register Author_Profile_Widget widget
add_action( 'widgets_init', create_function( '', 'register_widget( "agility_recent_posts_widget" );' ) );