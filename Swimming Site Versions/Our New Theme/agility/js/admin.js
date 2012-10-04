/**
 * Agility administrative javascript for Posts and Pages
 */

jQuery( document ).ready( function( $ ){

	/*
	 *	POSTS
	 */

	//When the Feature Type is not "Self Hosted Video", that meta box should be hidden
	function updateVideo(){
		var ftype = $( '#feature_type' ).val();

		//Self-Hosted Video
		var $metabox_switch = $( '#shvideo_settings-hide' );
		if( ftype == 'video-self'){
			$metabox_switch.attr( 'checked', true );
			$( '#shvideo_settings' ).slideDown();
		}
		else{
			$metabox_switch.attr( 'checked', false );
			$( '#shvideo_settings' ).slideUp();
		}

		//Embedded Video
		var $featured_video_wrap = $( '#metabox-field-wrap-featured_video' );
		if( ftype == 'video-embed' ){
			$featured_video_wrap.slideDown();
		}
		else{
			$featured_video_wrap.slideUp();
		}

	}
	$( '#feature_type' ).change( function(){
		updateVideo();
	});
	updateVideo();


	/*
	 *	PAGES
	 */
	function updatePageTemplate(){

		var template = $( '#page_template' ).val();

		//Portfolio
		var $portfolio_settings = $( '#portfolio_settings' );
		if( template == 'page-portfolio.php' ){
			$portfolio_settings.slideDown();
		}
		else{
			$portfolio_settings.slideUp();
		}


		//BrickLayer
		var $bricklayer_settings = $( '#bricklayer_settings' );
		if( template == 'bricklayout.php' ){
			$bricklayer_settings.slideDown();
		}
		else{
			$bricklayer_settings.slideUp();
		}

	}
	$( '#page_template' ).change( function(){
		updatePageTemplate();
	});
	updatePageTemplate();


	window.shortcodeMasterUI = function( button_id ){
		var $drop = $( '#shortcodeMasterDrop' );
		var $panel = $( '#shortcodeMasterPanel' );

		//Create dropdown if not already created
		if( !$drop.length ){

			var $button = $( button_id ); // $( '#content_shortcodeMaster' );

			//Create Panel
			$panel = $('<div id="shortcodeMasterPanel">');
			$innerPanel = $( '<div id="shortcodeMasterPanel_inner">' );
			$panel.append( $innerPanel );
			$panel.hide();
			$( 'body' ).append( $panel );

			$panel.click( function(){
				$panel.fadeOut( 'fast' );
			});
			$innerPanel.click( function(e){
				e.stopPropagation();
			});

			//Create Drop
			$drop = $('<div id="shortcodeMasterDrop">');
			$drop.html(
				'<ul><li><a href="#">Loading shortcodes...</a></li>'
			);
			//Load shortcodes
			$.post( ajaxurl, 
				{ action: 'shortcodeMaster_loadShortcodes' }, 
				function(d){
					$drop.html( d );

					$drop.find( 'a' ).click( function(e){
						e.preventDefault();
						$drop.slideUp( 'fast' );

						var sid = $(this).attr('href');
						if( sid == $drop.data( 'loaded_shortcode' ) ){
							$panel.fadeIn( 'fast' );
							return;
						}

						$drop.data( 'loaded_shortcode' , sid );

						$innerPanel.html( '<span class="panel-loading">Loading shortcode parameters...</span>' );
						$panel.fadeIn( 'fast' );


						//retrieve and load
						$.post( ajaxurl, 
							{ action: 'shortcodeMaster_loadShortcodeForm', shortcode: sid }, 
							function(d){
								$innerPanel.html( d );

								$innerPanel.find( 'label' ).tipsy( { title : 'data-tooltip', gravity: 'e' } );

								$innerPanel.find( ':input').change(function(e){
									var shortcode = shortcodeMaster_buildShortcode();
									$innerPanel.find( '.shortcode-preview' ).html( shortcode );
								});

								$innerPanel.find( '.insert-shortcode').click(function(e){
									e.preventDefault();

									var shortcode = shortcodeMaster_buildShortcode();

									if( $button.data( 'editor_type' ) == 'tinymce' ){
										var ed = $button.data( 'ed' );
										if( $.browser.msie && $.browser.version.substring(0, 2) == "8." ){
											alert( 'You seem to be using IE8.  To insert a shortcode, you can either copy it into the editor, use the HTML Editor instead, or upgrade to a modern browser like Chrome, Firefox, or IE9');
										}
										else ed.selection.setContent(shortcode);
									}
									else{
										insertAtCaret('content' , shortcode);
									}
									$panel.fadeOut( 'fast' );

								});

							});
						

					});
				});
			//Position Drop
			$drop.css( {
				position:   'absolute',
				top:	  $button.offset().top + $button.height() + 10,
				left:	 $button.offset().left
			});
			$drop.hide();

			$( 'body' ).append( $drop );
			
		}

		$drop.slideToggle( 'fast' );


		return;
	};

	window.shortcodeMaster_buildShortcode = function(){
		var $innerPanel = $( '#shortcodeMasterPanel_inner' );
		var shortcode_tag = $innerPanel.find( '.shortcode-tag' ).val();
		var shortcode = '['+ shortcode_tag;

		$innerPanel.find( '.shortcodeMaster_form_setting-attribute :input' ).each( function( k, el ){
			var val;
			if( $(this).is( ':checkbox' ) ){
				val = $(this).is(':checked') ? 'on' : 'off';
			}
			else{
				val = $(this).val();
			}
			if( val != $(this).attr('data-default') ){
				shortcode+= ' ' + $(this).attr('name') + '="' + val+'"';
			}
		});

		shortcode+= ']';

		var $content = $innerPanel.find( '.shortcodeMaster_form_setting textarea[name="content"]' );
		if( $content.length ){
			shortcode+= $content.val();
			shortcode+= '[/'+shortcode_tag+']';
		}
		return shortcode;
	};

	function insertAtCaret( areaId, text ) {
		var txtarea = document.getElementById(areaId);
		var scrollPos = txtarea.scrollTop;
		var strPos = 0;
		var br = ((txtarea.selectionStart || txtarea.selectionStart == '0') ? 
			"ff" : (document.selection ? "ie" : false ) );
		if (br == "ie") { 
			txtarea.focus();
			var range = document.selection.createRange();
			range.moveStart ('character', -txtarea.value.length);
			strPos = range.text.length;
		}
		else if (br == "ff") strPos = txtarea.selectionStart;
		
		var front = (txtarea.value).substring(0,strPos);  
		var back = (txtarea.value).substring(strPos,txtarea.value.length); 
		txtarea.value=front+text+back;
		strPos = strPos + text.length;
		if (br == "ie") { 
			txtarea.focus();
			var range = document.selection.createRange();
			range.moveStart ('character', -txtarea.value.length);
			range.moveStart ('character', strPos);
			range.moveEnd ('character', 0);
			range.select();
		}
		else if (br == "ff") {
			txtarea.selectionStart = strPos;
			txtarea.selectionEnd = strPos;
			txtarea.focus();
		}
		txtarea.scrollTop = scrollPos;
	}


	QTags.addButton( 'shortcodeMaster', 'shortcodes', function(){ 
		shortcodeMasterUI( '#qt_content_shortcodeMaster' ); 
		$( '#content_shortcodeMaster' ).data( { 'editor_type' : 'html' } );
	} );

});


