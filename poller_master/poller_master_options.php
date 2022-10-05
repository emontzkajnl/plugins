<br />
<?php 
global $poll; 
$poll->admin_scripts();
if($_SERVER['REQUEST_METHOD'] == "POST"){
	if(!wp_verify_nonce($_POST['save_options_field'], 'save_options')){
		die( __( 'Sorry, but this request is invalid', 'poller_master' ) );
	}
	/* save options */
	else{
		/* since update_options returns false if the value is the same as previously saved we wil use options_changed flag to check if there is 
			something to be saved and throd adequate iformation.
		*/
		$old_options = $poll->options;
		foreach( $poll->options as $key => $value ){
			if( isset( $_POST[$key] ) ){
				$poll->options[$key] = $key !== "templates" ? esc_html( $_POST[$key] ) : $_POST[$key];
			}
		}
		$info = $poll->save_options();
		if( $old_options !== $poll->options ){
			$poll->throw_info( 'Settings are saved.', 'success' );
		}
		else{			
			if( $old_options === $poll->options ){
				$poll->throw_info( 'No changes in options, nothing to save.', 'notice' );
			}
			else{
				$poll->throw_info( 'There was an error saving your settings please try again.', 'error' );
			}
		}	
	}
}
/* get options in separated variables */
extract( $poll->options );

?>
<noscript>
	<div class='updated' id='javascriptWarn'>
		<p><?php _e('JavaScript appears to be disabled in your browser. For this plugin to work correctly, please enable JavaScript or switch to a more modern browser.', 'poller_master');?></p>
	</div>
