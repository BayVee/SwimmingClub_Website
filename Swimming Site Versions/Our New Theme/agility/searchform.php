<?php
/**
 * The template for displaying search forms in Agility
 *
 * @package Agility
 * @since Agility 1.0
 */
?>
	<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
		<label for="s" class="fallback"><?php _e( 'Search', 'agility' ); ?></label>
		<input type="text" class="field" name="s" id="s" placeholder="<?php esc_attr_e( 'Enter to search &rarr;', 'agility' ); ?>" />
		<input type="submit" class="submit" name="submit" id="searchsubmit" value="<?php esc_attr_e( 'Search', 'agility' ); ?>" />
	</form>
