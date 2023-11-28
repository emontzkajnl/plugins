jQuery(document).ready(function($){
	"use strict"
	function show_element( element, callback ){
		var main_parent = element.parents('.poller_master_poll');
		var easing = main_parent.data('in_easing');
		var effect = main_parent.data('in_effect');
		
		if( effect == 'slide' ){
			element.hide().slideDown( 500, easing, function(){
				if( callback ){
					callback();
				}
			}  );
		}
		else if( effect == 'fade' ){
			element.hide().fadeIn( 500, easing, function(){
				if( callback ){
					callback();
				}
			}  );
		}
		else{
			element.hide().show( 500, easing, function(){
				if( callback ){
					callback();
				}
			}  );			
		}
	}
	
	function hide_element( element, callback ){
		var main_parent = element.parents('.poller_master_poll');
		var easing = main_parent.data('out_easing');
		var effect = main_parent.data('out_effect');	
			
		if( effect == 'slide' ){
			element.slideUp( 500,  easing, function(){
				if( callback ){
					callback();
				}
			} );
		}
		else if( effect == 'fade' ){
			element.fadeOut( 500,  easing, function(){
				if( callback ){
					callback();
				}
			} );
		}
		else{
			element.hide( 500,  easing, function(){
				if( callback ){
					callback();
				}
			} );	
		}		
	}
	
	/* conect with close results button */
	$(document).on( 'click', '.poller_master_close_res button', function(e){
		e.preventDefault();
		
		hide_element( $(this).parents('.poller_master_results') );
	});
	
	/* slow close the alerts */
	$(document).on( 'click', '.alert .close', function(e){
		e.preventDefault();
		
		hide_element( $(this).parents('.alert'), function(){ $(this).remove(); } );
	});	

	function show_success( message, parent_object ){
		var container = parent_object.find('.poller_master_message');
		container.append(
			'<div class="alert alert-success alert-dismissable">'+
				'<span class="glyphicon glyphicon-ok-sign"></span>'+
				'<button type="button" class="close" aria-hidden="true">&times;</button>'+
				message+
			'</div>'
		);
		show_element( container.find( '.alert:last' ) );
	}	
	
	function show_error( message, parent_object ){
		var container = parent_object.find('.poller_master_message');
		container.append(
			'<div class="alert alert-danger alert-dismissable">'+
				'<span class="glyphicon glyphicon-remove-sign"></span>'+
				'<button type="button" class="close" aria-hidden="true">&times;</button>'+
				message+
			'</div>'
		);
		
		show_element( container.find( '.alert:last' ) );
	}

	function show_loading( parent_object ){
		var height = parent_object.find('.poller_master_question_box').outerHeight( true );
		var width = parent_object.find('.poller_master_question_box').outerWidth( true );
		parent_object.find('.poller_master_results').html( '<div class="loading"></div>' ).css({height: height, width: width} );
		show_element( parent_object.find('.poller_master_results') );
	}
	
	function hide_loading( parent_object ){
		parent_object.find('.poller_master_results').slideUp( 500,  'easeOutBounce' );
	}
	
	function show_results( results_html, parent_object ){	
		parent_object.find('.loading').fadeOut(
			1000,
			function(){
				var obj = parent_object.find('.poller_master_results');
				obj.html( '<div class="results_box">'+results_html+'</div>' );						
				var progress_height = 87 / ( obj.find('.poller_master_bar').length );
				obj.find( '.poller_master_bar' ).css({ height: progress_height+'%'});
				obj.find('.results_box').fadeIn( 500, function(){
					obj.find( '.progress-bar' ).loadbar();
				} );
			}
		);
	}
	
	if( $('#poller_master_vote').length > 0 ){
		$(document).on( 'click', '#poller_master_vote', function(e){
			e.preventDefault();
			var $this = $(this);
			var parent_object = $(this).parents('.poller_master_poll');
			var poll_id = parent_object.data('poll_id');
			var form = parent_object.find('.poller_master_form');
			var votes = [];			
			
			form.find('input').each(function(){
				if( $(this).prop('checked') ){
					votes.push( $(this).val() );
				}
			});
			if( votes.length > 0 ){
				show_loading( parent_object );
				$.ajax({
					url: ajaxurl,
					dataType: "JSON",
					type: "POST",
					data: {
						poll_id: poll_id,
						votes: votes,
						action: "vote_the_poll"
					},
					success: function(response){
						if( !response.error ){
							show_success( response.success, parent_object );
							show_results( response.results_html, parent_object );
							if( !response.can_vote ){
								$this.fadeOut( 500 );
							}
							parent_object.find( '#poller_master_results' ).fadeIn( 500 );
							if( response.countdown_vote !== "" ){
								parent_object.append( response.countdown_vote );
								countdown_vote( parent_object.find( '.poller_master_vote_countdown' ) );
							}
						}
						else{
							hide_loading( parent_object );
							show_error( response.error, parent_object );							
						}
					},
					error: function(){
						hide_loading( parent_object );
					},
					complete: function(){
						
					}
				});
			}
			else{
				hide_loading( parent_object );
				show_error( form.data('error_empty_text'), parent_object );				
			}
		});
	}
	
	if( $('#poller_master_results').length > 0 ){
		$(document).on( 'click', '#poller_master_results', function(e){
			e.preventDefault();
			var parent_object = $(this).parents('.poller_master_poll');
			var poll_id = parent_object.data('poll_id');
			show_loading( parent_object );
			$.ajax({
				url: ajaxurl,
				dataType: "JSON",
				type: "POST",
				data: {
					poll_id: poll_id,
					action: "get_poll_results"
				},
				success: function( response ){
					if( !response.error ){
						show_results( response.results_html, parent_object );
					}
					else{
						hide_loading( parent_object );
						show_error( response.error, parent_object );
					}
				},
				error: function(){
					hide_loading( parent_object );
				},
				complete: function(){
				
				}
			});
		});
	}
	
	/* style for checkboxes and radio buttons */
	$('.poller_master_form').each(function(){
		var parent = $(this);
		var input_class = parent.data('input_class');
		parent.find('input').iCheck({
			checkboxClass: input_class,
			radioClass: input_class,
			labelHover: true,
			cursor: true
		});
	});
	
	if( $('.poller_master_poll_countdown').length > 0 ){
		$('.poller_master_poll_countdown').each(function(){
			var $this = $(this);
			$this.kkcountdown({
				dayText		: $this.data('day_text'),
				daysText 	: $this.data('days_text'),
				hoursText	: $this.data('hours_text'),
				minutesText	: $this.data('minutes_text'),
				secondsText	: $this.data('seconds_text'),
				addClass 	: 'poll_countdown',
				displayZeroDays : true,
				displayTexts : $this.data('show_texts'),
				rusNumbers  :   false
			});
		});
	}
	
	
	function countdown_vote( $this ){
		$this.kkcountdown({
			dayText		: $this.data('day_text'),
			daysText 	: $this.data('days_text'),
			hoursText	: $this.data('hours_text'),
			minutesText	: $this.data('minutes_text'),
			secondsText	: $this.data('seconds_text'),
			addClass 	: 'vote_countdown',
			displayZeroDays : true,
			displayTexts : $this.data('show_texts'),
			rusNumbers  :   false
		});	
	}
	if( $('.poller_master_vote_countdown').length > 0 ){
		$('.poller_master_vote_countdown').each(function(){
			countdown_vote( $(this) );
		});		
	}
	
	/* show messages which are present on page load */
	if( $('.poller_master_message').html() !== "" ){
		$('.poller_master_message .alert').each(function(){
			show_element( $(this) );			
		});
	}
	
});