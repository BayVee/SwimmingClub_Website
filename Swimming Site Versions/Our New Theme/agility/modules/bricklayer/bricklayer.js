/**
 * BrickLayer User Interface admin javascript
 */

jQuery( document ).ready( function($){

	if( !$('body').hasClass( 'folded' )){
		$('body').addClass('folded');
	}

	$( '.tooltip' ).tipsy( { title : 'data-tooltip', gravity: 's' } );
	$( '.get-started' ).click( function(e){
		e.preventDefault();
		$( '#brickLayer_welcome' ).fadeOut();
	});
	$( '#brickhelp' ).click( function(e){
		e.preventDefault();
		$( '#brickLayer_welcome' ).fadeToggle();
	});

	$( ".sortable" ).sortable({
		revert: true,
		connectWith: ".connectedSortable",
		placeholder: "ui-state-highlight",
		cancel: ".brick-handle-arrow, .info, input, textarea, select, .brick-controls",
		handle: ".brick-handle",
		tolerance: "pointer",
		beforeStop: function(event, ui) {
			
			//When the brick is dropped, it may be placed in between another brick and its newrow divider.
			//This swaps the two
			var $brick = $( ui.item );
			if( $brick.prev('.brick-newrow').length > 0 ){
				$brick.prev('.brick-newrow').remove();
				$brick.after('<li class="brick-newrow">');
			}

			$brick.find( '.tooltip' ).tipsy( { title : 'data-tooltip', gravity: 's' } );

		}	
	});

	$( "#bricks .draggable" ).draggable({
		connectToSortable: ".sortable",
		helper: "clone",
		revert: "invalid",
		//handle: ".brick-handle"
		cancel: 'input, textarea, select'

	});

	$( ".brick-handle .brick-handle-arrow" ).live( 'click', function(e){
		e.preventDefault();
		e.stopPropagation();
		$(this).parent('.brick-handle').siblings('.brick-settings').slideToggle('fast');
		return false;
	});

	$( "#layout_selector_arrow" ).click(function(){
		$( "#layout_selector ul" ).slideToggle('fast');
	});

	$( ".toggle h3" ).click( function(){
		$(this).siblings('.toggle-content').slideToggle();
	});

	$( "#bricklayer_form" ).submit( function(e){

		e.preventDefault();
		
		//Check for title

		brick_index = 0;
		var layout_data = {
			feature_area : {},
			content_area : {},
			sidebar_area : {}
		};

		$('#feature_area, #content_area, #sidebar_area').each(function(){
			var id = $(this).attr('id');
			var area_index = 0;

			$(this).find('.brick').each(function(){

				layout_data[id][area_index] = brick_index;

				$(this).find( ':input' ).each(function(){
					$(this).attr( 'data-index' , brick_index );
					$(this).attr( 'name' , $(this).attr('data-name') + '['+brick_index+']' );
				});
				brick_index++;
				area_index++;
			});
		});

		//Check to see that there are bricks in the layout
		if( brick_index == 0 ){
			setBrickAlert( 'warning', 'Please add some bricks before saving your layout!' );
			return false;
		}

		var data = {
			action : 'bricklayer_save_layout',
			brick_data : $(this).serialize(),
			layout_areas : layout_data,
			bricklayer_wpnonce: $(this).find( '#bricklayer_wpnonce' ).val()
		};

		$.post( ajaxurl, 
				data,
				function(response){

					switch( response.status ){
						case 0:
							setBrickStatus( 'saved' );
							setBrickAlert( 'notification', response.message, 10000 );
							updateLayoutID( response.layout_id );
							updateBrickIDs( response );
							updateBrickNonce( response );
							break;
						case 1:
							setBrickStatus( 'saved' );
							setBrickAlert( 'warning', response.message );
							updateLayoutID( response.layout_id );
							updateBrickIDs( response );
							break;
						case 2:
							setBrickStatus( 'changed' );
							setBrickAlert( 'error', response.message );
							break;
					}
				},
				'json')
				.error( function( response ) { 
					setBrickStatus( 'changed' );
					setBrickAlert( 'error toggle', '<strong title="Click for gory details">Something has gone terribly wrong.</strong>'
									+ '<div class="toggle-content"><p>Oops.  Somebody dropped a wrench in the gears and everything has come to a grinding halt.</p><br/>'
									+ '<p class="alert alert-warning">IMPORTANT: if anything is printed below other than a JSON string, you need to fix that.  If PHP warnings or errors are '
									+ 'being printed, you may need to set WP_DEBUG to FALSE in wp-config.php, or fix the plugin/themes that are generating them.</p><br/>'
									+ '<h2>Error details</h2> <pre class="alert alert-error">'
									+ response.responseText + '</pre></div>' );

					$( "#brick_notifications .alert strong").click(function(){
						$(this).siblings('div').slideToggle();
					});

				});


		return false;
	});

	function updateLayoutID( layout_id ){
		$( '#layout_id' ).val( layout_id );
	}

	function updateBrickIDs( response ){
		var brick_ids = response.brick_ids;

		$('#layout .brick input[data-name="brick_id"]').each( function(){
			var index = $(this).attr('data-index');
			var brick_id = brick_ids[index];

			if( brick_id == -1 ){
				//delete
				$(this).parents('.brick').slideUp( function(){ $(this).remove(); });
			}
			else $(this).val( brick_id );
		});

	}

	function updateBrickNonce( response ){
		var nonce = response.nonce;
		$('#bricklayer_wpnonce').val( nonce );
	}





	/*
	 * BRICK CONTROLS
	 */

	/* Bookmark */
	$( '.brick-control-bookmark' ).live( 'click', function(){
		if( $(this).hasClass( 'brick-control-active' ) ){
			$(this).siblings('input[data-name="bookmark_brick"]').val( 'off' );
			$(this).removeClass('brick-control-active').attr('title', 'Bookmark Brick');
		}
		else{
			$(this).siblings('input[data-name="bookmark_brick"]').val( 'on' );
			$(this).addClass('brick-control-active').attr('title', 'Brick Bookmarked');
			if( brickStatus == 'saved' ){
				setBrickAlert( 'warning', 'Unsaved changes.');
				setBrickStatus( 'changed' );
			}
		}
		return false;
	});

	/* Trash */
	$( '.brick-control-delete').live( 'click', function(){
		if( $(this).hasClass( 'brick-control-active' ) ){
			$(this).siblings('input[data-name="delete_brick"]').val( 'off' );
			$(this).removeClass('brick-control-active').attr('title', 'Delete Brick');
		}
		else{
			$(this).siblings('input[data-name="delete_brick"]').val( 'on' );
			$(this).addClass('brick-control-active').attr('title', 'Marked for Deletion');
			if( brickStatus == 'saved' ){
				setBrickAlert( 'warning', 'Unsaved changes.');
				setBrickStatus( 'changed' );
			}
		}
		return false;
	});

	/* Grid */
	$( '.brick-controls .brick-control-grid').live( 'click', function(e){
		e.preventDefault();
		$(this).toggleClass( 'brick-control-active' );
		$(this).find( '.grid-columns' ).toggleClass( 'grid-columns-open' );
	});
	$( '.grid-columns' ).live( 'click', function( e ){
		e.stopPropagation();
	});
	$( '.brick-controls .brick-control-grid li').live( 'click', function(e){
		e.preventDefault();
		var $brick = $(this).parents('.brick');

		var val = $(this).attr( 'data-val' );
		$brick.find( 'input[data-name="grid_columns"]' ).val( val );

		$(this).siblings('li').removeClass( 'grid-columns-current' );
		$(this).addClass( 'grid-columns-current' );

		var current_grid_class = 'brick-grid-' + $brick.attr( 'data-columns' );
		$brick.removeClass( current_grid_class );
		
		if( val ){
			$brick
				.addClass( 'brick-column brick-grid-'+val )
				.attr( 'data-columns' , val );
		}
		else{
			$brick.removeClass( 'brick-column' );
		}
		$(this).parents('.brick-control').click();
	});

	var grid_col_order_11 = new Array(
		'2',
		'3',
		'1-3',
		'4',
		'5',
		'1-2',
		'6',
		'7',
		'2-3',
		'8',
		'9',
		''
	);

	var grid_col_order_16 = new Array(
		'2',
		'3',
		'4',
		'5',
		'1-3',
		'6',
		'7',
		'1-2',
		'8',
		'9',
		'10',
		'2-3',
		'11',
		'12',
		'13',
		'14'
	);
	/* '10',
		'11',
		'12',
		'13',
		'14' */


	$('.brick-control-grid-smaller').live( 'click', function(e){
		e.preventDefault();

		var grid_col_order = grid_col_order_11;	//switch on area_index //TODO

		var $controlGrid = $(this).siblings('.brick-control-grid');
		var current = $controlGrid.find( 'input[data-name="grid_columns"]' ).val();
		var current_index = grid_col_order.indexOf( current );
		if( current_index > 0 ){
			var smaller = grid_col_order[ --current_index ];
		}
		$controlGrid.find( 'li[data-val="'+smaller+'"]' ).click();
		$controlGrid.find( '.grid-columns' ).removeClass( 'grid-columns-open' );
	});

	$('.brick-control-grid-larger').live( 'click', function(e){

		e.preventDefault();

		var grid_col_order = grid_col_order_11;	//switch on area

		var $controlGrid = $(this).siblings('.brick-control-grid');
		var current = $controlGrid.find( 'input[data-name="grid_columns"]' ).val();
		var current_index = grid_col_order.indexOf( current );
		if( current_index < grid_col_order.length ){
			var larger = grid_col_order[ ++current_index ];
		}
		$controlGrid.find( 'li[data-val="'+larger+'"]' ).click();
		$controlGrid.removeClass( 'brick-control-active' );
		$controlGrid.find( '.grid-columns' ).removeClass( 'grid-columns-open' );
	});

	/* New Row */
	$( '.brick-control-new-row' ).live( 'click', function(){
		if( $(this).hasClass( 'brick-control-active' ) ){
			$(this).siblings('input[data-name="new_row"]').val( 'off' );
			$(this).removeClass('brick-control-active').attr('title', 'Activate New Row');
			$(this).parents('.brick').prev( '.brick-newrow').remove();
		}
		else{
			$(this).siblings('input[data-name="new_row"]').val( 'on' );
			$(this).addClass('brick-control-active').attr('title', 'New Row Activated');
			if( brickStatus == 'saved' ){
				setBrickAlert( 'warning', 'Unsaved changes.');
				setBrickStatus( 'changed' );
			}
			$(this).parents('.brick').before( '<li class="brick-newrow" ></li>');
		}
		return false;
	});



	$( '#brick_recycle .recycle-icon, #brick_recycle ul').click( function(){
		$( '#brick_recycle ul' ).toggleClass('open');
	});


	$( '#scaffold_picker .mini-blueprint').click( function(){
		$( '#scaffold_picker .mini-blueprint').removeClass( 'mini-blueprint-selected' );
		$(this).addClass( 'mini-blueprint-selected' );
		var id = $(this).attr('id');
		$( '#'+id+'_radio' ).click();
		$( '#layout' ).attr('class', '').addClass( 'layout-'+id );

		$( '#content_area' ).attr( 'class', 'brick-container brick-container-'+$(this).attr( 'data-content-cols') );
	});
	

	function setBrickAlert( alertType, text , timeout ){
		$('#brick_notifications')
			.hide()
			.html( '<div class="alert alert-'+alertType+'">'+text+'</div>' )
			.fadeIn();
		if( timeout ) setTimeout( removeBrickAlert , timeout );
	}

	function removeBrickAlert(){
		$('#brick_notifications').fadeOut('slow', function(){
			$(this).html('');
		});
	}

	
	
	var $loader = $('#loadingBricks');
	var spinner = new Spinner();
	$('#loadingBricks')
		.hide()  // hide it initially
		.ajaxStart(function() {
			$(this).fadeIn();
			spinner.spin( $('#loadingBricks').get(0) );
		})
	    .ajaxStop(function() {
			$(this).fadeOut('normal', function(){
				spinner.stop();
			});
			
		});



	var brickStatus = 'saved';
	$('#bricklayer_form :input').live( 'change', function(){

		if( brickStatus == 'saved' ){
			setBrickAlert( 'warning', 'Unsaved changes.');
			setBrickStatus( 'changed' );
		}
	});
	$('.brick').live( 'sortreceive', function( e, ui ){
		if( brickStatus == 'saved' ){
			setBrickAlert( 'warning', 'Unsaved changes.');
			setBrickStatus( 'changed' );
		}
	});

	function setBrickStatus( status ){
		brickStatus = status;

		switch( brickStatus ){

			case 'saved':
				setConfirmUnload(false);
				break;
			case 'changed':
				setConfirmUnload(true);
				break;
		}
	}
	
	function setConfirmUnload(on) {
		window.onbeforeunload = (on) ? unloadMessage : null;
	}
	function unloadMessage() {
		return 'There are unsaved changes to this layout which will be lost if you leave now.';
	}



	$( '#bricklayer_delete' ).click( function(e){

		var answer = confirm( 'Are you sure you want to delete this layout?' );

		if( answer ){

			var data = {
				action : 'bricklayer_delete_layout',
				layout_id : $( '#layout_id' ).val(),
				bricklayer_wpnonce: $('#bricklayer_wpnonce' ).val()
			};

			$.post( ajaxurl, 
					data,
					function(response){

						switch( response.status ){
							case 0:
								setBrickAlert( 'notification', 'Layout Deleted' );
								window.location = response.redirect;
								break;
							case 1:
								break;
							case 2:
								break;
						}
					},
					'json')
					.error( function( response ) { 
						setQueryStatus( 'changed' );
						setQueryAlert( 'error toggle', '<strong title="Click for gory details">Something has gone terribly wrong.</strong>'
										+ '<div class="toggle-content"><p>Oops.  Somebody dropped a wrench in the gears and everything has come to a grinding halt.</p><br/>'
										+ '<p class="alert alert-warning">IMPORTANT: if anything is printed below other than a JSON string, you need to fix that.  If PHP warnings or errors are '
										+ 'being printed, you may need to set WP_DEBUG to FALSE in wp-config.php, or fix the plugin/themes that are generating them.</p><br/>'
										+ '<h2>Error details</h2> <pre class="alert alert-error">'
										+ response.responseText + '</pre></div>' );

						$( "#brick_notifications .alert strong").click(function(){
							$(this).siblings('div').slideToggle();
						});
						$( "#brick_notifications .alert .toggle-content").hide();

						$( "#brick_notifications .alert a")
							.attr( 'title' , response.responseText );
					});
		}
	});

	/* Tabs */
	$('body').on('click', 'ul.tabs > li > a', function(e) {

        //Get Location of tab's content
        var contentLocation = $(this).attr('href');

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
});
	

//fgnass.github.com/spin.js#v1.2.4
(function(a,b,c){function g(a,c){var d=b.createElement(a||"div"),e;for(e in c)d[e]=c[e];return d}function h(a){for(var b=1,c=arguments.length;b<c;b++)a.appendChild(arguments[b]);return a}function j(a,b,c,d){var g=["opacity",b,~~(a*100),c,d].join("-"),h=.01+c/d*100,j=Math.max(1-(1-a)/b*(100-h),a),k=f.substring(0,f.indexOf("Animation")).toLowerCase(),l=k&&"-"+k+"-"||"";return e[g]||(i.insertRule("@"+l+"keyframes "+g+"{"+"0%{opacity:"+j+"}"+h+"%{opacity:"+a+"}"+(h+.01)+"%{opacity:1}"+(h+b)%100+"%{opacity:"+a+"}"+"100%{opacity:"+j+"}"+"}",0),e[g]=1),g}function k(a,b){var e=a.style,f,g;if(e[b]!==c)return b;b=b.charAt(0).toUpperCase()+b.slice(1);for(g=0;g<d.length;g++){f=d[g]+b;if(e[f]!==c)return f}}function l(a,b){for(var c in b)a.style[k(a,c)||c]=b[c];return a}function m(a){for(var b=1;b<arguments.length;b++){var d=arguments[b];for(var e in d)a[e]===c&&(a[e]=d[e])}return a}function n(a){var b={x:a.offsetLeft,y:a.offsetTop};while(a=a.offsetParent)b.x+=a.offsetLeft,b.y+=a.offsetTop;return b}var d=["webkit","Moz","ms","O"],e={},f,i=function(){var a=g("style");return h(b.getElementsByTagName("head")[0],a),a.sheet||a.styleSheet}(),o={lines:12,length:7,width:5,radius:10,color:"#000",speed:1,trail:100,opacity:.25,fps:20,zIndex:2e9,className:"spinner",top:"auto",left:"auto"},p=function q(a){if(!this.spin)return new q(a);this.opts=m(a||{},q.defaults,o)};p.defaults={},p.prototype={spin:function(a){this.stop();var b=this,c=b.opts,d=b.el=l(g(0,{className:c.className}),{position:"relative",zIndex:c.zIndex}),e=c.radius+c.length+c.width,h,i;a&&(a.insertBefore(d,a.firstChild||null),i=n(a),h=n(d),l(d,{left:(c.left=="auto"?i.x-h.x+(a.offsetWidth>>1):c.left+e)+"px",top:(c.top=="auto"?i.y-h.y+(a.offsetHeight>>1):c.top+e)+"px"})),d.setAttribute("aria-role","progressbar"),b.lines(d,b.opts);if(!f){var j=0,k=c.fps,m=k/c.speed,o=(1-c.opacity)/(m*c.trail/100),p=m/c.lines;!function q(){j++;for(var a=c.lines;a;a--){var e=Math.max(1-(j+a*p)%m*o,c.opacity);b.opacity(d,c.lines-a,e,c)}b.timeout=b.el&&setTimeout(q,~~(1e3/k))}()}return b},stop:function(){var a=this.el;return a&&(clearTimeout(this.timeout),a.parentNode&&a.parentNode.removeChild(a),this.el=c),this},lines:function(a,b){function e(a,d){return l(g(),{position:"absolute",width:b.length+b.width+"px",height:b.width+"px",background:a,boxShadow:d,transformOrigin:"left",transform:"rotate("+~~(360/b.lines*c)+"deg) translate("+b.radius+"px"+",0)",borderRadius:(b.width>>1)+"px"})}var c=0,d;for(;c<b.lines;c++)d=l(g(),{position:"absolute",top:1+~(b.width/2)+"px",transform:b.hwaccel?"translate3d(0,0,0)":"",opacity:b.opacity,animation:f&&j(b.opacity,b.trail,c,b.lines)+" "+1/b.speed+"s linear infinite"}),b.shadow&&h(d,l(e("#000","0 0 4px #000"),{top:"2px"})),h(a,h(d,e(b.color,"0 0 1px rgba(0,0,0,.1)")));return a},opacity:function(a,b,c){b<a.childNodes.length&&(a.childNodes[b].style.opacity=c)}},!function(){var a=l(g("group"),{behavior:"url(#default#VML)"}),b;if(!k(a,"transform")&&a.adj){for(b=4;b--;)i.addRule(["group","roundrect","fill","stroke"][b],"behavior:url(#default#VML)");p.prototype.lines=function(a,b){function e(){return l(g("group",{coordsize:d+" "+d,coordorigin:-c+" "+ -c}),{width:d,height:d})}function k(a,d,f){h(i,h(l(e(),{rotation:360/b.lines*a+"deg",left:~~d}),h(l(g("roundrect",{arcsize:1}),{width:c,height:b.width,left:b.radius,top:-b.width>>1,filter:f}),g("fill",{color:b.color,opacity:b.opacity}),g("stroke",{opacity:0}))))}var c=b.length+b.width,d=2*c,f=-(b.width+b.length)*2+"px",i=l(e(),{position:"absolute",top:f,left:f}),j;if(b.shadow)for(j=1;j<=b.lines;j++)k(j,-2,"progid:DXImageTransform.Microsoft.Blur(pixelradius=2,makeshadow=1,shadowopacity=.3)");for(j=1;j<=b.lines;j++)k(j);return h(a,i)},p.prototype.opacity=function(a,b,c,d){var e=a.firstChild;d=d.shadow&&d.lines||0,e&&b+d<e.childNodes.length&&(e=e.childNodes[b+d],e=e&&e.firstChild,e=e&&e.firstChild,e&&(e.opacity=c))}}else f=k(a,"animation")}(),a.Spinner=p})(window,document);

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
