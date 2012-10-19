//Color picker fields
jQuery(document).ready(function(){
        
    //Load the colorpicker value and initializes the colorpicker
    jQuery('.colorselector').each(function() {
        
        var csel = this;
        var parent = jQuery(this).parent();
        var colorfield = jQuery(parent).find('.color');
        jQuery(this).css('backgroundColor', jQuery(colorfield).val());
        
        jQuery(this).ColorPicker({
            color: jQuery(colorfield).val(),
            onShow: function (colpkr){
                jQuery(colpkr).fadeIn(500);
                return false;
            },
            onHide: function (colpkr){
                jQuery(colpkr).fadeOut(500);
                return false;
            },
            onChange: function (hsb, hex, rgb){            
                jQuery(csel).css('backgroundColor', '#'+hex);            
                jQuery(colorfield).val('#'+hex);
            }
        })
    })   
    
    //Change the field value
    jQuery('.color').keyup(function() {
        var clr = this;
        var parent = jQuery(this).parent();
        var picker = jQuery(parent).find('.colorselector');
        jQuery(picker).css('backgroundColor', jQuery(this).val());
        jQuery(picker).ColorPickerSetColor(jQuery(this).val());        
    });	
});

/* SETTINGS MENU */
jQuery(document).ready(function(){
	
	/* Menu Transitions */
	jQuery('.settingsmenu_element').click(function(event){
		var current_id = event.target.id;
		if(!jQuery('#' + current_id).hasClass('active')){
			jQuery('.cposettings_block').fadeOut(300);
    		jQuery('#' + current_id + '_block').delay(500).fadeIn(300);
			jQuery('.settingsmenu_element').removeClass('active');
			jQuery('#' + current_id).addClass('active');
		}
    });
	
	/* Save Settings */
	jQuery('.cposettings_submit').click(function(event){
		jQuery('.cposettings_submit').val('...');
    });

});


//My Uploader for cpotheme_form_medialibrary
jQuery(document).ready(function() {    
    
    var postid = null;
    var value_container = null;
    jQuery('.upload_button').click(function() {         
        
        value_container = jQuery(this).parent();
        
        postid = value_container.find('#upload_image_hidden').val();        
        tb_show('', 'media-upload.php?post_id='+postid+'&amp;type=image&amp;TB_iframe=true');
        //return false;
    });

    window.original_send_to_editor = window.send_to_editor;
    
    window.send_to_editor = function(html) {
        
        if (postid != null) {        
            imgurl = jQuery('img',html).attr('src');
            value_container.find('#upload_image').val(imgurl);
            tb_remove();
        } else {
            window.original_send_to_editor(html);
        }
    }
});

// Image list functionality
jQuery(document).ready(function() {
    
    //Change border color when selecting the image
    jQuery('.cposettings_block .form_image_list_item img').click(function() {
        
        //Change other borders
        var parent = jQuery(this).parent().parent();
        jQuery(parent).find('img').each(function() {
            jQuery(this).removeClass('selected');
        })
        
        //Selected image
        jQuery(this).addClass('selected');
        
        
    });   
})