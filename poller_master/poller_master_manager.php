<?php 

/* Exit if accessed directly */
if ( ! defined( 'ABSPATH' ) ) exit;

/* start wp editor always in visual mode since tinymce has some bug with setContent while wp editor is in text mode */
add_filter( 'wp_default_editor', create_function('', 'return "tinymce";') );

global $poll;
$poll->admin_scripts();
?>
<h2><?php _e( 'Poller Master Manager', 'poller_master' ); ?></h2>

<h3><?php _e( 'Created Polls', 'poller_master' ); ?></h3>
<table class="wp-list-table widefat fixed posts table_polls" cellspacing="0">
	<thead>
		<tr>
			<th class="manage-column column-title" style="width: 5%"><?php _e( 'ID', 'poller_master' ); ?></th>
			<th class="manage-column column-title" style="width: 22%"><?php _e( 'Question', 'poller_master' ); ?></th>
			<th class="manage-column column-title column-tags" style="width: 7%"><?php _e( 'Voters', 'poller_master' ); ?></th>
			<th class="manage-column column-title column-tags" style="width: 16%"><?php _e( 'Start Date', 'poller_master' ); ?></th>
			<th class="manage-column column-title column-tags" style="width: 16%"><?php _e( 'End Date', 'poller_master' ); ?></th>
			<th class="manage-column column-title status_title column-tags" style="width: 10%" data-open_text="<?php _e( 'Open', 'poller_master' ); ?>" data-closed_text="<?php _e( 'Closed', 'poller_master' ); ?>"><?php _e( 'Status', 'poller_master' ); ?></th>
			<th class="manage-column column-title" style="width: 25%"><?php _e( 'Action', 'poller_master' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php echo $poll->polls_table(); ?>
	</tbody>
</table>
<br />
<a href="javascript:;" class="button button-primary add_new_panel" data-modal_title="<?php _e( 'Add New Poll', 'poller_master' ); ?>"><?php _e( 'Add New', 'poller_master' ); ?></a>
<div id="poll" class="modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h2 class="modal_title"></h2>
				<a href="javascript:;" data-dismiss="modal" class="poller_master_modal_close btn btn-default pull-right">&times;</a>
				<div class="clear"></div>
			</div>
			
			 <div class="modal-body">
				<input type="hidden" class="poll_id" value="0">
				<div class="information"></div>
				<div class="left_side">
					<div class="field">
						<label><?php _e( 'Poll Name', 'poller_master' ); ?></label>
						<input type="text" class="name" value="0" data-error_message="<?php _e( 'Poll name can not be empty', 'poller_master' ); ?>">
						<small><?php _e( 'Input name of the poll which will help you to indentify each poll.', 'poller_master' ); ?></small>
					</div>				
				
					<div class="field">
						<label><?php _e( 'Question', 'poller_master' ); ?></label>
						<?php wp_editor( "", "question", array( 'editor_class' => 'question' ) ); ?>
						<small><?php _e( 'Input question for the poll.', 'poller_master' ); ?></small>
					</div>
					
					<div class="field">
						<div class="answers" data-error_message="<?php _e( 'You must add at least one answer.', 'poller_master' ); ?>"></div>
						<div class="hidden answer_template">
							<div class="answer" data-answer_id="[x]">
								<label><?php _e( 'Answer', 'poller_master' ); ?> <div class="answer_num">[x+1]</div></label>
								<input type="text" value="">
								<a href="javascript:;" class="remove_answer button action"><?php _e( 'X', 'poller_master' ); ?></a>
							</div>
						</div>
						<br />
						<a href="javascript:;" class="add_answer add_answer button button-primary"><?php _e( 'Add New Answer', 'poller_master' ); ?></a>					
					</div>
				</div>
				
				<div class="right_side">
					<div class="field">
						<label><?php _e( 'Multiple Answers', 'poller_master' ); ?></label>
						<select class="multiple">
							<option value="no"><?php _e( 'No', 'poller_master' ); ?></option>
							<option value="yes"><?php _e( 'Yes', 'poller_master' ); ?></option>
						</select>
						<small><?php _e( 'Check this box if the users can select multiple votes.', 'poller_master' ); ?></small>
					</div>
					
					<div class="field">
						<label><?php _e( 'Poll Template', 'poller_master' ); ?></label>
						<select class="template">
							<?php echo $poll->form_templates_select(); ?>
						</select>
						<small><?php _e( 'Select template for this poll.', 'poller_master' ); ?></small>
					</div>
					
					<div class="field">
						<label><?php _e( 'Vote Frequency', 'poller_master' ); ?></label>
						<input type="text" class="frequency" value="0" data-default="0">
						<small><?php _e( 'Input frequency of the voting in days. 0 means that only one vote is allowed, and -1 that users can vote again and again.', 'poller_master' ); ?></small>
					</div>
					
					<div class="field">
						<label><?php _e( 'Who Can Vote', 'poller_master' ); ?></label>
						<select class="voters">
							<option value="all"><?php _e( 'All Users', 'poller_master' ); ?></option>
							<option value="registered"><?php _e( 'Only Registered Users', 'poller_master' ); ?></option>
							<option value="guests"><?php _e( 'Only Guests', 'poller_master' ); ?></option>
						</select>
						<small><?php _e( 'Select who have acces to vote.', 'poller_master' ); ?></small>
					</div>

					<div class="field">
						<label><?php _e( 'Start Date', 'poller_master' ); ?></label>
						<input type="text" class="start_date" value="">
						<small><?php _e( 'Input the date and time when the poll will be available. Blank means available after the creation.', 'poller_master' ); ?></small>
					</div>
					
					<div class="field">
						<label><?php _e( 'End Date', 'poller_master' ); ?></label>
						<input type="text" class="end_date" value="">
						<small><?php _e( 'Input the date and time from when the poll will be closed. Blank means that it is always available.', 'poller_master' ); ?></small>
					</div>

					<div class="field">
						<label><?php _e( 'Show Countdown [If there is end date set]', 'poller_master' ); ?></label>
						<select class="countdown">				
							<option value="1"><?php _e( 'Yes', 'poller_master' ); ?></option>
							<option value="0"><?php _e( 'No', 'poller_master' ); ?></option>
						</select>
						<small><?php _e( 'Show countdown until poll ends.', 'poller_master' ); ?></small>
					</div>
					
					<div class="field">
						<label><?php _e( 'Poll Status', 'poller_master' ); ?></label>
						<select class="status">				
							<option value="1"><?php _e( 'Open', 'poller_master' ); ?></option>
							<option value="0"><?php _e( 'Closed', 'poller_master' ); ?></option>
						</select>
						<small><?php _e( 'Set poll status to active or closed. This has advanatage over start / end date.', 'poller_master' ); ?></small>
					</div>
					
					<div class="field">
						<label><?php _e( 'Zero Vote', 'poller_master' ); ?></label>
						<select class="zero_vote">				
							<option value="1"><?php _e( 'Yes', 'poller_master' ); ?></option>
							<option value="0"><?php _e( 'No', 'poller_master' ); ?></option>
						</select>
						<small><?php _e( 'If this is set to yes than the users will be able to see the resulta even if they didn\'t voted.', 'poller_master' ); ?></small>
					</div>
					
					<div class="field">
						<a href="javascript:;" class="button button-primary add_new_poll" data-ready="<?php _e( 'Save Poll', 'poller_master' ); ?>" data-sending="<?php _e( 'Building, please wait...', 'poller_master' ); ?>"><?php _e( 'Save Poll', 'poller_master' ); ?></a>
					</div>			
				</div>
				<div class="clear"></div>
			</div>
			<div class="modal-footer">
				<a href="javascript:;" data-dismiss="modal" class="poller_master_modal_close btn btn-default">&times;</a>
			</div>	
		</div>
	</div>
</div>


<div id="stats" class="modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<a href="javascript:;" data-dismiss="modal" class="poller_master_modal_close btn btn-default pull-right">&times;</a>
				<div class="clear"></div>
			</div>
			<div class="modal-body">
				<div class="stats_chart" data-no_data_text="<?php _e( 'No Chart Data Available', 'poller_master' ); ?>" data-answer_text="<?php _e( 'Answer', 'poller_master' ) ?>">
					<div class="chart_item">
						<h3><?php _e( 'Summary Chart', 'poller_master' ); ?></h3>
						<div id="summary_chart_data"></div>
						<div id="summary_chart"></div>
					</div>
					<div class="chart_item">
						<h3><?php _e( 'Registered Users Chart', 'poller_master' ); ?></h3>
						<div id="registered_chart_data"></div>
						<div id="registered_chart"></div>
					</div>
					<div class="chart_item">
						<h3><?php _e( 'Guests Chart', 'poller_master' ); ?></h3>
						<div id="guests_chart_data"></div>
						<div id="guests_chart"></div>
					</div>
					
					<div class="stats_info">
						<h4 class="stats_question"></h4>
						<div class="stats_answers"></div>
					</div>
					
					<h3><?php _e( 'Summary Logs', 'poller_master' ); ?></h3>
					<div class="input-text-wrap" id="title-wrap">
						<p>
							<?php _e( 'Filter By: ', 'poller_master' ); ?>
							<select name="filter" class="filter_logs_field">
								<option value="0"><?php _e( 'ID', 'poller_master' ); ?></option>
								<option value="1"><?php _e( 'Time', 'poller_master' ); ?></option>
								<option value="2"><?php _e( 'User', 'poller_master' ); ?></option>
								<option value="3"><?php _e( 'IP', 'poller_master' ); ?></option>
								<option value="4"><?php _e( 'Answers', 'poller_master' ); ?></option>
								<option value="5"><?php _e( 'Registered', 'poller_master' ); ?></option>
							</select>
							<input name="post_title" id="title" type="text" class="filter_logs">
						</p>
					</div>
					<table class="wp-list-table widefat fixed posts table_logs" cellspacing="0">
						<thead>
							<tr>
								<th class="manage-column column-title column-tags" style="width: 5%"><?php _e( 'ID', 'poller_master' ); ?></th>
								<th class="manage-column column-title" style="width: 20%"><?php _e( 'Time', 'poller_master' ); ?></th>
								<th class="manage-column column-title" style="width: 25%"><?php _e( 'User', 'poller_master' ); ?></th>
								<th class="manage-column column-title column-tags" style="width: 15%"><?php _e( 'IP', 'poller_master' ); ?></th>
								<th class="manage-column column-title" style="width: 22%"><?php _e( 'Answers', 'poller_master' ); ?></th>
								<th class="manage-column column-title column-tags" style="width: 10%"><?php _e( 'Registered', 'poller_master' ); ?></th>
							</tr>
						</thead>
						<tbody>
							
						</tbody>
					</table>
					<br />
					<a href="javascript:;" class="button button-primary load_more_logs" data-working="<?php _e( 'Grabbing Logs', 'poller_master' ); ?>" data-ready="<?php _e( 'Load More Logs', 'poller_master' ); ?>"><?php _e( 'Load More Logs', 'poller_master' ); ?></a>
				</div>
				<div class="clear"></div>	
			</div>
			<div class="modal-footer">
				<a href="javascript:;" data-dismiss="modal" class="poller_master_modal_close btn btn-default">&times;</a>
			</div>	
		</div>
	</div>
</div>