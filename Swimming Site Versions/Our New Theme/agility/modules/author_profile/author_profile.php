<?php

/**
 * Template tag: display the author profile
 */
function agility_author_profile(){
	global $post;
	
	$curauth = get_userdata( $post->post_author );
	
	?>
			<div class="author-profile cf">
				<h6 class="author-bio-tag"><?php _e( 'About the Author', 'agility' ); ?></h6>
				<div class="three columns alpha">
					<?php
						$author_profile_image = get_the_author_meta( 'author_profile_image', $curauth->ID );
						if( $author_profile_image ){
							?>
							<img src="<?php echo $author_profile_image; ?>" alt="<?php echo $curauth->display_name; ?>" title="<?php echo $curauth->display_name; ?>" class="scale-with-grid" />
							<?php
						}
						else{
							echo get_avatar( $curauth->ID , 160 );
						}
					?>

				</div>
				<div class="eight columns omega">
					<h5><?php echo $curauth->display_name; ?></h5>
					<div class="author-bio-blurb"><?php echo $curauth->description; ?></div>
				</div>
			</div>
	<?php
}

/**
 * Author Profile Widget
 */
class Author_Profile_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
	 		'author_profile_widget', // Base ID
			'Author Profile Widget', // Name
			array( 'description' => __( 'Author Profile Widget', 'agility' ) ) // Args
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
		
		agility_author_profile();

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
			$title = __( 'New title', 'text_domain' );
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' , 'agility'); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p><?php _e( 'Designed to be 11 columns wide and fit below the standard post content in the "After Post" widget area.', 'agility' ); ?></p>
		<?php 
	}

} // class Author_Profile_Widget

// register Author_Profile_Widget widget
add_action( 'widgets_init', create_function( '', 'register_widget( "author_profile_widget" );' ) );



/**
 * ADMIN
 **/

add_action( 'show_user_profile', 'agility_author_profile_fields' );
add_action( 'edit_user_profile', 'agility_author_profile_fields' );

/**
 * Display new Author Profile fields in User edit screen
 */
function agility_author_profile_fields( $user ) { ?>

	<h3>Agility Profile Fields</h3>

	<table class="form-table">

		<tr valign="top">
			<th scope="row"><?php _e( 'Author Image', 'agility' ); ?></th>
			<td>
				<label for="upload_image">
					<input id="author_profile_image" type="text" size="36" name="author_profile_image" value="<?php the_author_meta( 'author_profile_image', $user->ID ); ?>" />
					<input id="author_profile_image_button" type="button" value="Upload Image" />
					<br /><?php _e( 'Enter a URL or upload an image to use in the Author Bio.', 'agility' ); ?>
				</label>
				<br/>
				<img id="author_profile_image_preview" src="<?php the_author_meta( 'author_profile_image', $user->ID ); ?>" style="max-width:300px;" />
			</td>
		</tr>
	</table>
<?php }

function agility_author_profile_assets() {
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
	wp_enqueue_script('agility-profile-upload', get_template_directory_uri().'/modules/author_profile/author_profile.js', array('jquery','media-upload','thickbox') );

	wp_enqueue_style('thickbox');
}

global $pagenow;
if( in_array( $pagenow, array( 'profile.php' , 'user-edit.php' ) ) ) {
	add_action('admin_print_scripts', 'agility_author_profile_assets');
}

add_action( 'personal_options_update', 'agility_save_author_profile_fields' );
add_action( 'edit_user_profile_update', 'agility_save_author_profile_fields' );

function agility_save_author_profile_fields( $user_id ) {

	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;

	update_user_meta( $user_id, 'author_profile_image', $_POST['author_profile_image'] );
}