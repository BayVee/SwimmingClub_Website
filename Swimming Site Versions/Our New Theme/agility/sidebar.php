<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package Agility
 * @since Agility 1.0
 */

global $post;
$sidebar_offset = 'offset-by-one';
if( isset( $GLOBALS['sidebar_offset'] ) ) $sidebar_offset = $GLOBALS['sidebar_offset'];

$sidebar_id = '';
if( $post ) $sidebar_id = get_post_meta( $post->ID , 'custom_sidebar', true ); //sidebar-1';
if( !$sidebar_id ) $sidebar_id = 'sidebar-1';

?>

		<!-- Begin sidebar.php
		================================================== -->
		<div id="secondary" class="widget-area sidebar four columns <?php echo $sidebar_offset; ?>" role="complementary">
			
			<?php do_action( 'before_sidebar' ); ?>
			<?php if ( ! dynamic_sidebar( $sidebar_id ) ) : ?>

				<aside id="no_widgets" class="widget">
					<div class="alert alert-warning">
						<h6><?php global $wp_registered_sidebars; 
							if( isset( $wp_registered_sidebars[$sidebar_id])) echo $wp_registered_sidebars[$sidebar_id]['name']; 
							?></h6>
						<p><?php _e( 'You haven\'t added any widgets to this Widget Area yet! '.
						'Log into your admin panel and navigate to Appearance &gt; Widgets '.
						'to get started.', 'agility' ); ?></p>
					</div>
				</aside>

			<?php endif; // end sidebar widget area ?>
			
		</div>
		<!-- end sidebar.php #secondary .sidebar .widget-area -->
