<?php

class STM_WP_Widget_Latest_Comments extends WP_Widget {

	public function __construct() {
		$widget_ops  = array(
			'classname'   => 'stm_wp_widget_latest_comments',
			'description' => __( 'STMT Latest Comments.', 'gutenmag' )
		);
		$control_ops = array( 'width' => 400, 'height' => 350 );
		parent::__construct( 'stm_latest_comments', __( 'STMT GM Latest Comments', 'gutenmag' ), $widget_ops, $control_ops );
	}

	public function widget( $args, $instance ) {

        $output = '';

        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

        $number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
        if ( ! $number ) {
            $number = 5;
        }

        /**
         * Filters the arguments for the Recent Comments widget.
         *
         * @since 3.4.0
         * @since 4.9.0 Added the `$instance` parameter.
         *
         * @see WP_Comment_Query::query() for information on accepted arguments.
         *
         * @param array $comment_args An array of arguments used to retrieve the recent comments.
         * @param array $instance     Array of settings for the current widget.
         */
        $comments = get_comments(
            apply_filters(
                'widget_comments_args',
                array(
                    'number'      => $number,
                    'status'      => 'approve',
                    'post_status' => 'publish',
                ),
                $instance
            )
        );
        $output .= $args['before_widget'];
        if ( $title ) {
            $output .= $args['before_title'] . $title . $args['after_title'];
        }
        $output .= '<ul id="recentcomments">';
        if ( is_array( $comments ) && $comments ) {
            // Prime cache for associated posts. (Prime post term cache if we need it for permalinks.)
            $post_ids = array_unique( wp_list_pluck( $comments, 'comment_post_ID' ) );
            _prime_post_caches( $post_ids, strpos( get_option( 'permalink_structure' ), '%category%' ), false );
            foreach ( (array) $comments as $comment ) {
                $output .= '<li class="recentcomments">';
                $output .= '<a href="' . esc_url(get_comment_link($comment->comment_ID)) . '">';
                $output .= '<span class="stm-gm-icon-testimonials"></span><span>' . $comment->comment_content . '</span>';
                $output .= '</a>';
                $output .= '</li>';
            }
        }
        $output .= '</ul>';
        $output .= $args['after_widget'];
        echo stmt_gutenmag_print_lmth($output);
	}

	public function update( $new_instance, $old_instance ) {
		$instance          = $old_instance;
		$instance['title'] = $new_instance['title'];
		$instance['number'] = $new_instance['number'];

		return $instance;
	}

	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '' ) );
		$title    = $instance['title'];
		$text     = esc_textarea( $instance['text'] );
		?>
		<p><label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e( 'Title:', 'gutenmag' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"
			       name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text"
			       value="<?php echo esc_attr( $title ); ?>"/></p>

		<p><label for="<?php echo esc_attr($this->get_field_id( 'text' )); ?>"><?php esc_html_e( 'Number of comments to show:', 'gutenmag' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'text' )); ?>"
			          name="<?php echo esc_attr($this->get_field_name( 'number' )); ?>"><?php echo stmt_gutenmag_print_lmth($text); ?></input></p>

	<?php
	}
}

function register_stm_text_widget() {
	register_widget( 'STM_WP_Widget_Latest_Comments' );
}

add_action( 'widgets_init', 'register_stm_text_widget' );