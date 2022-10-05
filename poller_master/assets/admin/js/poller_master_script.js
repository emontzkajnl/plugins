jQuery(document).ready(function($){
	"use strict"
	/* BUILDING CHARTS */
	/* get all modals and append them directlly into the body since this is what boottrap modals wants */
	$('.modal').each(function(){
		var el = $(this).detach();
		$('body').append(el);
	});	
	var charts = ['summary_chart', 'registered_chart', 'guests_chart'];
	var chart_colors = [];
	/* function which will draw the chart */
	function draw_chart( chart_id ){
		var rows = [];
		var i = 1;
		var is_empty = true;
		$('#'+chart_id+'_data').find('input').each(function(){			
			var $this = $(this);
			rows.push({
				label: $('.stats_chart').data('answer_text')+' '+i, 
				value: parseInt($this.val()) 
			});
			if( is_empty ){
				if( parseInt($this.val()) > 0 ){
					is_empty = false;
				}
			}
			i++;
		});
		if( !is_empty ){
			var options = {
				element: chart_id,
				data: rows,
				labelColor: '#333',			
			};
			if( chart_colors.length > 0 ){
				options.colors = chart_colors;
			}
			Morris.Donut( options );		
		}
		else{
			$('#'+chart_id).html('<p class="notice">'+$('.stats_chart').data('no_data_text')+'</p>');
		}

	}
	/* throw an error message */
	function throw_error( message ){
		$('.information').html('<p class="error">'+message+'</p>');
	}
	/*throw a success message*/
	function throw_success( message ){
		$('.information').html('<p class="success">'+message+'</p>');
	}	
	/* prep for new entry */
	function clean_modal(){
		$('.poll_id').val(0);
		$('.information').html("");		
		$('.name').val('');
		tinyMCE.activeEditor.setContent('');
		$('.question').val('');
		$('.answers').html('');
		$('.right_side').find('select, input, textarea').each(function(){
			var $this = $(this);
			if( $this.is('select')  ){
				$this.find('option:first-child').attr("selected", true);
			}
			else if( $this.is('textarea') ){
				$this.val('');
			}
			else if( $this.attr('type') == 'checkbox' ){
				$this.prop('checked', false);
			}
			else if( $this.attr('type') == 'text' ){
				if( typeof $this.data('default') !== "undefined" ){
					$this.val( $this.data('default').toString() );
				}
				else{
					$this.val('');
				}			
				
			}
		});		
	}
	
	/* append modal title */
	$('.add_new_panel').click(function(e){
		e.preventDefault();
		clean_modal();
		$('.modal_title').html( $(this).data('modal_title') );
		$('#poll').modal();
	} );
	
	/* populate answers div */
	function format_html_poll_answers( answers ){
		$('.answers').html("");
		var answer_template;
		for( var i=0; i<answers.length; i++ ){
			answer_template = $('.answer_template').html().replace( "[x]", i ).replace( "[x+1]", i+1 );
			$('.answers').append( answer_template );
			$('.answers .answer:last').find('input').val( answers[i] );
		}
	}
	
	/* on edit click first get the all data and populate the modal and than show it */
	$(document).on( 'click', '.edit_poll', function(e){
		e.preventDefault();
		$('.information').html("");
		var $this = $(this);
		$this.find('span').attr( 'class', $this.data('working') );
		var $$this = this;
		var poll_id = $this.parents('tr').data('poll_id');
		$.ajax({
			url: ajaxurl,
			data: {
				action: 'edit_poll',
				poll_id: poll_id
			},
			type: "POST",
			dataType: "JSON",
			success: function(response){
				if( !response.error ){
					$('.poll_id').val(response.id);
					$('.modal_title').html( $this.data('modal_title') );
					$('.name').val( response.name );
					$('.question').val( response.question );
					tinyMCE.activeEditor.setContent( response.question );
					format_html_poll_answers( response.answers );
					$('.right_side').find('select, input, textarea').each(function(){
						var $this = $(this);
						if( $this.attr('type') == "checkbox" ){
							$this.prop( 'checked', response[$this.attr('class')] );
						}
						else if( $this.is('select') ){
							var value = response[$this.attr('class')];
							if( $this.find('option[value="'+value+'"]').length > 0 ){
								$this.val( value );
							}
							else{
								$this.find('option:first-child').attr("selected", true);				
							}
						}
						else{
							$this.val( response[$this.attr('class').replace( ' hasDatepicker','' ).replace( ' wp-editor-area', '' )] );
						}
					});	
					$('#poll').modal();		
				}
				else{
					throw_error( response.error );
				}
			},
			error: function(){
			
			},
			complete: function(){
				$this.find('span').attr( 'class', $this.data('ready') );
			}
		});
	});
	
	/* deleting poll */
	$(document).on( 'click', '.delete_poll', function(e){
		e.preventDefault();
		var $this = $(this);		
		if (confirm( $this.data('confirm') )) {
			$this.find('span').attr( 'class', $this.data('working') );
			$.ajax({
				url: ajaxurl,
				data: {
					action: "delete_poll",
					poll_id: $this.parents('tr').data('poll_id')
				},
				type: "POST",
				dataType: "JSON",
				success: function(response){
					if( !response.error ){
						alert( response.success );
						$this.parents('tr').fadeOut(
							500,
							function(){
								$(this).remove();
							}
						);
					}
					else{
						alert( response.error );
					}
				},
				error: function(){
				
				},
				complete: function(){
					$this.find('span').attr( 'class', $this.data('ready') );
				}
			});
		}
	});
	
	/* resetin logs */
	$(document).on( 'click', '.reset_poll', function(e) {
		e.preventDefault();
		var $this = $(this);		
		if (confirm( $this.data('confirm') )) {
			$this.find('span').attr( 'class', $this.data('working') );
			var poll_id = $this.parents('tr').data('poll_id');
			$.ajax({
				url: ajaxurl,
				data:{
					action: 'reset_poll',
					poll_id: poll_id
				},
				type: "POST",
				dataType: "JSON",
				success: function(response){
					if( !response.error ){
						if( table_polls ){
							table_polls.fnDestroy();
						}
						$('tr[data-poll_id="'+response.id+'"] td:eq(2)').html( '0' );
						
						table_polls = $('.table_polls').dataTable({
							bPaginate: false,
							sDom: 'lfrtp',
							bAutoWidth: false
						});
						
						alert( response.success );
					}
					else{
						alert( response.error );
					}
				},
				error: function(){
				
				},
				complete: function(){
					$this.find('span').attr( 'class', $this.data('ready') );
				}
			});
		}
	});
	
	/* view all stats */
	function format_votes( votes ){
		votes = votes.split(",");
		for( var i=0; i<votes.length; i++ ){
			votes[i] = parseInt(votes[i]) + 1;
		}
		
		return votes.join(",");
	}
	
	function format_time( timestamp ){
		var montharray = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
		var date_time_string = "";
		if( timestamp !== "" ){
			var date = new Date( parseInt(timestamp)*1000 );
			var day = date.getDate();
			var jday;
			switch( day % 10){
				case 1 : jday = "st"; break;
				case 2 : jday = "nd"; break;
				case 3 : jday = "rd"; break;
				default: jday = "th";
			}
			date_time_string = montharray[ date.getMonth() ]+" "+day+""+jday+", "+date.getFullYear();
		}
		
		return date_time_string;
	}
	
	function update_chart(){
		for( var i=0; i<charts.length; i++ ){
			var chart_id = charts[i];
			if( $('#'+chart_id+'_data').html() !== "" ){
				draw_chart( chart_id );
			}
			else{
				$('#'+chart_id).hide();
			}
		}	
	}
	
	$(window).resize(function(){
		if( $('#stats').is(':visible') ){
			update_chart();
		}
	});
	
	var table_logs;
	
	function clear_table_logs( table ){		
		if( table_logs ){
			table_logs.fnDestroy();
			$('.table_logs tbody').empty();
		}	
	}
	
	function serve_logs( logs ){
		for( var j=0; j<logs.length; j++ ){
			var data = [
				logs[j].log_id,
				format_time( logs[j].vote_time ),
				logs[j].username,
				logs[j].ip,
				format_votes( logs[j].votes ),
				logs[j].registered
			];
			table_logs.fnAddData(data);
			$('.table_logs tbody tr:last td:eq(0)').addClass( 'column-tags' );
			$('.table_logs tbody tr:last td:eq(4)').addClass( 'column-tags' );
			$('.table_logs tbody tr:last td:eq(5)').addClass( 'column-tags' );
		}
	}
	
	$(document).on( 'click', '.stats_poll', function(e) {
		e.preventDefault();
		$('.table_logs tbody').html('');
		$('.load_more_logs').show();
		var $this = $(this);
		$this.find('span').attr( 'class', $this.data('working') );
		var $$this = this;
		var poll_id = $this.parents('tr').data('poll_id');
		$.ajax({
			url: ajaxurl,
			data:{
				action: 'get_stats',
				poll_id: poll_id
			},
			type: "POST",
			dataType: "JSON",
			success: function(response){
				if( !response.error ){
					$('#stats').modal();
					$('.stats_question').html( response.name );
					$('.stats_answers').html( response.answers );
					for( var i=0; i<charts.length; i++ ){
						var chart_id = charts[i];
						if( response[chart_id] ){
							$('#'+chart_id+'_data').html( response[chart_id] );
						}
					}
					
					chart_colors = response.chart_colors !== "" ? response.chart_colors.split(',') : [];
					
					update_chart();
					var table = $('.stats_chart').find('.table_logs tbody');
					var logs = response.logs;
					clear_table_logs( table );
					if( logs.length > 0 ){
						table_logs = $('.table_logs').dataTable({
							bPaginate: false,
							sDom: 'lrtp',
							bAutoWidth: false
						});
					
						serve_logs( logs );
						if( response.all_logs == "1" ){
							$('.load_more_logs').hide();
						}
						else{
							$('.load_more_logs').data( 'poll_id', poll_id );
							$('.load_more_logs').data( 'offset', logs.length );
						}			
						
						
						$('.filter_logs').off('keyup');
						$('.filter_logs').on( 'keyup',function(){
							table_logs.fnFilter( this.value, $(".filter_logs_field").val() );
						});
						
						
					}
					else{
						table.append( '<tr><td colspan="6">No records found</td></tr>' );
						$('.load_more_logs').hide();
					}
				}
				else{
					alert( response.error );
				}
			},
			error: function(){				
			},
			complete: function(){
				$this.find('span').attr( 'class', $this.data('ready') );
			}
		});
	});
	
	$('.load_more_logs').click(function(e){
		e.preventDefault();
		var $this = $(this);
		var poll_id = $this.data( 'poll_id' );
		var offset = $this.data('offset');
		$this.text( $this.data('working') );
		$.ajax({
			url: ajaxurl,
			dataType: "JSON",
			data: {
				poll_id: poll_id,
				offset: offset,
				action: 'load_more_stats'
			},
			type: "POST",
			success: function( response ){
				if( !response.error ){
					var logs = response.logs;
					if( logs.length > 0 ){
						serve_logs( logs );
						$('.load_more_logs').data( 'offset', offset+logs.length );
						if( response.all_logs == "1" ){
							$('.load_more_logs').hide();
						}
					}
				}
			},
			error: function(){
			
			},
			complete: function(){
				$this.text( $this.data('ready') );
			}
		});
	});	
	
	/* adding new answer box */
	$('.add_answer').click(function(e){
		e.preventDefault();
		var answer_number = $('.answer').length - 1; /* minus the hidden one */
		var answer_tempalte = $('.answer_template').html().replace( "[x]", answer_number ).replace( "[x+1]", answer_number+1 );
		$('.answers').append( answer_tempalte );
	} );
	
	/* removing answer box */
	$(document).on( 'click', '.remove_answer', function(e){
		e.preventDefault();
		$(this).parents('.answer').remove();
		var i = 0;
		$('.answers .answer').each(function(){
			var $this = $(this);
			$this.attr( 'data-answer_id', i );
			$this.find('.answer_num').text( i+1 );
			i++;
		});
	} );
	
	/* save the new modal or update existing one */
	/* this function is used for edit and add new poll */
	function update_poll( name, question, answers ){
		var data = {};
		data.name = name;
		data.question = question;
		data.answers = answers;	
		data.poll_id = $('.poll_id').val();
		data.action = "update_poll";
		$('.right_side').find('select, input, textarea').each(function(){
			var $this = $(this);
			if( $this.attr('type') == "checkbox" ){
				data[$this.attr('class')] = $this.prop('checked') === true ? "1" : "0";
			}
			else{
				data[$this.attr('class').replace(' hasDatepicker','')] = $this.val();
			}
		});
		
		$.ajax({
			url: ajaxurl,
			type: "POST",
			data: data,
			dataType: "JSON",
			success: function(response){
				if( !response.error ){
					if( table_polls ){
						table_polls.fnDestroy();
					}
					if( response.new_poll == "1" ){
						$('.table_polls tbody').append(
							'<tr class="'+( $('.table_polls tbody tr').length % 2 == 0 ? "": "alternate" )+'" data-poll_id="'+response.id+'">'+
								'<td>'+response.id+'</td>'+
								'<td>'+response.name+'</td>'+
								'<td>'+response.votes+'</td>'+
								'<td>'+response.start_date+'</td>'+
								'<td>'+response.end_date+'</td>'+
								'<td>'+response.status_html+'</td>'+
								'<td>'+response.actions+'</td>'+
							'</tr>'
						);
						$('.poll_id').val( response.id );
						$('.table_polls tr:last a[data-toggle="tooltip"]').tooltip({placement: 'top'});
						throw_success( response.success );				
					}
					else{

						$('tr[data-poll_id="'+response.id+'"] td:eq(1)').html( response.name );
						$('tr[data-poll_id="'+response.id+'"] td:eq(2)').html( response.votes );
						$('tr[data-poll_id="'+response.id+'"] td:eq(3)').html( response.start_date );
						$('tr[data-poll_id="'+response.id+'"] td:eq(4)').html( response.end_date );
						$('tr[data-poll_id="'+response.id+'"] td:eq(5)').html( response.status_html );
						throw_success( response.success );
					}
					table_polls = $('.table_polls').dataTable({
						bPaginate: false,
						sDom: 'lfrtp',
						bAutoWidth: false
					});
				}
				else{
					throw_error( response.error );
				}
			},
			error: function(){
			},
			complete: function(){
				$('.add_new_poll').text( $('.add_new_poll').data('ready') );
			}
		});
	}
	
	/* clone the poll */
	$('.clone_poll').click(function(e){
		e.preventDefault();
		var $this = $(this);
		$this.find('span').attr( 'class', $this.data('working') );
		var $$this = this;
		var poll_id = $this.parents('tr').data('poll_id');
		$.ajax({
			url: ajaxurl,
			data: {
				action: 'clone_poll',
				poll_id: poll_id
			},
			type: "POST",
			dataType: "JSON",
			success: function(response){
				if( !response.error ){	
					$('.table_polls tbody').append(
						'<tr class="'+( $('.table_polls tbody tr').length % 2 == 0 ? "": "alternate" )+'" data-poll_id="'+response.id+'">'+
							'<td>'+response.id+'</td>'+
							'<td>'+response.name+'</td>'+
							'<td>'+response.votes+'</td>'+
							'<td>'+response.start_date+'</td>'+
							'<td>'+response.end_date+'</td>'+
							'<td>'+response.status_html+'</td>'+
							'<td>'+response.actions+'</td>'+
						'</tr>'
					);
					$('.poll_id').val( response.id );
					$('.table_polls tr:last a[data-toggle="tooltip"]').tooltip({placement: 'top'});				
				}
			},
			error: function(){
			
			},
			complete: function(){
				$this.find('span').attr( 'class', $this.data('ready') );
			}
		});
	})
	
	/* send the data for creating new poll or editig existig one */
	$('.add_new_poll').click(function(e){
		tinyMCE.triggerSave();
		e.preventDefault();
		var $this = $(this);
		$this.text( $this.data('sending') );
		$('.information').html("");
		/* if the is a hidden field in the modal that means that the modal is used for editing the poll */
		var poll_id = $('.poll_id').val();
		var question = $('.question').val();
		var name = $('.name').val();
		var answers = [];
		if( name !== "" ){
			$( '.answers .answer' ).each(function(){
				var value = $(this).find('input').val();
				if( value !== "" ){
					answers.push(value);
				}
			});
			if( answers.length === 0 ){
				throw_error( $('.answers').data('error_message') );
				$this.text( $this.data('ready') );
			}
			else{
				update_poll( name, question, answers );
			}
		}
		else{
			throw_error( $('.name').data('error_message') );
			$this.text( $this.data('ready') );
		}
	});
		
	$('.start_date, .end_date').datetimepicker({
		addSliderAccess: true,
		sliderAccessArgs: { touchonly: false }
	});
	
	var table_polls = $('.table_polls').dataTable({
		bPaginate: false,
		sDom: 'lfrtp',
		bAutoWidth: false
	});
	
	/* ==================================================POLLIT OPTIONS================================================= */
	
	/* this is because of large number of color pickers so initiate tem when a section is open and not all at the begginig */
	function handle_colorpickers( section, callback ){
		var pickerize_field = section.find('.pickerized');
		
		if( pickerize_field.val() ==  0 ){
			section.find('.multiple_colors').each(function(){
				create_pickers( $(this) );
			});
			
			/* option's color pickers */			
			section.find('.poller_master_colorpicker').each(function(){
				$(this).wpColorPicker();	
			});
			
			pickerize_field.val( 1 );
		}
		
		callback();
	}
	
	/* change sections of settings */	
	$('#navigation a, #sub_navigation a').click(function(e){	
		e.preventDefault();
		var $this = $(this);
		var parent = $this.parent();
		var slide_up_el = $('.'+( parent.attr('id') == 'navigation' ? 'section' : 'template' )+'.visible');
		
		if( $this.hasClass( 'active' ) ){
			return;
		}
		parent.find('a').removeClass('active');
		slide_up_el.hide();
		slide_up_el.toggleClass( 'visible hidden' );

		var target_div = $this.data('section');
		var $target_div = $(target_div);
		$this.addClass( 'active' );
		/* add section id to the form action so it can be displayed on form submit, but put only part so browser do not go to that section */
		if( parent.attr('id') == 'navigation' ){
			$('#poller_master_settings').attr( 'action', $('#poller_master_settings').attr( 'action' ).split('#')[0]+target_div.replace( "_section", "" ) );
		}
		else{
			$('#poller_master_settings').attr( 'action', $('#poller_master_settings').attr( 'action' ).split('!#')[0]+'!'+target_div );
		}
		
		if( $this.data('pickerize') == 'yes' ){
			handle_colorpickers( $target_div, function(){
				$target_div.show();
				$target_div.toggleClass('hidden visible');
			} );
		}
		else{
			$target_div.show();	
			$target_div.toggleClass('hidden visible');
		}
	});
	
		
	/* if there is hash than show that settings */
	if( window.location.hash !== "" ){
		var target_sections = window.location.hash.split('!');
		var taget_main_nav = target_sections[0]+"_section";
		var taget_main_el = $(taget_main_nav);
		if( taget_main_el.length > 0 && !taget_main_el.is(':visible') ){
			$('a[data-section="'+taget_main_nav+'"]').trigger( 'click' );
		}
		if( target_sections[1] ){
			var target_sub_el = $(target_sections[1]);
			if( target_sub_el.length > 0 && !target_sub_el.is(':visible') ){
				$('a[data-section="'+target_sections[1]+'"]').trigger( 'click' );
			}
		}
	}
	else{
		$('#navigation a:first').trigger( 'click' );
	}
	
	/* progress bar style changer */
	$('.progress_bar_style').change(function(){
		$(this).parents('.template').find('.progress_bar_demo .progress').attr( 'class', 'progress '+$(this).val() );
	});
	
	function update_colors( container ){
		var parent = container.parents('.label'); 
		var field = parent.find( '.multiple_colors' );
		var colors = [];
		
		container.find( '.input_color' ).each(function(){
			var value = $(this).wpColorPicker( 'color' );
			if( value !== "" ){
				colors.push( value );
			}
		});
		
		field.val( colors.join(',') );
	}
	
	
	$(document).on( 'click', '.remove_color', function(e){
		e.preventDefault();
		$(this).parents('.color_box').fadeOut(
			200,
			function(){
				$(this).remove();
			}
		);
		
		update_colors( $(this).parents('.label').find( '.color_pickers' ) );
	} );
	
	$('.add_color').click(function(){
		var container = $(this).parents('.label').find( '.color_pickers' );
		container.append( '<div class="color_box"><a href="javascript:;" class="button action remove_color">X</a><input class="poller_master_colorpicker input_color" type="text" value=""><br /></div>' );

		var last_bar_color = container.find('.input_color:last');
		
		last_bar_color.wpColorPicker({
			change: function( event, ui ){
				update_colors( container );
			}
		});
		
		update_colors( container );		
	} );	
	
	
	/* progress bar color pickers */
	function create_pickers( $this ){
		var value = $this.val().split(",");
		var container = $this.parent().find( '.color_pickers' );
		container.html( '' );
		
		if( value ){
			for( var i=0; i<value.length; i++ ){
				if( value[i] !== "" ){
					container.append( '<div class="color_box"><a href="javascript:;" class="button action remove_color">X</a><input type="text" class="poller_master_colorpicker input_color" value="'+value[i]+'"><br /></div>' );
				}
			}
		}
		
		container.find('.input_color').each(function(){
			var $$this = $(this);
			setTimeout(function(){
				$$this.wpColorPicker({
					change: function( event, ui ){
						update_colors( container );
					}
				});
			}, 0);
		});	
		
		container.sortable({
			update: function(){
				update_colors( $(this) );
			}
		});
	}
		
	
	/* radio & checkboxes switcher */
	function update_scheme_preview( skin, color, parent ){
		var image_el = parent.find( '.answer_scheme_preview' );
		
		var image_url_base = image_el.attr( 'src' ).split('skins/')[0]+"skins/";
		if( skin !== "futurico" && skin !== "polaris" ){				
			image_el.attr( 'src', image_url_base+skin+"/"+color+'.png' );
		}
		else{
			image_el.attr( 'src', image_url_base+skin+"/"+skin+'.png' );
		}		
	}
	
	$('.radio_checkbox_style').change(function(){
		var $this = $(this);
		var parent = $this.parents('.template');
		var value = $this.val();
		var scheme_el = parent.find('#answer_scheme');
		if( value == 'polaris' || value == "futurico" ){
			scheme_el.fadeOut('fast');
		}
		else{
			scheme_el.fadeIn('fast');
			scheme_el.attr( 'class', $(this).val() );
		}
		
		update_scheme_preview( value, parent.find('.check_radio_scheme').val(), parent );
		
	});
	
	/* radio checkboxes color scheme */

	function change_scheme( $this ){
		if (!$this.hasClass('active')) {
			var parent = $this.parents('.template');
			var color_scheme = $this.attr('class').trim();
			var skin = parent.find( '.radio_checkbox_style' ).val();
			update_scheme_preview( skin, color_scheme, parent );
			
			$this.siblings().removeClass('active');
			$this.parents('#answer_scheme').find('.check_radio_scheme').val( color_scheme );
			$this.addClass('active');
		}
	}
	
	$('.colors li').click(function() {
		var $this = $(this);
		
		change_scheme( $this );
	});	
	
	
	$('.add_loader').click(function(e){
		e.preventDefault();		
		var $this = $(this);
        var frameArgs = {
            multiple: false,
            title: 'Select File'
        };		
        var Frame = wp.media(frameArgs);
        Frame.on('select', function () {
            var selection = Frame.state().get('selection').toJSON();
			var extension =  selection[0].url.substr((~-selection[0].url.lastIndexOf(".") >>> 0) + 2);
			var thumbnail_url = selection[0].url.replace( "."+extension, '-150x150.'+extension );
			$this.parents('.loader_data').find('input[type="hidden"]').val( selection[0].id );
			/* check if image with dimensions 150 x 150 exists and if so than append it, else apend full image and set its width and height to 150, 150 respectively. */
			$.get( thumbnail_url )
				.done(function() { 
					$this.parents('.loader_data').find('.loader').html('<img src="'+thumbnail_url+'" class="poller_master_thumbnail"><a href="javascript:;" class="delete_loader">[X]</a>');

				}).fail(function() { 
					$this.parents('.loader_data').find('.loader').html('<img src="'+selection[0].url+'" class="poller_master_thumbnail"><a href="javascript:;" class="delete_loader">[X]</a>');
				});
        });		
        Frame.open();
		
	});
	$(document).on( 'click', '.delete_loader', function(e){
		e.preventDefault();
		var $this = $(this);
		$this.parents('.loader_data').find('input[type="hidden"]').val( "" );
		$this.parents('.loader_data').find('.loader').fadeOut(
			250,
			function(){
				$(this).html("");
				$(this).show();
			}
		);
	});	
	
	$('a[data-toggle="tooltip"]').tooltip({placement: 'top'});
	
	$('.reset_template').click(function(){
		var parent = $(this).parents('.template');
		parent.find( 'select, input, textarea' ).each(function(){
			var $this = $(this);
			var hasData = ( typeof $this.attr('data-default_value') !== "undefined" && typeof $this.attr('data-default_value') !== null) ? true : false;
			if( hasData ){
				$this.val( $this.data('default_value') ).trigger( 'change' );
			}
			
			if( $this.hasClass( 'check_radio_scheme' ) ){
				change_scheme( parent.find('.colors .'+$this.data('default_value')) );
			}
			
			if( $this.hasClass( 'multiple_colors' ) ){
				create_pickers( $this );
			}
		});
	} );
});