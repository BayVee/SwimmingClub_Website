//SLIDESHOW
jQuery(document).ready(function(){
    jQuery('.slider_container').cycle({
        fx: 'fade',
		next: '#slider .next', 
    	prev: '#slider .prev',
		pager: '#slider .pages',
		pause: true,
		pauseOnPagerHover: true,
		containerResize: false,
		slideResize: false,
		fit: 1
    });
});

//SLIDESHOW IN PORTFOLIO PAGE
jQuery(document).ready(function(){
    jQuery('.portfolio .slides ul').cycle({
        fx: 'fade',
		pager: '.portfolio .pages',
		pause: true,
		pauseOnPagerHover: true,
		containerResize: false,
		slideResize: false,
		fit: 1,
		after: AfterPortfolioSlide
    });	
});

function AfterPortfolioSlide(curr, next, opts, fwd) {
	var ht = jQuery(this).height();
	jQuery(this).parent().animate({height: ht});
}