(function() {
	var values = [];
	if( polls_array.length > 0 ){
		for( var i=0; i<polls_array.length; i++ ){
			values.push({
				text: polls_array[i].key, 
				value: polls_array[i].value 
			});
		}
	}
    tinymce.create('tinymce.plugins.poller_master', {
        init : function(ed, url) {	
			ed.addButton('poller_master_select', {
				type: 'listbox',
				text: 'Select Poll',
				icon: false,
				onselect: function(e) {
					var value = this.value();
					if( value !== "" ){
						ed.insertContent('[poller_master poll_id="'+value+'" extra_class=""]');
					}
				},
				values: values,
				onPostRender: function() {
					ed.my_control = this;
				}
			});
        }
    });
    // Register plugin
    tinymce.PluginManager.add( 'poller_master', tinymce.plugins.poller_master );
})();