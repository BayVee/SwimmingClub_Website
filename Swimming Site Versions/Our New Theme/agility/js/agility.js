/**
 * agility.js
 * 
 * The main javascript for Agility - Responsive WordPress theme by SevenSpark
 * 
 * Copyright 2012 Chris Mavricos, SevenSpark
 * http://sevenspark.com
 * 
 */

jQuery(document).ready(function($){

	/* Style for JS-enabled */
	$('body').addClass('js-enabled');	
	
	/* Keep track of the width for use in mobile browser displays */
	var currentWindowWidth = $(window).width();
	$(window).resize(function(){
		currentWindowWidth = $(window).width();
	});
	
	/* FLEX SLIDER */
	var $flexSlider = $('.flexslider');
	$flexSlider.flexslider({
		animation: "slide",
		controlsContainer: ".flex-container",
		prevText: "&larr;",
		nextText: "&rarr;",
		pausePlay:true,
		pauseOnHover:true,
		before:	function($slider){
			$slider.find('.flex-caption').fadeOut('fast');			
		},
		after: function($slider){
			$slider.find('.flex-caption').fadeIn();			
		},
		slideshowSpeed 		: agilitySetting( 'slideshowSpeed', 7000, true ),
		animationDuration 	: agilitySetting( 'animationSpeed', 600 , true ),
		animation 	   		: agilitySetting( 'animation', 'slide' , false ),
		slideshow 			: agilitySetting( 'autoplay', true , false )
	});
	$('.height-expand').each(function(){
		$(this).height($(this).prev().height());
	});
	
	/* DROP PANEL */
	$('#drop-panel-expando').click(function(e){
		e.preventDefault();
		$('.drop-panel').slideToggle();
	});
	
	/* PRETTY PHOTO */
	if( !jQuery.browser.mobile ){	//Don't use prettyPhoto on mobile device
		$("a[data-rel^='prettyPhoto']").prettyPhoto({
			social_tools: '',
			overlay_gallery: false,
			default_width: agilitySetting( 'prettyPhoto_default_width' , 940 , true ),
			changepicturecallback: function(){
				
				//Make sure lightbox doesn't run off the left of the screen
				var $pp = $('.pp_default');
				if( parseInt( $pp.css('left') ) < 0 ){
					$pp.css('left', 0 );
				}
			}
		});
	}
	else{
		//Mobile devices use alternative href
		$("a[data-rel^='prettyPhoto'][data-href-alt]").click( function(e){
			e.preventDefault();
			var href = $(this).attr( 'data-href-alt' );
			if( href ) window.location = href;
			return false;
		});
	}
	
	/* MOBILE MENU */
	$('.mobile-menu-button').click(function(e){
		e.preventDefault();
		var $menu = $($(this).attr('href'));
		$menu.toggleClass('menu-open'); //toggle()
		
		if(typeof $navClose !== 'undefined' && !$menu.hasClass('menu-open') ){
			$navClose.hide();
		}
	});
	
	/* Expander for featured images */
	$('.single-post-feature-expander').click(function(){
		$(this).parents('.featured-image').toggleClass('full-width');
	});
	
	//Size images in IE
	imgSizer.collate();
	
	
	//IPHONE, IPAD, IPOD
	var deviceAgent = navigator.userAgent.toLowerCase();
	var is_iOS = deviceAgent.match(/(iphone|ipod|ipad)/);
	
	if (is_iOS) {
		
		$('#main-nav').prepend('<a href="#" class="nav-close">&times;</a>'); // Close Submenu
		
		var $navClose = $('.nav-close');
		$navClose.hide().click(function(e){
			e.preventDefault();
			if(currentWindowWidth >= 767){
				$(this).hide();
			}
		});
		
		$('#main-nav > ul > li').hover( function(e){
			e.preventDefault();
			if( $(this).has( 'ul' ).size() == 0 ){
				$navClose.hide();
			}
			else if( currentWindowWidth < 767 ){
				$navClose.css({ 
					top : $(this).position().top + 33,
					left : '',
					right : 0
				}).show();
			}
			else{
				$navClose.css({
					left : $(this).position().left + parseInt($(this).css('marginLeft')),
					top : '',
					right : 'auto'
				}).show();
			}
		});
			  
	}
	
	
	//NON-IOS
	if(!is_iOS){
		//iOS doesn't like CSS3 transitioning preloader, so don't use it
		$('.preload').preloadImages({
			showSpeed: 200   // length of fade-in animation, should be .2 in CSS transition
		});	   
		$(':not(.flexslider) .video-container').addClass('video-flex');
	}
	
	
	//ANDROID
	var is_Android = deviceAgent.match(/(android)/);
	if(is_Android){
		//Do something special with Android
	}
	
		
	//IE automatic grid clears
	if($.browser.msie){
		$('.portfolio.col-4 article:nth-child(4n+1)').addClass('clear-grid');
		$('.portfolio.col-3 article:nth-child(3n+1)').addClass('clear-grid');
		$('.portfolio.col-2 article:nth-child(2n+1)').addClass('clear-grid');
	}
	
	
	//HTML5 Fallbacks
	if(!Modernizr.input.placeholder){
		$('.fallback').show();
	}
	
	//Google Maps
	if( typeof google != 'undefined' && 
		typeof google.maps != 'undefined' &&
		typeof google.maps.LatLng !== 'undefined' ){
		$('.map_canvas').each(function(){
			
			var $canvas = $(this);
			var dataZoom = $canvas.attr('data-zoom') ? parseInt($canvas.attr('data-zoom')) : 8;
			
			var latlng = $canvas.attr('data-lat') ? 
							new google.maps.LatLng($canvas.attr('data-lat'), $canvas.attr('data-lng')) :
							new google.maps.LatLng(40.7143528, -74.0059731);
					
			var myOptions = {
				zoom: dataZoom,
				mapTypeId: google.maps.MapTypeId.ROADMAP,
				center: latlng
			};
					
			var map = new google.maps.Map(this, myOptions);
			
			if($canvas.attr('data-address')){
				var geocoder = new google.maps.Geocoder();
				geocoder.geocode({ 
						'address' : $canvas.attr('data-address') 
					},
					function(results, status) {					
						if (status == google.maps.GeocoderStatus.OK) {
							map.setCenter(results[0].geometry.location);
							var marker = new google.maps.Marker({
								map: map,
								position: results[0].geometry.location,
								title: $canvas.attr('data-mapTitle')
							});
						}
				});
			}
		});
	}

	//Twitter
	if($('#tweet').size() > 0){
		var account = $( '#tweet' ).attr( 'data-account' );
		if( !account ){
			$( '#tweet' ).html( '<div class="hint">please set a Twitter handle in the Agility Social Media Options</div>');
		}	
		else{
			getTwitters('tweet', { 
				id: account, 
				count: 1, 
				enableLinks: true, 
				ignoreReplies: true, 
				clearContents: true,
				template: '%text% <a href="http://twitter.com/%user_screen_name%/statuses/%id_str%/" class="tweet-time" target="_blank">%time%</a>'+
							'<a href="http://twitter.com/%user_screen_name%" class="twitter-account" title="Follow %user_screen_name% on Twitter" target="_blank" ><img src="%user_profile_image_url%" /></a>'
			});
		}
	}

	//Toggles
	$('.toggle-closed .toggle-body').hide();
	$('.toggle-header a').click(function(e){
		e.preventDefault();
		$(this).parent('.toggle-header').siblings('.toggle-body').slideToggle().parents( '.toggle' ).toggleClass('toggle-open');
	});


	//Tabs
	$('body').on('click', 'ul.tabs > li > a', function(e) {

		//Get Location of tab's content
		var contentLocation = $(this).attr('href');
		if( contentLocation.indexOf( '#' ) > 0 ) contentLocation = contentLocation.substring( contentLocation.indexOf( '#' ) );

		//Let go if not a hashed one
		if(contentLocation.charAt(0)=="#") {

			e.preventDefault();

			//Make Tab Active
			$(this).parent().siblings().children('a').removeClass('active');
			$(this).addClass('active');

			//Show Tab Content & add active class
			$(contentLocation).show().addClass('active').siblings().hide().removeClass('active');

		}
	});



	//Video-JS

	if( typeof VideoJS !== 'undefined' ){

		$('.video-js').each( function(){
			$(this).data( 'aspect_ratio' , $(this).attr( 'width' ) / $(this).attr( 'height' ) );
			var new_height = $(this).width() / $(this).data('aspect_ratio');
			$(this).parent( '.featured-video' ).height( new_height );
			$(this).height( new_height );
		});

		$(window).resize(function(){
			$('.video-js').each(function(){
				resizeVideoJS( $(this) );
			});
		});
	}

	function resizeVideoJS( $videojs ){
		var new_height = $videojs.width() / $videojs.data( 'aspect_ratio' );
		$videojs.parent( '.featured_video' ).height( new_height );
		$videojs.height( new_height );
	}

	// Back to Top - based on Scroll To Top by Cudazi

	var upperLimit = 100;
	var scrollElem = $('a#back-to-top');
	var scrollSpeed = 500;
	
	// Show and hide the scroll to top link based on scroll position	
	scrollElem.hide();
	$( window ).scroll( function () { 			
		var scrollTop = $(document).scrollTop();
		if ( scrollTop > upperLimit ) {
			$( scrollElem ).stop().fadeTo( 300, .7 );
		}
		else{		
			$( scrollElem ).stop().fadeTo( 300, 0 , function(){
				setTimeout( function(){ scrollElem.hide(); } , 200 );
			});
		}
	});

	// Scroll to top animation on click
	$( scrollElem ).click(function(){ 
		$( 'html, body' ).animate( { scrollTop:0 }, scrollSpeed ); 
		return false; 
	});

	function agilitySetting( key , fallback , numeric ){
		if( typeof agilitySettings != 'undefined' ){
			if( key in agilitySettings ){
				if( numeric ) return parseInt( agilitySettings[key] );
				//if( bool ) return agilitySettings[key] == 'true' ? true : false;
				return agilitySettings[key];
			}
		}
		return fallback;
	}

	//jQuery( '.mosaic' ).isotope( { itemSelector: 'article', layoutMode: 'masonry' } );
	
});

