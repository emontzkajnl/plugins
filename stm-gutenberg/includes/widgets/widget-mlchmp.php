<?php
class STM_WP_Widget_Mlchmp extends WP_Widget {

    public function __construct() {
        $widget_ops = array(
            'classname'   => 'stm_widget_mlchmp',
            'description' => __( "STM MailChimp.", 'gutenmag' )
        );
        parent::__construct( 'stm-mlchmp', __( 'STM MailChimp', 'gutenmag' ), $widget_ops );
        $this->alt_option_name = 'stm_widget_mlchmp';

    }

    /**
     * @param array $args
     * @param array $instance
     */
    public function widget( $args, $instance ) {
        $cache = array();
        if ( ! $this->is_preview() ) {
            $cache = wp_cache_get( 'stm_widget_mlchmp', 'widget' );
        }

        if ( ! is_array( $cache ) ) {
            $cache = array();
        }

        if ( ! isset( $args['widget_id'] ) ) {
            $args['widget_id'] = $this->id;
        }

        if ( isset( $cache[ $args['widget_id'] ] ) ) {
            echo stmt_gutenmag_print_lmth($cache[ $args['widget_id'] ]);

            return;
        }

        $title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'About me', 'gutenmag' );
        $subtitle = ( ! empty( $instance['subtitle'] ) ) ? $instance['subtitle'] : __( 'Welcome!', 'gutenmag' );
        $touLink = ( ! empty( $instance['touLink'] ) ) ? $instance['touLink'] : '';

        echo stmt_gutenmag_print_lmth($args['before_widget']);
        $widget = '<div class="stm-widget_mchmp_wrapper style_2">';
        $widget .= '<div class="inner">';
        $widget .= '<div class="subtitle normal-font">' . esc_html($subtitle) . '</div>';
        $widget .= '<h5 class="heading-font block-title block_header_5">' . esc_html($title) . '</h5>';

        ob_start();
        echo do_shortcode('[stm_mailchimp]');
        $widget .= ob_get_clean();
        $widget .= '<div class="normal-font tou_text">' . sprintf( esc_html__('By signing up, you agree to <a href="%s">our terms</a>.', 'gutenmag'), $touLink ) . '</div>';
        $widget .= '</div>';
        $widget .= '</div>';

        echo stmt_gutenmag_print_lmth($widget);
        echo stmt_gutenmag_print_lmth($args['after_widget']);
    }

    public function update( $new_instance, $old_instance ) {
        $instance              = $old_instance;
        $instance['title']     = strip_tags( $new_instance['title'] );
        $instance['subtitle']  = strip_tags( $new_instance['subtitle'] );
        $instance['touLink']  = strip_tags( $new_instance['touLink'] );

        return $instance;
    }

    public function form( $instance ) {
        $title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
        $subtitle     = isset( $instance['subtitle'] ) ? esc_attr( $instance['subtitle'] ) : '';
        $touLink    = isset( $instance['touLink'] ) ? esc_attr( $instance['touLink'] ) : '';

        ?>
        <p><label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e( 'Title:', 'gutenmag' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"
                   name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr($title); ?>"/>
        </p>
        <p><label for="<?php echo esc_attr($this->get_field_id( 'subtitle' )); ?>"><?php esc_html_e( 'SubTitle:', 'gutenmag' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'subtitle' )); ?>"
                   name="<?php echo esc_attr($this->get_field_name( 'subtitle' )); ?>" type="text" value="<?php echo esc_attr($subtitle); ?>"/>
        </p>
        <p><label for="<?php echo esc_attr($this->get_field_id( 'touLink' )); ?>"><?php esc_html_e( 'Terms Of Use Link:', 'gutenmag' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'touLink' )); ?>"
                   name="<?php echo esc_attr($this->get_field_name( 'touLink' )); ?>" type="text" value="<?php echo esc_attr($touLink); ?>"/>
        </p>
        <?php
    }
}

function register_stm_wp_widget_mlchmp() {
    register_widget( 'STM_WP_Widget_Mlchmp' );
}
add_action( 'widgets_init', 'register_stm_wp_widget_mlchmp' );