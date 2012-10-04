<?php
/**
 * Agility Theme Options
 *
 * @package Agility
 * @since Agility 1.0
 */
$agilitySettings;
define( 'AGILITY_SETTINGS', 'agility_settings' );
agility_theme_options_init();

/**
 * Initialize the Spark Options framework and define configurable settings
 * 
 * @since Agility 1.0
 */
function agility_theme_options_init() {

	global $agilitySettings;

	$agilitySettings = new AgilityOptions( 
							AGILITY_SETTINGS, 
							
							//Menu Page
							array(
								'type'			=> 'menu_page',
								'parent_slug' 	=> 'agility',
								'page_title'	=> __( 'Agility Settings' , 'agility' ),
								'menu_title'	=> 'Agility',
								'menu_slug'		=> 'agility-settings',
							),
							
							//Links
							array(

								1	=>	array(
									'href'	=>	'http://agility.sevenspark.com/help',
									'class'	=>	'spark-outlink-hl',
									'title'	=>	__( 'Read the Support Manual' , 'agility' ),
									'text'	=>	__( 'Support Manual &rarr;' , 'agility' ),							
								),
								
								2	=>	array(
									'href'	=>	'http://agility.sevenspark.com/help#faqs',
									'class'	=>	'spark-outlink',
									'title'	=>	__( 'Frequently Asked Questions' , 'agility' ),
									'text'	=>	__( 'FAQs  &rarr;' , 'agility' ),
								),
								
								3	=>	array(
									'href'	=>	'http://sevenspark.com/out/support',
									'class'	=>	'spark-outlink',
									'title'	=>	__( 'Submit a support request' , 'agility' ),
									'text'	=>	__( 'Get Support &rarr;' , 'agility' ),
								),
								
								4	=>	array(
									'href'	=>	'http://sevenspark.com/out/agility-wp',
									'class'	=>	'spark-outlink',
									'title'	=>	__( 'Purchase a license for use on a second installation' , 'agility' ),
									'text'	=>	__( 'Additional License &rarr;' , 'agility' ),
								)
							),
							'theme'
							
						);
		
		
		
		/*
		 * Basic Config Panel
		 */
		$basic = 'basic-config';
		$agilitySettings->registerPanel( $basic, __( 'Basic Configuration'  , 'agility' ) );
		
		$agilitySettings->addHidden( $basic , 'current-panel-id' , $basic );

		$agilitySettings->addSubHeader( $basic,
					'basic-favicon',
					__( 'Favicons' , 'agility' )
					);

		$agilitySettings->addImage( $basic , 
					'favicon-image',
					__( 'Favicon (16&times;16)' , 'agility' ),
					__( 'After uploading, click "Insert into Post" to set your image.' , 'agility' )
					);

		$agilitySettings->addImage( $basic , 
					'apple-touch-57',
					__( 'Apple Touch Icon (57&times;57)' , 'agility' ),
					__( 'For Apple iPhone and iPod Touch.  After uploading, click "Insert into Post" to set your image.' , 'agility' )
					);

		$agilitySettings->addImage( $basic , 
					'apple-touch-72',
					__( 'Apple Touch Icon (72&times;72)' , 'agility' ),
					__( 'For Apple iPad. After uploading, click "Insert into Post" to set your image.' , 'agility' )
					);

		$agilitySettings->addImage( $basic , 
					'apple-touch-114',
					__( 'Apple Touch Icon (114&times;114)' , 'agility' ),
					__( 'For Apple iPad (High Resolution). After uploading, click "Insert into Post" to set your image.' , 'agility' )
					);

		$agilitySettings->addSubHeader( $basic,
					'basic-meta',
					__( 'Meta Settings' , 'agility' )
					);

		$agilitySettings->addTextInput( $basic, 
					'viewport-maximum-scale',
					__( 'Maximum Viewport Scale' , 'agility' ),
					__( 'Set the maxiumum viewport scale.  Default is 1, indicating that users can not pinch and zoom on an iPad.  Setting it to 2 '.
						'will allow zooming to 200%, but will affect display on rotation' , 'agility' ),
					'1'
					);

		$agilitySettings->addSubHeader( $basic,
					'basic-logo',
					__( 'Logo' , 'agility' )
					);

		$agilitySettings->addRadio( $basic ,
					'logo-type',
					__( 'Logo Type' , 'agility' ),
					__( 'The logo text will be your site title' , 'agility' ),
					array(
						'text'			=>	__( 'Text (Site Title)' , 'agility' ),
						'image'			=>	__( 'Image (Upload below)' , 'agility' ),
						'image_text'	=>	__( 'Image + Text' , 'agility' ),
						'text_image'	=>	__( 'Text + Image' , 'agility' ),
					),
					'text'
					);

		$agilitySettings->addImage( $basic , 
					'logo-image', 
					__( 'Logo' , 'agility' ),
					__( '(Optional).  After uploading, click "Insert into Post" to set your image.' , 'agility' )
					);

		$agilitySettings->addSubHeader( $basic,
					'basic-skin',
					__( 'Skin' , 'agility' )
					);
		
		$agilitySettings->addRadio( $basic, 
					'background-preset',
					__( 'Background' , 'agility' ),
					'',
					array(
						'bkg-smoky'	=>	__( 'Smoky Noise' , 'agility' ),
						'bkg-fiber'	=>	__( 'Paper Fiber' , 'agility' ),
						'bkg-white'	=>	__( 'White' , 'agility' ),
					),
					'bkg-fiber'
					);

		$agilitySettings->addRadio( $basic, 
					'highlight-color',
					__( 'Highlight Color' , 'agility' ),
					'',
					array(
						'skin-red'		=>	__( 'Red' , 'agility' ),
						'skin-blue'		=>	__( 'Blue' , 'agility' ),
						'skin-green'	=>	__( 'Green' , 'agility' ),
						'skin-purple'	=>	__( 'Purple' , 'agility' ),
						'skin-charcoal'	=>	__( 'Charcoal' , 'agility' ),
					),
					'skin-red'
					);

		$agilitySettings->addCheckbox( $basic,
					'box-layout',
					__( 'Box Layout' , 'agility' ),
					__( 'Display a narrow inner wrapper ("box") on a dark background when > 960px ' , 'agility' ),
					'off'
					);


		$agilitySettings->addSubHeader( $basic,
					'basic-header',
					__( 'Header' , 'agility' )
					);

		$agilitySettings->addCheckbox( $basic,
					'enable-drop-container',
					__( 'Enable Drop Container' , 'agility' ),
					__( 'Disable this to remove the dropdown container at the top edge of the site.' , 'agility' ),
					'on'
					);

		$agilitySettings->addCheckbox( $basic,
					'enable-social-media',
					__( 'Show Social Media Icons' , 'agility' ),
					'',
					'on'
					);

		$agilitySettings->addSubHeader( $basic,
					'basic-widgets',
					__( 'Widgets' , 'agility' )
					);

		$agilitySettings->addTextInput( $basic, 
					'custom-sidebars',
					__( 'Custom Sidebars/Widget Area' , 'agility' ),
					__( 'Set the number of custom sidebars to generate' , 'agility' ),
					'0'
					);

		$agilitySettings->addSubHeader( $basic,
					'basic-footer',
					__( 'Footer' , 'agility' )
					);

		$agilitySettings->addTextArea( $basic, 
					'footer-left',
					__( 'Footer Text: Left' , 'agility' ),
					__( 'Text to include on the left side of the footer' , 'agility' ),
					'Agility WordPress Theme &copy; <a href="http://sevenspark.com">SevenSpark</a>'
					);

		$agilitySettings->addTextArea( $basic, 
					'footer-right',
					__( 'Footer Text: Right' , 'agility' ),
					__( 'Text to include on the right side of the footer' , 'agility' ),
					'Proudly powered by <a href="http://wordpress.org">WordPress</a>'
					);









		/** HOME **/

		$home = 'home-config';
		$agilitySettings->registerPanel( $home, __( 'Home Page'  , 'agility' ) );

		$agilitySettings->addInfobox( $home,
			'home-notice',
			'',
			__( 'The Home Settings apply to pages using any "Home" Template' , 'agility' )
		);

		$agilitySettings->addSubHeader( $home,
			'home-header-slider',
			__( 'Slider' , 'agility' )
			);

		$agilitySettings->addSelect( $home,
			'home-slider',
			__( 'Slider' , 'agility' ),
			__( 'Select the slider to use on the Home page.  <a href="'.admin_url("admin.php?page=sliderconstructor").
				'" target="_blank">Create a new slider</a>' , 'agility' ), 
			'agility_slider_ops'
		);

		$agilitySettings->addCheckbox( $home,
			'home-slider-crop',
			__( 'Crop Slides' , 'agility' ),
			__( 'You can adjust the crop aspect ratio in the Images &amp; Videos tab' , 'agility' ),
			'off'
		);

		$agilitySettings->addSubHeader( $home,
			'home-header-tagline',
			__( 'Tagline' , 'agility' )
			);

		$agilitySettings->addTextarea( $home,
			'home-tagline',
			__( 'Tagline' , 'agility' ), 
			__( 'This is the home page tagline.  You can delete it if you would rather not have one' , 'agility' )
		);

		$agilitySettings->addCheckbox( $home,
			'home-tagline-fleuron',
			__( 'Show Tagline Fleuron' , 'agility' ),
			'',
			'on'
		);

		$agilitySettings->addSubHeader( $home,
			'home-header-feature',
			__( 'Featured Items' , 'agility' )
			);

		$agilitySettings->addCheckbox( $home,
			'home-show-featured-items',
			__( 'Show Featured Items' , 'agility' ),
			'',
			'on'
		);

		//$portfolioOps = agility_portfolio_ops();
		$agilitySettings->addSelect( $home,
			'home-featured-items',
			__( 'Featured Items Portfolio' , 'agility' ),
			__( 'Select the portfolio to use on the Home page.  <a href="'.admin_url("admin.php?page=portfolioconstructor").'" target="_blank">Create a new portfolio</a>' , 'agility' ), 
			'agility_portfolio_ops'
		);

		$agilitySettings->addCheckbox( $home,
			'home-featured-items-crop',
			__( 'Crop Featured Items' , 'agility' ),
			__( 'You can adjust the crop aspect ratio in the Images &amp; Videos tab' , 'agility' ),
			'off'
		);

		$agilitySettings->addSubHeader( $home,
			'home-header-widgets',
			__( 'Widgets' , 'agility' )
			);

		$agilitySettings->addCheckbox( $home,
			'home-display-widgets',
			__( 'Display lower area widgets' , 'agility' ),
			'',
			'on'
		);

		$agilitySettings->addSubHeader( $home,
			'home-header-twitter',
			__( 'Twitter' , 'agility' )
			);

		$agilitySettings->addTextInput( $home, 
			'home-twitter-title',
			__( 'Twitter Title' , 'agility' ),
			__( 'The title to be displayed above the latest tweet' , 'agility' ),
			'From the Twittersphere'
			);

		$agilitySettings->addCheckbox( $home,
			'home-display-twitter',
			__( 'Display Latest Tweet' , 'agility' ),
			__( 'You can set the Twitter username in the Social Media tab' , 'agility' ),
			'on'
		);

		





		/** BLOG **/
		$blog = 'blog-config';
		$agilitySettings->registerPanel( $blog, __( 'Blog'  , 'agility' ) );

		$agilitySettings->addSubHeader( $blog,
			'blog-loop',
			__( 'Blog Loop' , 'agility' )
			);

		$agilitySettings->addCheckbox( $blog,
			'blog-display-title',
			__( 'Display Page Title' , 'agility' ),
			__( 'For example "The Blog"' , 'agility' ),
			'on'
		);

		$agilitySettings->addTextInput( $blog,
			'blog-title',
			__( 'Title' , 'agility' ), 
			__( 'Title to be displayed above the Loop' , 'agility' ),
			'The Blog'
		);

		$agilitySettings->addRadio( $blog, 
			'blog-layout',
			__( 'Blog Layout' , 'agility' ),
			__( 'Display your posts either in a standard layout with a right sidebar, or in a full-width 3-column grid layout' , 'agility' ),
			array(
				'standard'	=>	__( 'Standard' , 'agility' ),
				'grid'		=>	__( 'Grid' , 'agility' )
			),
			'standard'
			);

		$agilitySettings->addRadio( $blog, 
			'blog-excerpt',
			__( 'Display posts content as' , 'agility' ),
			__( 'Relevant only for standard blog layout.  Full Text will respect the <code>&lt;!--more--&gt;</code> (more link) while Excerpt will print the standard excerpt' , 'agility' ),
			array(
				'excerpt'	=>	__( 'Excerpt' , 'agility' ),
				'full'	=>	__( 'Full Text' , 'agility' )
			),
			'excerpt'
			);

		$agilitySettings->addCheckbox( $blog,
			'blog-pagination',
			__( 'Blog Loop Pagination' , 'agility' ),
			__( 'Show blog loop pagination (instead of "&larr; Older Posts" , "Newer Posts &rarr;"' , 'agility' ),
			'on'
		);


		$agilitySettings->addSubHeader( $blog,
			'blog-single',
			__( 'Single Post' , 'agility' )
			);

		$agilitySettings->addCheckbox( $blog,
			'blog-display-post-nav-above',
			__( 'Display Post Navigation Above Single Post' , 'agility' ),
			'',
			'off'
		);

		





		/* Images and Cropping */
		$images = 'images-config';
		$agilitySettings->registerPanel( $images, __( 'Images &amp; Videos' , 'agility' ) );	

		$agilitySettings->addSubHeader( $images,
			'image-section',
			__( 'Images' , 'agility' )
			);

		$agilitySettings->addCheckbox( $images,
			'enable_lightbox',
			__( 'Enable Lightbox - List' , 'agility' ),
			__( 'Disable this to disable lighboxes the blog loop, portfolios, post lists, etc - instead, images will link to the post/portfolio item' , 'agility' ),
			'on'
		);

		$agilitySettings->addCheckbox( $images,
			'enable_lightbox_single',
			__( 'Enable Lightbox - Single' , 'agility' ),
			__( 'Disable this to disable lighboxes on all single Blog Posts and Portfolio Items' , 'agility' ),
			'on'
		);

		$agilitySettings->addTextInput( $images,
			'prettyPhoto_default_width',
			__( 'Default lightbox width' , 'agility' ),
			__( 'PrettyPhoto will auto-size images, but this will define the width of the lightbox for videos.' , 'agility' ),
			'940'
		);
		

		$agilitySettings->addInfobox( $images,
			'images-notice',
			__( 'Cropping is optional' , 'agility' ),
			__( 'Set your preferred crop ratio here.  When you upload images, 2 cropped sizes will be created (940 and 640 pixel widths).  '.
			'In various areas (sliders, featured images, galleries, etc), you will be able to select whether to crop your images or not.'.
			'You should be sure to upload images at least 640px (preferably 940px) wide, in order to ensure cropping occurs consistently.' , 'agility' )
		);


		$agilitySettings->addInfobox( $images,
			'images-alert',
			__( 'Important' , 'agility' ),
			__( 'Images are sized and cropped upon upload (via the core WordPress functionality), rather than on the fly.  This saves server resources. '.
				'However, if you ever change the ratio, you\'ll need to recreate the images using the '.
				'<a target="_blank" href="http://wordpress.org/extend/plugins/regenerate-thumbnails/">Regenerate Thumbnails plugin</a>' , 'agility' ),
			'spark-infobox-warning'
		);

		$agilitySettings->addTextInput( $images,
			'crop-ratio-w',
			__( 'Crop Ratio: Width' , 'agility' ),
			__( 'The default ratio of 3:1 would produce crops of dimensions 940&times;313 and 640&times;213.' , 'agility' ),
			'3'
		);

		$agilitySettings->addTextInput( $images,
			'crop-ratio-h',
			__( 'Crop Ratio: Height' , 'agility' ), 
			'',
			'1'
		);


		$agilitySettings->addCheckbox( $images,
			'crop-feature',
			__( 'Crop feature images' , 'agility' ),
			__( 'This is the default.  You can override it on each post.  Changing this will not affect old posts.' , 'agility' ),
			'off'
		);

		$agilitySettings->addSubHeader( $images,
			'video-section',
			__( 'Video' , 'agility' )
			);

		$agilitySettings->addCheckbox( $images,
			'self-hosted-video',
			__( 'Allow Self-Hosted Video' , 'agility' ),
			__( 'Enable self-hosted video support for post featured media.' , 'agility' ),
			'off'
		);


		/* Social Media */
		$social = 'social-media-config';
		$agilitySettings->registerPanel( $social, __( 'Social Media' , 'agility' ) );	
		$agilitySettings->addTextInput( $social,
			'twitter',
			'Twitter', 
			__( 'Twitter username (no @).  Used for Twitter feed as well as social media icon' , 'agility' )
		);

		$agilitySettings->addTextInput( $social,
			'facebook',
			'Facebook', 
			__( 'Facebook Page URL or Custom URL' , 'agility' )
		);

		$agilitySettings->addTextInput( $social,
			'forrst',
			'Forrst', 
			__( 'Forrst username' , 'agility' )
		);

		$agilitySettings->addTextInput( $social,
			'dribbble',
			'Dribbble', 
			__( 'Dribbble username' , 'agility' )
		);

		$agilitySettings->addTextInput( $social,
			'vimeo',
			'Vimeo', 
			__( 'Vimeo custom URL' , 'agility' )
		);

		$agilitySettings->addTextInput( $social,
			'youtube',
			'YouTube', 
			__( 'YouTube username' , 'agility' )	//http://www.youtube.com/user/%
		);

		$agilitySettings->addTextInput( $social,
			'linkedin',
			'LinkedIn', 
			__( 'LinkedIn username' , 'agility' )	//http://www.linkedin.com/in/%
		);
		$agilitySettings->addTextInput( $social,
			'pinterest',
			'Pinterest', 
			__( 'Pinterest username' , 'agility' )	//http://www.pinterest.com/%
		);
		$agilitySettings->addTextInput( $social,
			'flickr',
			'Flickr', 
			__( 'Flickr username or URL' , 'agility' )	//http://www.flickr.com/photos/%
		);
		$agilitySettings->addTextInput( $social,
			'tumblr',
			'Tumblr', 
			__( 'Tumblr URL or subdomain' , 'agility' )	//http://%.tumblr.com
		);




		/* SLIDER SETTINGS */
		$slider = 'slider-config';
		$agilitySettings->registerPanel( $slider, __( 'Slider Configuration' , 'agility' ) );

		$agilitySettings->addTextInput( $slider,
			'slider-speed',
			__( 'Slider Speed', 'agility' ), 
			__( 'Slider speed, in milliseconds (7000 = 7 seconds)' , 'agility' ),	//http://%.tumblr.com
			'7000'
		);
		$agilitySettings->addTextInput( $slider,
			'slider-animation-speed',
			__( 'Animation Speed', 'agility' ), 
			__( 'Slide animation speed, in milliseconds (1000 = 1 second)' , 'agility' ),	//http://%.tumblr.com
			'600'
		);
		$agilitySettings->addSelect( $slider,
			'slider-animation',
			__( 'Animation' , 'agility' ),
			__( 'Choose your slider animation' , 'agility' ), 
			array(
				'slide'	=>	'Slide',
				'fade'=>	'Fade',
			),
			'slide'
		);
		$agilitySettings->addCheckbox( $slider,
			'slider-autoplay',
			__( 'Autoplay' , 'agility' ),
			__( 'Automatically play the slideshow.' , 'agility' ),
			'on'
		);

		



		$misc = 'misc-config';
		$agilitySettings->registerPanel( $misc, __( 'Miscellaneous' , 'agility' ) );

		$agilitySettings->addSelect( $misc,
			'back-to-top',
			__( 'Back to Top' , 'agility' ),
			__( 'How to handle the "Back to Top" button' , 'agility' ), 
			array(
				'fancy'	=>	'Fancy',
				'simple'=>	'Simple',
				'off'	=>	'Off',
			),
			'fancy'
		);

		$agilitySettings->addTextArea( $misc,
			'search-header-noresults',
			__( 'No Search Results Header' , 'agility' ),
			__( 'Use %s to include the search term.' , 'agility' ),
			'Sorry, we\'re all out of %s'

			);

		$agilitySettings->addTextArea( $misc,
			'search-message-noresults',
			__( 'No Search Results Message' , 'agility' ),
			'',
			'Nothing matched your search terms - better luck next time! Please try again with some different keywords.'

			);

		$agilitySettings->addTextInput( $misc,
			'shortcode_namespace',
			__( 'Shortcode Namespace' , 'agility' ), 
			__( 'Agility will automatically register the agility-{shortcode} version of each shortcode, as well as one with the above namespace.  If you would like to be able to use the shortcodes via [map] rather than [agility-map], you can leave the above blank.  If you have shortcode name collisions with a plugin, you can define your namespace however you like.  For example, if you set this value to <strong>my-</strong>, you can then call the shortcodes as [my-map] or [agility-map], but not [map]' , 'agility' )
		);


		$agilitySettings->addTextInput( $misc,
			'custom_portfolio_item_slug',
			__( 'Custom Portfolio Item Slug' , 'agility' ), 
			__( 'WordPress custom post types, like Portfolio Items, require a slug in their single item URL.  By default, this is "portfolio-item", but you can change that here.', 'agility' ).
				'<span class="spark-infobox spark-infobox-warning"><strong>'.__( 'Be sure that this slug does not conflict with any of your page slugs!  If you have a page called "portfolio", setting this slug to "portfolio" will cause problems.', 'agility' ).'</strong></span>'.
				'<span class="spark-infobox spark-infobox-warning">'.__( 'If you change this setting, be sure to resave your permalinks to flush the rewrite rules.  Your old URLs will no longer be valid.', 'agility' ).'</span>',
			'portfolio-item'
				

		);

		/* CUSTOMIZATIONS */

		$cust = 'customizations-config';
		$agilitySettings->registerPanel( $cust, 'Customize' );
		
		$agilitySettings->addInfobox( $cust,
			'cust-notice',
			__( 'ALWAYS CUSTOMIZE USING CHILD THEMES' , 'agility' ),
			__( 'Be sure to NEVER edit the core Agility theme files.  To customize your theme, use the Agility starter child theme, and edit '.
				'styles through the child theme\'s style.css, or override files in the child theme directory.  For more information on the '.
				'proper usage of Child Themes, see: <a href="http://codex.wordpress.org/Child_Themes#Template_files" target="_blank">Customizing your site '.
				'Using Child Themes</a>' , 'agility' ),
			'spark-infobox-warning'
		);

		$agilitySettings->addTextArea( $cust,
			'custom_css',
			__( 'Custom CSS Tweaks' , 'agility' ), 
			__( 'For minor CSS tweaks, you can add them here.  They will be included in the &lt;head&gt; of your site.  For major style adjustments, '.
				'you should use a child theme' , 'agility' )
		);	



		/* RECOMMENDED PLUGINS */

		$plugins = 'plugins-config';
		$agilitySettings->registerPanel( $plugins, __( 'Recommended Plugins' , 'agility' ) );

		$agilitySettings->addPlugin( $plugins, 
			'contact-form-7', 
			'Contact Form 7', 
			'',
			__( 'Used for the contact form on the contact page' , 'agility' ) );

		$agilitySettings->addPlugin( $plugins, 
			'yet-another-related-posts-plugin', 
			'YARPP - Yet another related posts plugin', 
			'',
			__( 'Used for the Related Posts widget below the post content' , 'agility' ) );
		
		$agilitySettings->addPlugin( $plugins, 
			'regenerate-thumbnails', 
			'Regenerate Thumbnails', 
			'',
			__( 'Used to regenerate your image thumbnails if you\'ve changed/added sizes' , 'agility' ) );
		
		$agilitySettings->addPlugin( $plugins, 
			'wordpress-seo', 
			'WordPress SEO', 
			'',
			__( 'The best SEO solution for WordPress' , 'agility' ) );

		$agilitySettings->addPlugin( $plugins, 
			'google-analytics-for-wordpress', 
			'Google Analytics for WordPress', 
			'',
			__( 'Add Google Analytics to your site' , 'agility' ) );

		$agilitySettings->addPlugin( $plugins, 
			'responsive-select-menu', 
			'Responsive Select Menu', 
			'',
			__( 'Turn your menu into a select box on mobile devices if you choose.' , 'agility' ) );

		$agilitySettings->addPlugin( $plugins, 
			'wordpress-importer', 
			'WordPress Importer', 
			'',
			__( 'Import sample data.' , 'agility' ) );


		if( isset( $_GET['updates'] ) ){

			/* UPDATES */
			$updates = 'updates';
			$agilitySettings->registerPanel( $updates, 'Updates' );	
			$agilitySettings->addCustomField( $updates,
					'agility-updates',
					'agility_update_notifier'
					);
		}

}

/**
 * Retrieves saved sliders in proper options format
 */
function agility_slider_ops(){
	$sliderOps = QueryConstructor::getSavedQueries( 'Slider' );
	$ops = array( '0' => __( 'Default - All Slides' , 'agility' ) );
	foreach( $sliderOps as $slider ){
		$ops[$slider->ID] = $slider->post_title;
	}
	return $ops;
}

/**
 * Retrieves saved portfolios in proper options format
 */
function agility_portfolio_ops(){
	$pOps = QueryConstructor::getSavedQueries( 'Portfolio' );
	$ops = array( '0' => __( 'Default - All Portfolio Items' , 'agility' ) );
	foreach( $pOps as $folio ){
		$ops[$folio->ID] = $folio->post_title;
	}
	return $ops;
}