<?php
class Poller_Master_Widget extends WP_Widget {
	public function __construct() {
		parent::__construct('poller_master_widget', __('Poller Master Widget','rando'), array('description' =>__('Poller Master Widget','rando') ));
	}
	public function widget( $args, $instance ) {
		extract($args);
		$poll_id = $instance['poll_id'];
		$extra_class = $instance['extra_class'];
		echo $before_widget;
		echo do_shortcode( '[poller_master poll_id="'.$poll_id.'" extra_class="'.$extra_class.'"]' );
		echo $after_widget;
	}
 	public function form( $instance ) {
		$poll = new Poll_It();
		$polls = $poll->get_polls();
		$instance = wp_parse_args( (array) $instance, array( 'poll_id' => '', 'extra_class' => '' ) );
		$poll_id = esc_attr( $instance['poll_id'] );
		$extra_class = esc_attr( $instance['extra_class'] );
		
		echo '<p><label for="'.($this->get_field_id('poll_id')).'">'.__( 'Select Poll', 'poller_master' ).'</label>';
		echo '<select class="widefat" id="'.($this->get_field_id('poll_id')).'"  name="'.($this->get_field_name('poll_id')).'">';
			if( !empty($polls) ){
				foreach( $polls as $poll ){
					echo '<option value="'.$poll->id.'" '.( $poll->id == $poll_id ? 'selected="selected"' : '' ).'>'.$poll->name.'</option>';
				}
			}
		echo '</select>';
		echo '<p><label for="'.($this->get_field_id('extra_class')).'">'.__( 'Extra Class', 'poller_master' ).'</label>';
		echo '<input type="text" class="widefat" id="'.($this->get_field_id('extra_class')).'"  name="'.($this->get_field_name('extra_class')).'" value="'.$extra_class.'">';	
	}
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['poll_id'] = strip_tags($new_instance['poll_id']);
		$instance['extra_class'] = strip_tags($new_instance['extra_class']);
		return $instance;	
	}
}

function poller_master_widgets_load(){
	register_widget( 'Poller_Master_Widget' );
}
add_action( 'widgets_init', 'poller_master_widgets_load');

?>