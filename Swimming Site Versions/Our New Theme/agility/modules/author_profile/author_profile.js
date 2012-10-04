jQuery(document).ready( function($) {
	$( '#author_profile_image_button' ).click(function() {
		formfield = $( '#author_profile_image' ).attr( 'name' );
		tb_show( '', 'media-upload.php?type=image&amp;TB_iframe=true' );
		return false;
	});
	
	window.send_to_editor = function(html) {
		imgurl = $('img',html).attr('src');
		$( '#author_profile_image' ).val(imgurl);
		$( '#author_profile_image_preview' ).attr( 'src' , imgurl );
		tb_remove();
	}

});
