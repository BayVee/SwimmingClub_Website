<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package Agility
 * @since Agility 1.0
 */

global $agilitySettings;
?>

	<footer id="colophon" class="site-footer" role="contentinfo">
		
		<!-- .footer-upper -->
		<div class="footer-upper container" style="background-color:transparent;">

			<div class="one-third column">

				<!-- Widget Area: Footer Left -->
				<?php dynamic_sidebar( 'footer-left' ); ?>
				<!-- End Widget Area: Footer Left -->

			</div>


			<div class="one-third column">

				<!-- Widget Area: Footer Center -->
				<?php dynamic_sidebar( 'footer-center' ); ?>
				<!-- End Widget Area: Footer Center -->

			</div>

			<div class="one-third column">
				
				<!-- Widget Area: Footer Right -->
				<?php dynamic_sidebar( 'footer-right' ); ?>
				<!-- End Widget Area: Footer Right -->

			</div>
		
		</div>
		<!-- end .footer-upper -->
		
		<!-- #footer-base .site-info -->
		<div id="footer-base" class="site-info">
			
			<div class="container" style="background-color:transparent;">
				<?php do_action( 'agility_credits' ); ?>
				<div class="eight columns"><?php echo $agilitySettings->op( 'footer-left' ); ?></div>
				<div class="eight columns far-edge"><?php echo $agilitySettings->op( 'footer-right' ); ?></div>
			</div>
			
		</div>
		<!-- end #footer-base .site-info -->
		
	</footer>
	<!-- end #colophon .site-footer -->
	
</div>
<!-- end #page .wrap .hfeed .site -->

<?php if( ( $backToTop = $agilitySettings->op( 'back-to-top' ) ) !== 'off' ): ?>
<!-- Back to Top -->
<a id="back-to-top" class="back-to-top-<?php echo $backToTop; ?>" href="#" title="<?php _e( 'Back to top', 'agility' ); ?>"><?php _e( 'Top', 'agility' ); ?></a>
<!-- end Back to Top -->
<?php endif; ?>

<!-- Begin wp_footer()
================================================== -->
<?php wp_footer(); ?>

<!-- end wp_footer() -->
	
</body>
</html>