(function($){ 
	jQuery( '.isotope-container img' ).bind( 'load', function(){
		agility_isotope();
	});
})(jQuery); 

jQuery( window ).ready( function( $ ){
	var deviceAgent = navigator.userAgent.toLowerCase();
	if( deviceAgent.match(/(iphone|ipod|ipad)/) ) {
		agility_isotope();
	}
});

function agility_isotope(){
	//Isotope
	var $isotopeContainer = jQuery( '.isotope-container' );
	$isotopeContainer.css( 'min-height' , $isotopeContainer.height() );
	// initialize isotope
	$isotopeContainer.isotope({
		layoutMode: 'fitRows'
	});

	// filter items when filter link is clicked
	jQuery('.isotope-filters a').click(function(e){
		e.preventDefault();
		var selector = jQuery(this).attr( 'data-filter' );
		$isotopeContainer.isotope( { filter: selector } );
		return false;
	});
}


//Image Preloader
jQuery.fn.preloadImages = function(options){

	var defaults = {
		showSpeed: 200
	};

	var options = jQuery.extend(defaults, options);

	return this.each(function(){
		var $container = jQuery(this);
		var $image = $container.find('img');

		$image.addClass('loading');	//hide image while loading
		 
		$image.bind('load error', function(){
			$image.removeClass('loading'); //allow image to display (will fade in with CSS3 trans)
			
			setTimeout(function(){ 
				$container.removeClass('preload'); //remove the preloading class to swap the bkg
			}, options.showSpeed);
			
		});
		
		if($image[0].complete || (jQuery.browser.msie )) { 
			$image.trigger('load');	//IE has glitchy load triggers, so trigger it automatically
		}
	});
}


