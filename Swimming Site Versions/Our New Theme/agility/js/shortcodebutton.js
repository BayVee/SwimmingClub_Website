/*
 * Scroll Checkpoint Tiny MCE Button plugin
 */

(function($) {
  tinymce.create('tinymce.plugins.shortcodeMaster', {
	init : function(ed, url) {
		ed.addButton('shortcodeMaster', {
			title : 'Add Shortcode',
			image : url+'/../images/icons/bolt.png',
			onclick : function() {
				$( '#content_shortcodeMaster' ).data( { 'ed' : ed, 'editor_type' : 'tinymce' } );
				shortcodeMasterUI( '#content_shortcodeMaster' );
			}
		});
	},
	createControl : function(n, cm) {
		return null;
	},
  });
  tinymce.PluginManager.add('shortcodeMaster', tinymce.plugins.shortcodeMaster);

})(jQuery);