</noscript>
<form  id="poller_master_settings" method="post" action="<?php echo $GLOBALS['PHP_SELF'] . '?page=' . $poll->poller_master_options_page; ?>">

	<div id="navigation">
		<a href="javascript:;" data-section="#general_section" data-pickerize="yes"><?php _e( 'General', 'poller_master' ); ?></a>
		<a href="javascript:;" data-section="#poll_templates_section" data-pickerize="no"><?php _e( 'Poll Templates', 'poller_master' ); ?></a>
	</div>
	
	<div id="general_section" class="section hidden">
		<input type="hidden" value="0" class="pickerized">
		<h3 class="label-info"><?php _e( 'General Settings', 'poller_master' ); ?></h3>
		
		<label>			
			<?php _e('Vote Text:', 'poller_master');?> <input type="text" name="vote_text" class="poller_master_wide" value="<?php echo stripslashes( $vote_text ); ?>"><br />
			<small><?php _e( 'Input text which will appear on the vote button.', 'poller_master' ); ?></small>
		</label><br />
		
		<label>			
			<?php _e('Results Text:', 'poller_master');?> <input type="text" name="results_text" class="poller_master_wide" value="<?php echo stripslashes( $results_text ); ?>"><br />
			<small><?php _e( 'Input text which will appear on the results button.', 'poller_master' ); ?></small>
		</label><br />

		<label>			
			<?php _e('Vote Success Text:', 'poller_master');?> <input type="text" name="vote_success_text" class="poller_master_wide" value="<?php echo stripslashes( $vote_success_text ); ?>"><br />
			<small><?php _e( 'Input text which will appear on vote success.', 'poller_master' ); ?></small>
		</label><br />

		<label>			
			<?php _e('Vote Error Text:', 'poller_master');?> <input type="text" name="vote_error_text" class="poller_master_wide" value="<?php echo stripslashes( $vote_error_text ); ?>"><br />
			<small><?php _e( 'Input text which will appear on vote error.', 'poller_master' ); ?></small>
		</label><br />

		<label>			
			<?php _e('Already Voted Text:', 'poller_master');?> <input type="text" name="vote_already_text" class="poller_master_wide" value="<?php echo stripslashes( $vote_already_text ); ?>"><br />
			<small><?php _e( 'Input text which will appear if the user has been already voted.', 'poller_master' ); ?></small>
		</label><br />

		<label>			
			<?php _e('Vote Again Text:', 'poller_master');?> <input type="text" name="vote_again_text" class="poller_master_wide" value="<?php echo stripslashes( $vote_again_text ); ?>"><br />
			<small><?php _e( 'Input text which will appear if the user has been voted but he can vote again.', 'poller_master' ); ?></small>
		</label><br />

		<label>			
			<?php _e('Vote Results Text:', 'poller_master');?> <input type="text" name="vote_results_text" class="poller_master_wide" value="<?php echo stripslashes( $vote_results_text ); ?>"><br />
			<small><?php _e( 'Input text which will appear on results listing. Use <b>{%TOTAL_VOTES%}</b> to place the number of the total votes.', 'poller_master' ); ?></small>
		</label><br />
		
		<label>			
			<?php _e('Poll Closed Text:', 'poller_master');?> <input type="text" name="poll_closed_text" class="poller_master_wide" value="<?php echo stripslashes( $poll_closed_text ); ?>"><br />
			<small><?php _e( 'Input text which will appear if the poll is closed.', 'poller_master' ); ?></small>
		</label><br />

		<label>			
			<?php _e('Poll Ended Text:', 'poller_master');?> <input type="text" name="poll_expired_text" class="poller_master_wide" value="<?php echo stripslashes( $poll_expired_text ); ?>"><br />
			<small><?php _e( 'Input text which will appear if the poll ended.', 'poller_master' ); ?></small>
		</label><br />

		<label>			
			<?php _e('Empty Vote Text:', 'poller_master');?> <input type="text" name="error_empty_text" class="poller_master_wide" value="<?php echo stripslashes( $error_empty_text ); ?>"><br />
			<small><?php _e( 'Input text which will appear if the users clicked on vote and did not selected any answer.', 'poller_master' ); ?></small>
		</label><br />			

		<label>			
			<?php _e('Poll For Registered Users Only Text:', 'poller_master');?> <input type="text" name="registered_text" class="poller_master_wide" value="<?php echo stripslashes( $registered_text ); ?>"><br />
			<small><?php _e( 'Input text which will appear if the poll is only for the registered users.', 'poller_master' ); ?></small>
		</label><br />	
		
		<label>			
			<?php _e('Poll For Guests Only Text:', 'poller_master');?> <input type="text" name="guests_text" class="poller_master_wide" value="<?php echo stripslashes( $guests_text ); ?>"><br />
			<small><?php _e( 'Input text which will appear if the poll is only for the guests.', 'poller_master' ); ?></small>
		</label><br />
		
		<label>
			<?php _e('Results Denied Text:', 'poller_master');?> <input type="text" name="results_denied_text" class="poller_master_wide" value="<?php echo stripslashes( $results_denied_text ); ?>"><br />
			<small><?php _e( 'Input text which will appear if the user has no privilege to see the results.', 'poller_master' ); ?></small>
		</label><br />
		
		<label>
			<?php _e('Day Text:', 'poller_master');?> <input type="text" name="day_text" class="poller_master_wide" value="<?php echo stripslashes( $day_text ); ?>"><br />
			<small><?php _e( 'Input translation for the word "Day".', 'poller_master' ); ?></small>
		</label><br />
		
		<label>
			<?php _e('Days Text:', 'poller_master');?> <input type="text" name="days_text" class="poller_master_wide" value="<?php echo stripslashes( $days_text ); ?>"><br />
			<small><?php _e( 'Input translation for the word "Days".', 'poller_master' ); ?></small>
		</label><br />		
		
		<label>
			<?php _e('Hours Text:', 'poller_master');?> <input type="text" name="hours_text" class="poller_master_wide" value="<?php echo stripslashes( $hours_text ); ?>"><br />
			<small><?php _e( 'Input translation for the word "Hours".', 'poller_master' ); ?></small>
		</label><br />
		
		<label>
			<?php _e('Minutes Text:', 'poller_master');?> <input type="text" name="minutes_text" class="poller_master_wide" value="<?php echo stripslashes( $minutes_text ); ?>"><br />
			<small><?php _e( 'Input translation for the word "Minutes".', 'poller_master' ); ?></small>
		</label><br />

		<label>
			<?php _e('Seconds Text:', 'poller_master');?> <input type="text" name="seconds_text" class="poller_master_wide" value="<?php echo stripslashes( $seconds_text ); ?>"><br />
			<small><?php _e( 'Input translation for the word "Seconds".', 'poller_master' ); ?></small>
		</label><br />
		
		<label>			
			<?php _e('Enqueue bootstrap:', 'poller_master');?> 
			<select name="enqueue_bootstrap" >
				<option value="yes" <?php echo $enqueue_bootstrap == "yes" ? 'selected="selected"' : '' ?>><?php _e( 'Yes', 'poller_master' ) ?></option>
				<option value="no" <?php echo $enqueue_bootstrap == "no" ? 'selected="selected"' : '' ?>><?php _e( 'No', 'poller_master' ) ?></option>
			</select><br />
			<small><?php _e( 'Select <b>Yes</b> if you want for the pugin to include its bootstrap, or <b>No</b> if you will do it with your theme.', 'poller_master' ); ?></small>
		</label><br />
		
		<label>			
			<?php _e('Load Logs:', 'poller_master');?> 
			<select name="load_logs">
				<option value="10" <?php echo $load_logs == "10" ? 'selected="selected"' : '' ?>><?php _e( '10', 'poller_master' ) ?></option>
				<option value="20" <?php echo $load_logs == "20" ? 'selected="selected"' : '' ?>><?php _e( '20', 'poller_master' ) ?></option>
				<option value="50" <?php echo $load_logs == "50" ? 'selected="selected"' : '' ?>><?php _e( '50', 'poller_master' ) ?></option>
				<option value="100" <?php echo $load_logs == "100" ? 'selected="selected"' : '' ?>><?php _e( '100', 'poller_master' ) ?></option>
				<option value="500" <?php echo $load_logs == "500" ? 'selected="selected"' : '' ?>><?php _e( '500', 'poller_master' ) ?></option>
				<option value="1000" <?php echo $load_logs == "1000" ? 'selected="selected"' : '' ?>><?php _e( '1000', 'poller_master' ) ?></option>
				<option value="5000" <?php echo $load_logs == "5000" ? 'selected="selected"' : '' ?>><?php _e( '5000', 'poller_master' ) ?></option>
				<option value="10000" <?php echo $load_logs == "10000" ? 'selected="selected"' : '' ?>><?php _e( '10000', 'poller_master' ) ?></option>
				<option value="50000" <?php echo $load_logs == "50000" ? 'selected="selected"' : '' ?>><?php _e( '50000', 'poller_master' ) ?></option>
				<option value="100000" <?php echo $load_logs == "100000" ? 'selected="selected"' : '' ?>><?php _e( '100000', 'poller_master' ) ?></option>
				<option value="all" <?php echo $load_logs == "all" ? 'selected="selected"' : '' ?>><?php _e( 'All', 'poller_master' ) ?></option>
			</select><br />
			<small><?php _e( 'Select how many logs to load in the stats window per load more click and on initial state.', 'poller_master' ); ?></small>
		</label><br />

		<div class="label">
			<label><?php _e('Stats Chart Colors:', 'poller_master');?>  <br /></label>			
			<input type="hidden" class="multiple_colors" name="chart_colors" value="<?php echo $chart_colors; ?>">
			
			<div class="color_pickers"></div>
			
			<a href="javascript:;" class="add_color button action"><?php _e( 'Add A New Color', 'poller_master' ); ?></a><br />
			<small><?php _e( 'Manage colors for the stats chart. If there is no colors default one will be used. If there is more answers than the colors, answers after the end of the colors array will all have same color.', 'poller_master' ); ?></small>
		</div><br />
	</div>
	
	<div id="poll_templates_section" class="section hidden">
		<h3 class="label-info"><?php _e( 'Poll Templates Settings', 'poller_master' ); ?></h3>
		
		<div id="sub_navigation">
			<?php foreach( $templates as $template_id => $template_data ): ?>
				<a href="javascript:;" data-section="#<?php echo $template_id; ?>" data-pickerize="yes"><?php echo $template_data['template_name'] ?></a>
			<?php endforeach; ?>
		</div>
		
		<?php 
			extract( $poll->default_template );
			foreach( $templates as $template_id => $template_data ): ?>
			<?php $template_data = array_merge( $poll->default_template, $template_data ); ?>
			<div id="<?php echo $template_id ?>" class="template hidden">
				<input type="hidden" value="0" class="pickerized">
				<h4 class="label-info"><?php _e( 'Main Poll Options', 'poller_master' ); ?></h4>
				<?php $template_id = 'templates['.$template_id.']'; ?>				
				<!-- poll options -->
				<label>
					<?php _e('Template Name:', 'poller_master');?> <br />
					<input type="text" name="<?php echo $template_id; ?>[template_name]" value="<?php echo $template_data['template_name']; ?>"><br />
					<small><?php _e( 'Input template name.', 'poller_master' ); ?></small>
				</label><br />				
				
				<label>
					<?php _e('Background Color:', 'poller_master');?> <br />
					<input type="text" class="poller_master_colorpicker" name="<?php echo $template_id; ?>[poll_bg_color]" data-default_value="<?php echo $poll_bg_color ?>" value="<?php echo $template_data['poll_bg_color']; ?>"><br />
					<small><?php _e( 'Select background color for the poll.', 'poller_master' ); ?></small>
				</label><br />		
				
				<label>
					<?php _e('Poll Border Color:', 'poller_master');?> <br />
					<input type="text" class="poller_master_colorpicker" name="<?php echo $template_id; ?>[poll_border_color]" data-default_value="<?php echo $poll_border_color ?>" value="<?php echo $template_data['poll_border_color']; ?>"><br />
					<small><?php _e( 'Select border color for the poll.', 'poller_master' ); ?></small>
				</label><br />
				
				<label>
					<?php _e('Poll Border Radius:', 'poller_master');?> <br />
					<input type="text" name="<?php echo $template_id; ?>[poll_border_radius]" data-default_value="<?php echo $poll_border_radius ?>" value="<?php echo $template_data['poll_border_radius']; ?>"><br />
					<small><?php _e( 'Set border radius for the poll. You can use any form.', 'poller_master' ); ?></small>
				</label><br />
				
				<label>
					<?php _e('Answer Font Color:', 'poller_master');?>  <br />
					<input type="text" class="poller_master_colorpicker" name="<?php echo $template_id; ?>[answers_color]" data-default_value="<?php echo $answers_color ?>" value="<?php echo $template_data['answers_color']; ?>"> <br />
					<small><?php _e( 'Select font color for the poll answers.', 'poller_master' ); ?></small>
				</label><br />
				
				<label>
					<?php _e('Answer Checkboxes / Radios: Style', 'poller_master');?>  <br />
					<select name="<?php echo $template_id; ?>[check_radio_style]" class="radio_checkbox_style" data-default_value="<?php echo $check_radio_style ?>" >
						<option value="minimal" <?php echo $template_data['check_radio_style'] == "minimal" ? 'selected="selected"' : ''; ?>><?php _e( 'Minimal skin', 'poller_master' ) ?></option>
						<option value="square" <?php echo $template_data['check_radio_style'] == "square" ? 'selected="selected"' : ''; ?>><?php _e( 'Square skin', 'poller_master' ) ?></option>
						<option value="flat" <?php echo $template_data['check_radio_style'] == "flat" ? 'selected="selected"' : ''; ?>><?php _e( 'Flat skin', 'poller_master' ) ?></option>
						<option value="line" <?php echo $template_data['check_radio_style'] == "line" ? 'selected="selected"' : ''; ?>><?php _e( 'Line skin', 'poller_master' ) ?></option>
						<option value="polaris" <?php echo $template_data['check_radio_style'] == "polaris" ? 'selected="selected"' : ''; ?>><?php _e( 'Polaris skin', 'poller_master' ) ?></option>
						<option value="futurico" <?php echo $template_data['check_radio_style'] == "futurico" ? 'selected="selected"' : ''; ?>><?php _e( 'Futurico skin', 'poller_master' ) ?></option>
					</select> <br />
					<small><?php _e( 'Select checkbox / radio style.', 'poller_master' ); ?></small>
				</label>
				<br />
				<img src="<?php echo $poll->get_scheme_preview( $template_data['check_radio_style'], $template_data['check_radio_scheme'] ); ?>" class="answer_scheme_preview"><br />
				<small><?php _e( 'Checkbox / radio preview.', 'poller_master' ); ?></small>
				
				<div id="answer_scheme" <?php echo ( $template_data['check_radio_style'] == 'futurico' || $template_data['check_radio_style'] == 'polaris' ) ? 'style="display: none;"' : ''; ?>>					
					<input type="hidden" name="<?php echo $template_id; ?>[check_radio_scheme]" class="check_radio_scheme" data-default_value="<?php echo $check_radio_scheme ?>" value="<?php echo $template_data['check_radio_scheme']; ?>">				
					<div class="colors clear">
						<label><?php _e( 'Checkboxes / Radios Color:', 'poller_master' ); ?></label>
							<ul>
							<li class="black <?php echo $template_data['check_radio_scheme'] == "black" ? "active" : ""; ?>" title="<?php _e( 'Black', 'poller_master' ); ?>"></li>
							<li class="red <?php echo $template_data['check_radio_scheme'] == "red" ? "active" : ""; ?>" title="<?php _e( 'Red', 'poller_master' ); ?>"></li>
							<li class="green <?php echo $template_data['check_radio_scheme'] == "green" ? "active" : ""; ?>" title="<?php _e( 'Green', 'poller_master' ); ?>"></li>
							<li class="blue <?php echo $template_data['check_radio_scheme'] == "blue" ? "active" : ""; ?>" title="<?php _e( 'Blue', 'poller_master' ); ?>"></li>
							<li class="aero <?php echo $template_data['check_radio_scheme'] == "aero" ? "active" : ""; ?>" title="<?php _e( 'Aero', 'poller_master' ); ?>"></li>
							<li class="grey <?php echo $template_data['check_radio_scheme'] == "grey" ? "active" : ""; ?>" title="<?php _e( 'Grey', 'poller_master' ); ?>"></li>
							<li class="orange <?php echo $template_data['check_radio_scheme'] == "orange" ? "active" : ""; ?>" title="<?php _e( 'Orange', 'poller_master' ); ?>"></li>
							<li class="yellow <?php echo $template_data['check_radio_scheme'] == "yellow" ? "active" : ""; ?>" title="<?php _e( 'Yellow', 'poller_master' ); ?>"></li>
							<li class="pink <?php echo $template_data['check_radio_scheme'] == "pink" ? "active" : ""; ?>" title="<?php _e( 'Pink', 'poller_master' ); ?>"></li>
							<li class="purple <?php echo $template_data['check_radio_scheme'] == "purple" ? "active" : ""; ?>" title="<?php _e( 'Purple', 'poller_master' ); ?>"></li>
						</ul>
					</div><br />
					<small><?php _e( 'Select color schema for the checkboxes and radios.', 'poller_master' ); ?></small>
				</div><br />
				
				<label>
					<?php _e('Answer Font Size:', 'poller_master');?>  <br />
					<input type="text" name="<?php echo $template_id; ?>[answers_font_size]" data-default_value="<?php echo $answers_font_size ?>" value="<?php echo $template_data['answers_font_size']; ?>"> <br />
					<small><?php _e( 'Input font size for the poll answers in <b>px</b> (pixels).', 'poller_master' ); ?></small>
				</label><br />
								
				<label>
					<?php _e('Answer Line Height:', 'poller_master');?>  <br />
					<input type="text" name="<?php echo $template_id; ?>[answers_line_height]" data-default_value="<?php echo $answers_line_height ?>" value="<?php echo $template_data['answers_line_height']; ?>"> <br />
					<small><?php _e( 'Input line height for the poll answers in <b>px</b> (pixels).', 'poller_master' ); ?></small>
				</label><br />
				
				<label>
					<?php _e('Answer Font Weight:', 'poller_master');?>  <br />
					<select name="<?php echo $template_id; ?>[answers_font_weight]" data-default_value="<?php echo $answers_font_weight ?>">
						<option value="normal" <?php echo $template_data['answers_font_weight'] == 'normal' ? 'selected="selected"' : ''; ?>><?php _e( 'Normal', 'poller_master' ); ?></option>
						<option value="bold" <?php echo $template_data['answers_font_weight'] == 'normal' ? 'selected="selected"' : ''; ?>><?php _e( 'Bold', 'poller_master' ); ?></option>
					</select><br />
					<small><?php _e( 'Select font weight.', 'poller_master' ); ?></small>
				</label><br />	
				
				<label>
					<?php _e('In Easing:', 'poller_master');?>  <br />
					<select name="<?php echo $template_id; ?>[in_easing]" data-default_value="<?php echo $in_easing ?>" >
						<?php echo poller_master_form_select( 'easing', $template_data['in_easing'] ); ?>
					</select><br />
					<small><?php _e( 'Select easing for displaying the elements. See them in action <b>http://api.jqueryui.com/easings/</b>', 'poller_master' ); ?></small>
				</label><br />
				
				<label>
					<?php _e('In Effect:', 'poller_master');?>  <br />
					<select name="<?php echo $template_id; ?>[in_effect]" data-default_value="<?php echo $in_effect ?>" >
						<?php echo poller_master_form_select( 'effect', $template_data['in_effect'] ); ?>
					</select><br />
					<small><?php _e( 'Select effect for displaying the elements.', 'poller_master' ); ?></small>
				</label><br />
				
				<label>
					<?php _e('Out Easing:', 'poller_master');?>  <br />
					<select name="<?php echo $template_id; ?>[out_easing]" data-default_value="<?php echo $out_easing ?>" >
						<?php echo poller_master_form_select( 'easing', $template_data['out_easing'] ); ?>
					</select><br />
					<small><?php _e( 'Select easing for hidding the elements. See them in action <b>http://api.jqueryui.com/easings/</b>', 'poller_master' ); ?></small>
				</label><br />	

				<label>
					<?php _e('Out Effect:', 'poller_master');?>  <br />
					<select name="<?php echo $template_id; ?>[out_effect]" data-default_value="<?php echo $out_effect ?>" >
						<?php echo poller_master_form_select( 'effect', $template_data['out_effect'] ); ?>
					</select><br />
					<small><?php _e( 'Select effect for hiddig the elements.', 'poller_master' ); ?></small>
				</label><br />
				
				<label>
					<?php _e('Loader Image:', 'underconstruction');?><br />
				</label>
				<div class="loader_data">
					<div class="loader">
						<?php if( $template_data['loader'] !== "" ): ?>
							<img src="<?php echo $poll->get_image_src( $template_data['loader'] ); ?>"><a href="javascript:;" class="delete_loader">[X]</a>
						<?php endif; ?>
					</div>
					<input type="hidden" name="<?php echo $template_id; ?>[loader]" data-default_value="<?php echo $loader ?>" value="<?php echo $template_data['loader']; ?>">
					<input type="button" class="button action add_loader" value="<?php _e( 'Add loader', 'poller_master' ); ?>"><br />
					<small><?php _e( 'Select loader image.', 'poller_master' ); ?></small>
					<br />
				</div>				
				<!-- .poll options -->
				
				<h4 class="label-info"><?php _e( 'Main Poll Counter Options', 'poller_master' ); ?></h4>
				<!-- poll counter options -->
				<label>
					<?php _e('Show Text:', 'poller_master');?>  <br />
					<select name="<?php echo $template_id; ?>[counter_poll_show_text]" data-default_value="<?php echo $counter_poll_show_text ?>">
						<option value="yes" <?php echo $template_data['counter_poll_show_text'] == 'yes' ? 'selected="selected"' : ''; ?>><?php _e( 'Yes', 'poller_master' ); ?></option>
						<option value="no" <?php echo $template_data['counter_poll_show_text'] == 'no' ? 'selected="selected"' : ''; ?>><?php _e( 'No', 'poller_master' ); ?></option>
					</select><br />
					<small><?php _e( 'Select yes if you wish to display Days, Hours Minutes and Seconds text.', 'poller_master' ); ?></small>
				</label><br />				
				
				<label>
					<?php _e('Background Color:', 'poller_master');?>  <br />
					<input type="text" class="poller_master_colorpicker" name="<?php echo $template_id; ?>[counter_poll_bg_color]" data-default_value="<?php echo $counter_poll_bg_color ?>" value="<?php echo $template_data['counter_poll_bg_color']; ?>"> <br />
					<small><?php _e( 'Select background color for the poll counter.', 'poller_master' ); ?></small>
				</label><br />
				
				<label>
					<?php _e('Border Color:', 'poller_master');?>  <br />
					<input type="text" class="poller_master_colorpicker" name="<?php echo $template_id; ?>[counter_poll_border_color]" data-default_value="<?php echo $counter_poll_border_color ?>" value="<?php echo $template_data['counter_poll_border_color']; ?>"> <br />
					<small><?php _e( 'Select background color for the poll counter.', 'poller_master' ); ?></small>
				</label><br />	

				<label>
					<?php _e('Hover Background Color:', 'poller_master');?>  <br />
					<input type="text" class="poller_master_colorpicker" name="<?php echo $template_id; ?>[counter_poll_bg_hvr_color]" data-default_value="<?php echo $counter_poll_bg_hvr_color ?>" value="<?php echo $template_data['counter_poll_bg_hvr_color']; ?>"> <br />
					<small><?php _e( 'Select background color for the poll counter on hover.', 'poller_master' ); ?></small>
				</label><br />
				
				<label>
					<?php _e('Hover Border Color:', 'poller_master');?>  <br />
					<input type="text" class="poller_master_colorpicker" name="<?php echo $template_id; ?>[counter_poll_border_hvr_color]" data-default_value="<?php echo $counter_poll_border_hvr_color ?>" value="<?php echo $template_data['counter_poll_border_hvr_color']; ?>"> <br />
					<small><?php _e( 'Select background color for the poll counter on hover.', 'poller_master' ); ?></small>
				</label><br />			

				<label>
					<?php _e('Font Color:', 'poller_master');?>  <br />
					<input type="text" class="poller_master_colorpicker" name="<?php echo $template_id; ?>[counter_poll_font_color]" data-default_value="<?php echo $counter_poll_font_color ?>" value="<?php echo $template_data['counter_poll_font_color']; ?>"> <br />
					<small><?php _e( 'Select font color for the poll counter.', 'poller_master' ); ?></small>
				</label><br />
				
				<label>
					<?php _e('Font:', 'poller_master');?>  <br />
					<select name="<?php echo $template_id; ?>[counter_poll_font]" data-default_value="<?php echo $counter_poll_font ?>" >
						<?php echo poller_master_form_select( 'font', $template_data['counter_poll_font'] ); ?>
					</select><br />
					<small><?php _e( 'Select font for the poll counter.', 'poller_master' ); ?></small>
				</label><br />
				
				<label>
					<?php _e('Font Size:', 'poller_master');?>  <br />
					<input type="text" name="<?php echo $template_id; ?>[counter_poll_font_size]" data-default_value="<?php echo $counter_poll_font_size ?>" value="<?php echo $template_data['counter_poll_font_size']; ?>"> <br />
					<small><?php _e( 'Input font size for the counter numbers in <b>px</b> (pixels).', 'poller_master' ); ?></small>
				</label><br />
				
				<label>
					<?php _e('Font Line Height:', 'poller_master');?>  <br />
					<input type="text" name="<?php echo $template_id; ?>[counter_poll_line_height]" data-default_value="<?php echo $counter_poll_line_height ?>" value="<?php echo $template_data['counter_poll_line_height']; ?>"> <br />
					<small><?php _e( 'Input font line height for the counter numbers in <b>px</b> (pixels).', 'poller_master' ); ?></small>
				</label><br />
				
				<label>
					<?php _e('Border Radius:', 'poller_master');?>  <br />
					<input type="text" name="<?php echo $template_id; ?>[counter_poll_border_radius]" data-default_value="<?php echo $counter_poll_border_radius ?>" value="<?php echo $template_data['counter_poll_border_radius']; ?>"> <br />
					<small><?php _e( 'Input border radius for the counter boxes. you can use any form.', 'poller_master' ); ?></small>
				</label><br />
				<!-- .poll counter options -->
						
				<!-- poll vote button -->
				<h4 class="label-info"><?php _e( 'Vote Button Options', 'poller_master' ); ?></h4>
				<label>
					<?php _e('Background Color:', 'poller_master');?>  <br />
					<input type="text" class="poller_master_colorpicker" name="<?php echo $template_id; ?>[vote_btn_bg_color]" data-default_value="<?php echo $vote_btn_bg_color ?>" value="<?php echo $template_data['vote_btn_bg_color']; ?>"> <br />
					<small><?php _e( 'Select background color for the vote button.', 'poller_master' ); ?></small>
				</label><br />
				
				<label>
					<?php _e('Border Color:', 'poller_master');?>  <br />
					<input type="text" class="poller_master_colorpicker" name="<?php echo $template_id; ?>[vote_btn_border_color]" data-default_value="<?php echo $vote_btn_border_color ?>" value="<?php echo $template_data['vote_btn_border_color']; ?>"> <br />
					<small><?php _e( 'Select border color for the vote button.', 'poller_master' ); ?></small>
				</label><br />

				<label>
					<?php _e('Hover Background Color:', 'poller_master');?>  <br />
					<input type="text" class="poller_master_colorpicker" name="<?php echo $template_id; ?>[vote_btn_bg_hvr_color]" data-default_value="<?php echo $vote_btn_bg_hvr_color ?>" value="<?php echo $template_data['vote_btn_bg_hvr_color']; ?>"> <br />
					<small><?php _e( 'Select background color for the vote button on hover.', 'poller_master' ); ?></small>
				</label><br />
				
				<label>
					<?php _e('Hover Border Color:', 'poller_master');?>  <br />
					<input type="text" class="poller_master_colorpicker" name="<?php echo $template_id; ?>[vote_btn_hvr_border_color]" data-default_value="<?php echo $vote_btn_hvr_border_color ?>" value="<?php echo $template_data['vote_btn_hvr_border_color']; ?>"> <br />
					<small><?php _e( 'Select border color for the vote button on hover.', 'poller_master' ); ?></small>
				</label><br />
				
				<label>
					<?php _e('Font Color:', 'poller_master');?>  <br />
					<input type="text" class="poller_master_colorpicker" name="<?php echo $template_id; ?>[vote_btn_font_color]" data-default_value="<?php echo $vote_btn_font_color ?>" value="<?php echo $template_data['vote_btn_font_color']; ?>"> <br />
					<small><?php _e( 'Select font color for the vote button.', 'poller_master' ); ?></small>
				</label><br />
				
				<label>
					<?php _e('Font Weight:', 'poller_master');?>  <br />
					<select name="<?php echo $template_id; ?>[vote_btn_font_weight]" data-default_value="<?php echo $vote_btn_font_weight ?>">
						<option value="normal" <?php echo $template_data['vote_btn_font_weight'] == 'normal' ? 'selected="selected"' : ''; ?>><?php _e( 'Normal', 'poller_master' ); ?></option>
						<option value="bold" <?php echo $template_data['vote_btn_font_weight'] == 'bold' ? 'selected="selected"' : ''; ?>><?php _e( 'Bold', 'poller_master' ); ?></option>
					</select><br />
					<small><?php _e( 'Select font weight.', 'poller_master' ); ?></small>
				</label><br />
				
				<label>
					<?php _e('Border Radius:', 'poller_master');?>  <br />
					<input type="text" name="<?php echo $template_id; ?>[vote_btn_border_radius]" data-default_value="<?php echo $vote_btn_border_radius ?>" value="<?php echo $template_data['vote_btn_border_radius']; ?>"> <br />
					<small><?php _e( 'Input border radius for the vote button. You can use any form.', 'poller_master' ); ?></small>
				</label><br />
				
				<label>
					<?php _e('Width:', 'poller_master');?>  <br />
					<input type="text" name="<?php echo $template_id; ?>[vote_btn_width]" data-default_value="<?php echo $vote_btn_width ?>" value="<?php echo $template_data['vote_btn_width']; ?>"> <br />
					<small><?php _e( 'Input width for the vote button. You can use any form.', 'poller_master' ); ?></small>
				</label><br />				
				<!-- .poll vote button -->

				<!-- poll result button -->
				<h4 class="label-info"><?php _e( 'Result Button Options', 'poller_master' ); ?></h4>
				<label>
					<?php _e('Background Color:', 'poller_master');?>  <br />
					<input type="text" class="poller_master_colorpicker" name="<?php echo $template_id; ?>[result_btn_bg_color]" data-default_value="<?php echo $result_btn_bg_color ?>" value="<?php echo $template_data['result_btn_bg_color']; ?>"> <br />
					<small><?php _e( 'Select background color for the result button.', 'poller_master' ); ?></small>
				</label><br />
				
				<label>
					<?php _e('Border Color:', 'poller_master');?>  <br />
					<input type="text" class="poller_master_colorpicker" name="<?php echo $template_id; ?>[result_btn_border_color]" data-default_value="<?php echo $result_btn_border_color ?>" value="<?php echo $template_data['result_btn_border_color']; ?>" <br />
					<small><?php _e( 'Select border color for the result button.', 'poller_master' ); ?></small>
				</label><br />

				<label>
					<?php _e('Hover Background Color:', 'poller_master');?>  <br />
					<input type="text" class="poller_master_colorpicker" name="<?php echo $template_id; ?>[result_btn_bg_hvr_color]" data-default_value="<?php echo $result_btn_bg_hvr_color ?>" value="<?php echo $template_data['result_btn_bg_hvr_color']; ?>"> <br />
					<small><?php _e( 'Select background color for the result button on hover.', 'poller_master' ); ?></small>
				</label><br />
				
				<label>
					<?php _e('Hover Border Color:', 'poller_master');?>  <br />
					<input type="text" class="poller_master_colorpicker" name="<?php echo $template_id; ?>[result_btn_hvr_border_color]" data-default_value="<?php echo $result_btn_hvr_border_color ?>" value="<?php echo $template_data['result_btn_hvr_border_color']; ?>"> <br />
					<small><?php _e( 'Select border color for the result button on hover.', 'poller_master' ); ?></small>
				</label><br />
				
				<label>
					<?php _e('Font Color:', 'poller_master');?>  <br />
					<input type="text" class="poller_master_colorpicker" name="<?php echo $template_id; ?>[result_btn_font_color]" data-default_value="<?php echo $result_btn_font_color ?>" value="<?php echo $template_data['result_btn_font_color']; ?>"> <br />
					<small><?php _e( 'Select font color for the result button.', 'poller_master' ); ?></small>
				</label><br />
				
				<label>
					<?php _e('Font Weight:', 'poller_master');?>  <br />
					<select name="<?php echo $template_id; ?>[result_btn_font_weight]" data-default_value="<?php echo $result_btn_font_weight ?>">
						<option value="normal" <?php echo $template_data['result_btn_font_weight'] == 'normal' ? 'selected="selected"' : ''; ?>><?php _e( 'Normal', 'poller_master' ); ?></option>
						<option value="bold" <?php echo $template_data['result_btn_font_weight'] == 'bold' ? 'selected="selected"' : ''; ?>><?php _e( 'Bold', 'poller_master' ); ?></option>
					</select><br />
					<small><?php _e( 'Select font weight.', 'poller_master' ); ?></small>
				</label><br />				
				
				<label>
					<?php _e('Border Radius:', 'poller_master');?>  <br />
					<input type="text" name="<?php echo $template_id; ?>[result_btn_border_radius]" data-default_value="<?php echo $result_btn_border_radius ?>" value="<?php echo $template_data['result_btn_border_radius']; ?>"> <br />
					<small><?php _e( 'Input border radius for the result button. You can use any form.', 'poller_master' ); ?></small>
				</label><br />
				
				<label>
					<?php _e('Width:', 'poller_master');?>  <br />
					<input type="text" name="<?php echo $template_id; ?>[result_btn_width]" data-default_value="<?php echo $result_btn_width ?>" value="<?php echo $template_data['result_btn_width']; ?>"> <br />
					<small><?php _e( 'Input width for the result button. You can use any form.', 'poller_master' ); ?></small>
				</label><br />				
				<!-- .poll result button -->
				
				<!-- .vote options -->
				<h4 class="label-info"><?php _e( 'Main Vote Counter Options', 'poller_master' ); ?></h4>
				<!-- vote counter options -->
				<label>
					<?php _e('Show Text:', 'poller_master');?>  <br />
					<select name="<?php echo $template_id; ?>[counter_vote_show_text]" data-default_value="<?php echo $counter_vote_show_text ?>">
						<option value="yes" <?php echo $template_data['counter_vote_show_text'] == 'yes' ? 'selected="selected"' : ''; ?>><?php _e( 'Yes', 'poller_master' ); ?></option>
						<option value="no" <?php echo $template_data['counter_vote_show_text'] == 'no' ? 'selected="selected"' : ''; ?>><?php _e( 'No', 'poller_master' ); ?></option>
					</select><br />
					<small><?php _e( 'Select yes if you wish to display Days, Hours Minutes nad Seconds text.', 'poller_master' ); ?></small>
				</label><br />
				
				<label>
					<?php _e('Background Color:', 'poller_master');?>  <br />
					<input type="text" class="poller_master_colorpicker" name="<?php echo $template_id; ?>[counter_vote_bg_color]" data-default_value="<?php echo $counter_vote_bg_color ?>" value="<?php echo $template_data['counter_vote_bg_color']; ?>"> <br />
					<small><?php _e( 'Select background color for the vote counter.', 'poller_master' ); ?></small>
				</label><br />
				
				<label>
					<?php _e('Border Color:', 'poller_master');?>  <br />
					<input type="text" class="poller_master_colorpicker" name="<?php echo $template_id; ?>[counter_vote_border_color]" data-default_value="<?php echo $counter_vote_border_color ?>" value="<?php echo $template_data['counter_vote_border_color']; ?>"> <br />
					<small><?php _e( 'Select background color for the vote counter.', 'poller_master' ); ?></small>
				</label><br />	

				<label>
					<?php _e('Hover Background Color:', 'poller_master');?>  <br />
					<input type="text" class="poller_master_colorpicker" name="<?php echo $template_id; ?>[counter_vote_bg_hvr_color]" data-default_value="<?php echo $counter_vote_bg_hvr_color ?>" value="<?php echo $template_data['counter_vote_bg_hvr_color']; ?>"> <br />
					<small><?php _e( 'Select background color for the vote counter on hover.', 'poller_master' ); ?></small>
				</label><br />
				
				<label>
					<?php _e('Hover Border Color:', 'poller_master');?>  <br />
					<input type="text" class="poller_master_colorpicker" name="<?php echo $template_id; ?>[counter_vote_border_hvr_color]" data-default_value="<?php echo $counter_vote_border_hvr_color ?>" value="<?php echo $template_data['counter_vote_border_hvr_color']; ?>"> <br />
					<small><?php _e( 'Select background color for the vote counter on hover.', 'poller_master' ); ?></small>
				</label><br />

				<label>
					<?php _e('Font Color:', 'poller_master');?>  <br />
					<input type="text" class="poller_master_colorpicker" name="<?php echo $template_id; ?>[counter_vote_font_color]" data-default_value="<?php echo $counter_vote_font_color ?>" value="<?php echo $template_data['counter_vote_font_color']; ?>"> <br />
					<small><?php _e( 'Select font color for the vote counter.', 'poller_master' ); ?></small>
				</label><br />
				
				<label>
					<?php _e('Font:', 'poller_master');?>  <br />
					<select name="<?php echo $template_id; ?>[counter_vote_font]" data-default_value="<?php echo $counter_vote_font ?>" >
						<?php echo poller_master_form_select( 'font', $template_data['counter_vote_font'] ); ?>
					</select><br />
					<small><?php _e( 'Select font for the vote counter.', 'poller_master' ); ?></small>
				</label><br />
				
				<label>
					<?php _e('Font Size:', 'poller_master');?>  <br />
					<input type="text" name="<?php echo $template_id; ?>[counter_vote_font_size]" data-default_value="<?php echo $counter_vote_font_size ?>" value="<?php echo $template_data['counter_vote_font_size']; ?>"> <br />
					<small><?php _e( 'Input font size for the counter numbers in <b>px</b> (pixels).', 'poller_master' ); ?></small>
				</label><br />
				
				<label>
					<?php _e('Font Line Height:', 'poller_master');?>  <br />
					<input type="text" name="<?php echo $template_id; ?>[counter_vote_line_height]" data-default_value="<?php echo $counter_vote_line_height ?>" value="<?php echo $template_data['counter_vote_line_height']; ?>"> <br />
					<small><?php _e( 'Input font line height for the counter numbers in <b>px</b> (pixels).', 'poller_master' ); ?></small>
				</label><br />
				
				<label>
					<?php _e('Border Radius:', 'poller_master');?>  <br />
					<input type="text" name="<?php echo $template_id; ?>[counter_vote_border_radius]" data-default_value="<?php echo $counter_vote_border_radius ?>" value="<?php echo $template_data['counter_vote_border_radius']; ?>"> <br />
					<small><?php _e( 'Input border radius for the vote countdown boxes. You can use any form.', 'poller_master' ); ?></small>
				</label><br />
				<!-- .vote counter options -->
				
				<!-- result box -->
				<h4 class="label-info"><?php _e( 'Results Box Options', 'poller_master' ); ?></h4>
				
				<label>
					<?php _e('Display Results As:', 'poller_master');?>  <br />
					<select name="<?php echo $template_id; ?>[answer_vote_results]" data-default_value="<?php echo $answer_vote_results ?>">
						<option value="both" <?php echo $template_data['answer_vote_results'] == 'both' ? 'selected="selected"' : ''; ?>><?php _e( 'Both', 'poller_master' ); ?></option>
						<option value="percentage_only" <?php echo $template_data['answer_vote_results'] == 'percentage_only' ? 'selected="selected"' : ''; ?>><?php _e( 'Percentage Only', 'poller_master' ); ?></option>
						<option value="votes_only" <?php echo $template_data['answer_vote_results'] == 'votes_only' ? 'selected="selected"' : ''; ?>><?php _e( 'Votes Only', 'poller_master' ); ?></option>						
					</select><br />
					<small><?php _e( 'Select how vote results should be displayed.', 'poller_master' ); ?></small>
				</label><br />
				
				<label>
					<?php _e('Background Color:', 'poller_master');?>  <br />
					<input type="text" class="poller_master_colorpicker" name="<?php echo $template_id; ?>[results_box_bg_color]" data-default_value="<?php echo $results_box_bg_color ?>" value="<?php echo $template_data['results_box_bg_color']; ?>"> <br />
					<small><?php _e( 'Select background color for the results box.', 'poller_master' ); ?></small>
				</label><br />
				
				<label>
					<?php _e('Font Size:', 'poller_master');?>  <br />
					<input type="text" name="<?php echo $template_id; ?>[results_box_font_size]" data-default_value="<?php echo $results_box_font_size ?>" value="<?php echo $template_data['results_box_font_size']; ?>"> <br />
					<small><?php _e( 'Input font size dor the results in <b>px</b> (pixels).', 'poller_master' ); ?></small>
				</label><br />				

				<label>
					<?php _e('Border Color:', 'poller_master');?>  <br />
					<input type="text" class="poller_master_colorpicker" name="<?php echo $template_id; ?>[results_box_border_color]" data-default_value="<?php echo $results_box_border_color ?>" value="<?php echo $template_data['results_box_border_color']; ?>"> <br />
					<small><?php _e( 'Select border color for the results box.', 'poller_master' ); ?></small>
				</label><br />
				
				<label>
					<?php _e('Font Color:', 'poller_master');?>  <br />
					<input type="text" class="poller_master_colorpicker" name="<?php echo $template_id; ?>[results_box_font_color]" data-default_value="<?php echo $results_box_font_color ?>" value="<?php echo $template_data['results_box_font_color']; ?>"> <br />
					<small><?php _e( 'Select font color for the results box.', 'poller_master' ); ?></small>
				</label><br />
				
				<label>
					<?php _e('Font Weight:', 'poller_master');?>  <br />
					<select name="<?php echo $template_id; ?>[results_box_font_weight]" data-default_value="<?php echo $results_box_font_weight ?>">
						<option value="normal" <?php echo $template_data['results_box_font_weight'] == 'normal' ? 'selected="selected"' : ''; ?>><?php _e( 'Normal', 'poller_master' ); ?></option>
						<option value="bold" <?php echo $template_data['results_box_font_weight'] == 'bold' ? 'selected="selected"' : ''; ?>><?php _e( 'Bold', 'poller_master' ); ?></option>
					</select><br />
					<small><?php _e( 'Select font weight.', 'poller_master' ); ?></small>
				</label><br />
				
				<label>
					<?php _e('Background Opacity:', 'poller_master');?>  <br />
					<input type="text" name="<?php echo $template_id; ?>[results_box_opacity]" data-default_value="<?php echo $results_box_opacity ?>" value="<?php echo $template_data['results_box_opacity']; ?>"> <br />
					<small><?php _e( 'Input results box background opaciti in range from 0 to 1.', 'poller_master' ); ?></small>
				</label><br />

				<label>
					<?php _e('Border Radius:', 'poller_master');?>  <br />
					<input type="text" name="<?php echo $template_id; ?>[results_box_border_radius]" data-default_value="<?php echo $results_box_border_radius ?>" value="<?php echo $template_data['results_box_border_radius']; ?>"> <br />
					<small><?php _e( 'Input border radius for the results box. You can use any form.', 'poller_master' ); ?></small>
				</label><br />				
				<!-- .result box -->
				
				<!-- progress bar -->
				<h4 class="label-info"><?php _e( 'Progress Bar Options', 'poller_master' ); ?></h4>
				<label>
					<?php _e('Style:', 'poller_master');?>  <br />
					<select name="<?php echo $template_id; ?>[progress_bar_style]" class="progress_bar_style" data-default_value="<?php echo $progress_bar_style ?>" >
						<option value="progress-striped active" <?php echo $template_data['progress_bar_style'] == "progress-striped active" ? 'selected="selected"' : ''; ?>><?php _e( 'Active Stripes', 'poller_master' ) ?></option>
						<option value="progress-striped" <?php echo $template_data['progress_bar_style'] == "progress-striped" ? 'selected="selected"' : ''; ?>><?php _e( 'Stripes', 'poller_master' ) ?></option>
						<option value="" <?php echo $template_data['progress_bar_style'] == "" ? 'selected="selected"' : ''; ?>><?php _e( 'No Stripes', 'poller_master' ) ?></option>
					</select><br />
					<small><?php _e( 'Select progress bar style.', 'poller_master' ); ?></small>
				</label>
				<div class="progress_bar_demo">
					<div class="progress <?php echo $template_data['progress_bar_style']; ?>">
					  <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
					  </div>
					</div>
				</div><br />	

				<label>
					<?php _e('Progress Bar Height:', 'poller_master');?>  <br />
					<input type="text" name="<?php echo $template_id; ?>[progress_bar_height]" data-default_value="<?php echo $progress_bar_height ?>" value="<?php echo $template_data['progress_bar_height']; ?>"> <br />
					<small><?php _e( 'Input height of the progress bar in <b>px</b> (pixels).', 'poller_master' ); ?></small>
				</label><br />
				
				<div class="label">
					<label><?php _e('Progress Bar Colors:', 'poller_master');?>  <br /></label>				
					<input type="hidden" class="multiple_colors" name="<?php echo $template_id; ?>[progress_bar_colors]" data-default_value="<?php echo $progress_bar_colors ?>" value="<?php echo $template_data['progress_bar_colors']; ?>">
					
					<div class="color_pickers"></div>
					
					<input type="button" value="<?php _e( 'Add A New Color', 'poller_master' ); ?>" class="add_color button action"><br />
					<small><?php _e( 'Manage progress bar colors.', 'poller_master' ); ?></small>
				</div><br />
				<!-- .progress bar -->
			
				<br />
				<input type="button" value="<?php _e( 'Reset Template', 'poller_master' ); ?>" class="reset_template button action">
			</div>
		<?php endforeach; ?>
	</div>
		
	<div id="actions">
		<?php wp_nonce_field('save_options','save_options_field'); ?>
		<br />
		<input type="submit" class="save_options button button-primary button-large" value="Save Options">
	</div>
</form>