//edButtons[edButtons.length] = new edButton( 'shortcodeMaster', 'shortcodes', '', '', '' );



// tipsy, facebook style tooltips for jquery
// version 1.0.0a
// (c) 2008-2010 jason frame [jason@onehackoranother.com]
// released under the MIT license

(function($) {
    
    function maybeCall(thing, ctx) {
        return (typeof thing == 'function') ? (thing.call(ctx)) : thing;
    };
    
    function Tipsy(element, options) {
        this.$element = $(element);
        this.options = options;
        this.enabled = true;
        this.fixTitle();
    };
    
    Tipsy.prototype = {
        show: function() {
            var title = this.getTitle();
            if (title && this.enabled) {
                var $tip = this.tip();
                
                $tip.find('.tipsy-inner')[this.options.html ? 'html' : 'text'](title);
                $tip[0].className = 'tipsy'; // reset classname in case of dynamic gravity
                $tip.remove().css({top: 0, left: 0, visibility: 'hidden', display: 'block'}).prependTo(document.body);
                
                var pos = $.extend({}, this.$element.offset(), {
                    width: this.$element[0].offsetWidth,
                    height: this.$element[0].offsetHeight
                });
                
                var actualWidth = $tip[0].offsetWidth,
                    actualHeight = $tip[0].offsetHeight,
                    gravity = maybeCall(this.options.gravity, this.$element[0]);
                
                var tp;
                switch (gravity.charAt(0)) {
                    case 'n':
                        tp = {top: pos.top + pos.height + this.options.offset, left: pos.left + pos.width / 2 - actualWidth / 2};
                        break;
                    case 's':
                        tp = {top: pos.top - actualHeight - this.options.offset, left: pos.left + pos.width / 2 - actualWidth / 2};
                        break;
                    case 'e':
                        tp = {top: pos.top + pos.height / 2 - actualHeight / 2, left: pos.left - actualWidth - this.options.offset};
                        break;
                    case 'w':
                        tp = {top: pos.top + pos.height / 2 - actualHeight / 2, left: pos.left + pos.width + this.options.offset};
                        break;
                }
                
                if (gravity.length == 2) {
                    if (gravity.charAt(1) == 'w') {
                        tp.left = pos.left + pos.width / 2 - 15;
                    } else {
                        tp.left = pos.left + pos.width / 2 - actualWidth + 15;
                    }
                }
                
                $tip.css(tp).addClass('tipsy-' + gravity);
                $tip.find('.tipsy-arrow')[0].className = 'tipsy-arrow tipsy-arrow-' + gravity.charAt(0);
                if (this.options.className) {
                    $tip.addClass(maybeCall(this.options.className, this.$element[0]));
                }
                
                if (this.options.fade) {
                    $tip.stop().css({opacity: 0, display: 'block', visibility: 'visible'}).animate({opacity: this.options.opacity});
                } else {
                    $tip.css({visibility: 'visible', opacity: this.options.opacity});
                }
            }
        },
        
        hide: function() {
            if (this.options.fade) {
                this.tip().stop().fadeOut(function() { $(this).remove(); });
            } else {
                this.tip().remove();
            }
        },
        
        fixTitle: function() {
            var $e = this.$element;
            if ($e.attr('title') || typeof($e.attr('original-title')) != 'string') {
                $e.attr('original-title', $e.attr('title') || '').removeAttr('title');
            }
        },
        
        getTitle: function() {
            var title, $e = this.$element, o = this.options;
            this.fixTitle();
            var title, o = this.options;
            if (typeof o.title == 'string') {
                title = $e.attr(o.title == 'title' ? 'original-title' : o.title);
            } else if (typeof o.title == 'function') {
                title = o.title.call($e[0]);
            }
            title = ('' + title).replace(/(^\s*|\s*$)/, "");
            return title || o.fallback;
        },
        
        tip: function() {
            if (!this.$tip) {
                this.$tip = $('<div class="tipsy"></div>').html('<div class="tipsy-arrow"></div><div class="tipsy-inner"></div>');
            }
            return this.$tip;
        },
        
        validate: function() {
            if (!this.$element[0].parentNode) {
                this.hide();
                this.$element = null;
                this.options = null;
            }
        },
        
        enable: function() { this.enabled = true; },
        disable: function() { this.enabled = false; },
        toggleEnabled: function() { this.enabled = !this.enabled; }
    };
    
    $.fn.tipsy = function(options) {
        
        if (options === true) {
            return this.data('tipsy');
        } else if (typeof options == 'string') {
            var tipsy = this.data('tipsy');
            if (tipsy) tipsy[options]();
            return this;
        }
        
        options = $.extend({}, $.fn.tipsy.defaults, options);
        
        function get(ele) {
            var tipsy = $.data(ele, 'tipsy');
            if (!tipsy) {
                tipsy = new Tipsy(ele, $.fn.tipsy.elementOptions(ele, options));
                $.data(ele, 'tipsy', tipsy);
            }
            return tipsy;
        }
        
        function enter() {
            var tipsy = get(this);
            tipsy.hoverState = 'in';
            if (options.delayIn == 0) {
                tipsy.show();
            } else {
                tipsy.fixTitle();
                setTimeout(function() { if (tipsy.hoverState == 'in') tipsy.show(); }, options.delayIn);
            }
        };
        
        function leave() {
            var tipsy = get(this);
            tipsy.hoverState = 'out';
            if (options.delayOut == 0) {
                tipsy.hide();
            } else {
                setTimeout(function() { if (tipsy.hoverState == 'out') tipsy.hide(); }, options.delayOut);
            }
        };
        
        if (!options.live) this.each(function() { get(this); });
        
        if (options.trigger != 'manual') {
            var binder   = options.live ? 'live' : 'bind',
                eventIn  = options.trigger == 'hover' ? 'mouseenter' : 'focus',
                eventOut = options.trigger == 'hover' ? 'mouseleave' : 'blur';
            this[binder](eventIn, enter)[binder](eventOut, leave);
        }
        
        return this;
        
    };
    
    $.fn.tipsy.defaults = {
        className: null,
        delayIn: 0,
        delayOut: 0,
        fade: false,
        fallback: '',
        gravity: 'n',
        html: false,
        live: false,
        offset: 0,
        opacity: 0.8,
        title: 'title',
        trigger: 'hover'
    };
    
    // Overwrite this method to provide options on a per-element basis.
    // For example, you could store the gravity in a 'tipsy-gravity' attribute:
    // return $.extend({}, options, {gravity: $(ele).attr('tipsy-gravity') || 'n' });
    // (remember - do not modify 'options' in place!)
    $.fn.tipsy.elementOptions = function(ele, options) {
        return $.metadata ? $.extend({}, options, $(ele).metadata()) : options;
    };
    
    $.fn.tipsy.autoNS = function() {
        return $(this).offset().top > ($(document).scrollTop() + $(window).height() / 2) ? 's' : 'n';
    };
    
    $.fn.tipsy.autoWE = function() {
        return $(this).offset().left > ($(document).scrollLeft() + $(window).width() / 2) ? 'e' : 'w';
    };
    
    /**
     * yields a closure of the supplied parameters, producing a function that takes
     * no arguments and is suitable for use as an autogravity function like so:
     *
     * @param margin (int) - distance from the viewable region edge that an
     *        element should be before setting its tooltip's gravity to be away
     *        from that edge.
     * @param prefer (string, e.g. 'n', 'sw', 'w') - the direction to prefer
     *        if there are no viewable region edges effecting the tooltip's
     *        gravity. It will try to vary from this minimally, for example,
     *        if 'sw' is preferred and an element is near the right viewable 
     *        region edge, but not the top edge, it will set the gravity for
     *        that element's tooltip to be 'se', preserving the southern
     *        component.
     */
     $.fn.tipsy.autoBounds = function(margin, prefer) {
		return function() {
			var dir = {ns: prefer[0], ew: (prefer.length > 1 ? prefer[1] : false)},
			    boundTop = $(document).scrollTop() + margin,
			    boundLeft = $(document).scrollLeft() + margin,
			    $this = $(this);

			if ($this.offset().top < boundTop) dir.ns = 'n';
			if ($this.offset().left < boundLeft) dir.ew = 'w';
			if ($(window).width() + $(document).scrollLeft() - $this.offset().left < margin) dir.ew = 'e';
			if ($(window).height() + $(document).scrollTop() - $this.offset().top < margin) dir.ns = 's';

			return dir.ns + (dir.ew ? dir.ew : '');
		}
	};
    
})(jQuery);