/* IE Image Resizing - by Ethan Marcotte - http://unstoppablerobotninja.com/entry/fluid-images/ */
var imgSizer = {
	Config : {
		imgCache : []
		,spacer : "../images/spacer.gif"
	}

	,collate : function(aScope) {
		var isOldIE = (document.all && !window.opera && !window.XDomainRequest) ? 1 : 0;
		if (isOldIE && document.getElementsByTagName) {
			var c = imgSizer;
			var imgCache = c.Config.imgCache;

			var images = (aScope && aScope.length) ? aScope : document.getElementsByTagName("img");
			for (var i = 0; i < images.length; i++) {
				images[i].origWidth = images[i].offsetWidth;
				images[i].origHeight = images[i].offsetHeight;

				imgCache.push(images[i]);
				c.ieAlpha(images[i]);
				images[i].style.width = "100%";
			}

			if (imgCache.length) {
				c.resize(function() {
					for (var i = 0; i < imgCache.length; i++) {
						var ratio = (imgCache[i].offsetWidth / imgCache[i].origWidth);
						imgCache[i].style.height = (imgCache[i].origHeight * ratio) + "px";
					}
				});
			}
		}
	}

	,ieAlpha : function(img) {
		var c = imgSizer;
		if (img.oldSrc) {
			img.src = img.oldSrc;
		}
		var src = img.src;
		img.style.width = img.offsetWidth + "px";
		img.style.height = img.offsetHeight + "px";
		img.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + src + "', sizingMethod='scale')"
		img.oldSrc = src;
		img.src = c.Config.spacer;
	}

	// Ghettomodified version of Simon Willison's addLoadEvent() -- http://simonwillison.net/2004/May/26/addLoadEvent/
	,resize : function(func) {
		var oldonresize = window.onresize;
		if (typeof window.onresize != 'function') {
			window.onresize = func;
		} else {
			window.onresize = function() {
				if (oldonresize) {
					oldonresize();
				}
				func();
			}
		}
	}
};


/**
 * jQuery.browser.mobile (http://detectmobilebrowser.com/)
 *
 * jQuery.browser.mobile will be true if the browser is a mobile device
 *
 **/
(function(a){jQuery.browser.mobile=/android.+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|e\-|e\/|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|xda(\-|2|g)|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))})(navigator.userAgent||navigator.vendor||window.opera);