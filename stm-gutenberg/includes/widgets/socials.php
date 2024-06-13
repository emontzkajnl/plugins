<?php

class Stm_Socials_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'socials', // Base ID
			__('Socials', 'gutenmag'), // Name
			array( 'description' => __( 'Socials widget', 'gutenmag' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		$title = (isset($instance['title']) && !empty($instance['title'])) ? apply_filters( 'widget_title', $instance['title'] ) : '';

        echo stmt_gutenmag_print_lmth($args['before_widget']);
		if ( ! empty( $title ) ) {
			echo stmt_gutenmag_print_lmth($args['before_title'] . esc_html( $title ) . $args['after_title']);
		}
		echo '<div class="socials_widget_wrapper">';

		echo stmt_gutenmag_get_socials();
		    
		echo '</div>';

        echo stmt_gutenmag_print_lmth($args['after_widget']);
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {

		$title = '';

		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}else {
			$title = __( 'Social Network', 'gutenmag' );
		}

		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'gutenmag' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
	<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? esc_attr( $new_instance['title'] ) : '';

		return $instance;
	}

}

function stm_register_socials_widget() {
	register_widget( 'Stm_Socials_Widget' );
}
add_action( 'widgets_init', 'stm_register_socials_widget' );