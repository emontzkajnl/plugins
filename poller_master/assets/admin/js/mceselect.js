(function() {	
    tinymce.create('tinymce.plugins.poller_master', {
        init : function(ed, url) {			
        },
		createControl : function(n, cm) {
			if( n == 'poller_master_select' ){
				var mlb = cm.createListBox('poller_master_select', {
					title : 'Select Poll',
					onselect : function( v ) {
						if( v !== "" ){
							tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[poller_master poll_id="'+v+'"]');
						}
					}
				});
				if( polls_array.length > 0 ){
					for( var i=0; i<polls_array.length; i++ ){
						mlb.add( polls_array[i].key, polls_array[i].value );
					}
				}			
				return mlb;
			}
			return null;
		}
        // ... Hidden code
    });
    // Register plugin
    tinymce.PluginManager.add( 'poller_master', tinymce.plugins.poller_master );
})();