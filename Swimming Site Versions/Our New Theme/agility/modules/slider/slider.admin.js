jQuery(document).ready(function($){
	$('#post-preview')
		.off('click')
		.attr('href', '#')
		.click(function(e){
			e.preventDefault();
			
			//TODO Make own preview
			/*if ( $('#auto_draft').val() == '1' && notSaved ) {
				autosaveDelayPreview = true;
				autosave();
				return false;
			}*/
			
			
			return false;
		});
	
});
