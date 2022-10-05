/* script for shortcodes-in-use plugin */
jQuery(function($){
	$('#shortcodes_in_use_parameters')
		.on('change hilite.siu', 'input', function(e, hilite){
			var self = $(this);
			self.parent('.for_s-i-u_checkbox').toggleClass('s-i-u_hilite', !!hilite || self.prop('checked'));
		})
		.find('.s-i-u_provider_list')
		.on('click', '.s-i-u_list_reveal', function(){
			$(this).toggleClass('dashicons-arrow-down').next('.s-i-u_list_inset').slideToggle();
			this.blur();
			return false;
		})
		.on('change', 'input', function(){
			var self = $(this),
					turnedOn = self.prop('checked'),
					//tags, and any provider with no tags (eg. 'unknown'), will not have this...
					subContainer = self.parent().siblings('.s-i-u_list_inset'),
					providerWrap = self.parentsUntil('td').last(),
					isProvider = self.hasClass('s-i-u_providers_provider'),
					providerCheckbox = isProvider ? self : providerWrap.find('>label>input'),
					maybeUnhiliteProvider = false,
					nameCheckbox;
			if(!isProvider || subContainer.length){
				if(isProvider){
					if(turnedOn){
						//turn names and tags off...
						subContainer.find('input').prop('checked', false).trigger('hilite.siu');
					}
				}else if(self.hasClass('s-i-u_providers_name')){
					if(turnedOn){
						//turn tags off...
						subContainer.find('input').filter(':checked').prop('checked', false).trigger('hilite.siu');
						//turn provider off...
						providerCheckbox.prop('checked', false).trigger('hilite.siu', [true]);
					}
					//if turned off, check the provider...
					maybeUnhiliteProvider = !turnedOn;
				}else{
					nameCheckbox = self.closest('.s-i-u_list_inset').siblings('label').find('input.s-i-u_providers_name');
					if(turnedOn){
						//turn provider and name (if there is one) off...
						providerCheckbox.add(nameCheckbox).prop('checked', false).trigger('hilite.siu', [true]);
					}
					//if turned off, only need to do something if there are now no tags in this set that are ON...
					if(!turnedOn && !self.closest('div').find('input').filter(':checked').length){
						//un-hilite name (if there is one)...
						nameCheckbox.trigger('hilite.siu');
						//check the provider...
						maybeUnhiliteProvider = !turnedOn;
					}
				}
				//if there's nothing checked below provider then un-hilite provider...
				if(maybeUnhiliteProvider && !providerWrap.find('input').filter(':checked').length){
					providerCheckbox.trigger('hilite.siu');
				}
			}
		})
		.end()
		.find('input').filter(':checked').trigger('change');
	$('#shortcodes_in_use_results')
		.on('click', '.s-i-u_result-count', function(){
		  $(this).closest('.s-i-u_result-block').toggleClass('s-i-u_result-collapse').children('.s-i-u_result-rows').slideToggle();
		  this.blur();
		  return false;
		})
		.on('click', '.s-i-u_results-toggle-all', function(){
		  var t = $(this).closest('form').find('.s-i-u_results-table'),
		  		d = t.data();
		  d.collapse = !d.collapse;
		  t.find('.s-i-u_result-block')[ d.collapse ? 'not' : 'filter' ]('.s-i-u_result-collapse').find('.s-i-u_result-count').trigger('click');
		  this.blur();
		  return false;
		});
});
