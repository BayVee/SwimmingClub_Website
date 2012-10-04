<?php
/**
 * BrickLayer Brick: Blog Grid
 *
 * Displays blog posts in a grid layout
 *
 */

class BlogGridBrick extends Brick{

	function __construct( $brick_id = -1 ){

		parent::__construct( 'BlogGrid' , __( 'Blog - Grid Layout', 'agility' ) , $brick_id );

		$this->addSetting( new BrickSetting( 'details', __( 'This brick will print the blog in a grid layout.  It can only be used '.
			'in a full-width (sixteen column) layout area, and must be a full-width brick.' , 'agility' ), 
			'info', '', $this->id , $this->brick_id ) );

	}

	public function draw( $container_cols, $columns = '' ){

		$this->before( '' );

		if( $container_cols != 16 ){
			echo '<div class="hint">'.__( 'The Blog Grid brick can only be used in a full-width (sixteen column) content area!' , 'agility' ).'</div><br/>';
		}

		$paged_var = is_front_page() ? 'page' : 'paged';
		$blog_query = new WP_Query( array( 
			'post_type'	=>	'post',
			'paged' 	=> 	get_query_var( $paged_var )
		));
		
		if ( $blog_query->have_posts() ) : 
			global $wp_query;
			$temp_query = $wp_query;
			$wp_query = $blog_query;
			?>
						<!-- Blog Grid Layout -->		
						<div class="mosaic col-3 cf clearfix blog-layout-grid">

							<?php /* Start the Loop */ $post_index = 0; ?>
							<?php while ( $blog_query->have_posts() ) : $blog_query->the_post(); ?>

								<?php
									get_template_part( 'content', 'grid' );
									$post_index++;
								?>

							<?php endwhile; ?>

							<div class="sixteen columns">
								<?php agility_content_nav( 'nav-below' , true ); ?>
							</div>

						</div>		
						<!-- end .mosaic -->
			<?php 
			$wp_query = $temp_query;
		endif;						
		wp_reset_query(); 

		$this->after();
	}


}
