<?php
class STM_WP_Widget_Author extends WP_Widget {

    public function __construct() {
        $widget_ops = array(
            'classname'   => 'stm_widget_author',
            'description' => __( "STM Blog Author.", 'gutenmag' )
        );
        parent::__construct( 'stm-blog-author', __( 'STM Blog Author', 'gutenmag' ), $widget_ops );
        $this->alt_option_name = 'stm_widget_author';

    }

    /**
     * @param array $args
     * @param array $instance
     */
    public function widget( $args, $instance ) {
        $cache = array();
        if ( ! $this->is_preview() ) {
            $cache = wp_cache_get( 'stm_widget_author', 'widget' );
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
        $img_link = ( ! empty( $instance['img_link'] ) ) ? $instance['img_link'] : __( '', 'gutenmag' );
        $auth_desc = ( ! empty( $instance['auth_desc'] ) ) ? $instance['auth_desc'] : __( 'Blogger, photographer, cat lover, traveller, pizza addict.', 'gutenmag' );

        echo stmt_gutenmag_print_lmth($args['before_widget']);
        $widget = '<div class="widget_author_wrapper style_2">';

        $widget .= '<div class="author">';
        $widget .= '<div class="author-img">';
        $widget .= '<img src="' . $img_link . '" />';
        $widget .= '</div>';
        $widget .= '<div class="author-meta">';
        $widget .= '<div class="author-subtitle normal-font">' . $subtitle . '</div>';
        $widget .= '<h5 class="heading-font">';
        $widget .=  esc_html($title);
        $widget .= '</h5>';
        $widget .= '<div class="author-desc">';
        $widget .=  esc_html($auth_desc);
        $widget .= '</div>';
        $widget .= '</div>';

        $widget .= "</div>";
        $widget .= '</div>';

        echo stmt_gutenmag_print_lmth($widget);
        echo stmt_gutenmag_print_lmth($args['after_widget']);
    }

    public function update( $new_instance, $old_instance ) {
        $instance              = $old_instance;
        $instance['title']     = strip_tags( $new_instance['title'] );
        $instance['subtitle']  = strip_tags( $new_instance['subtitle'] );
        $instance['img_link']  = strip_tags( $new_instance['img_link'] );
        $instance['auth_desc']  = strip_tags( $new_instance['auth_desc'] );

        return $instance;
    }

    public function form( $instance ) {
        $title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
        $subtitle     = isset( $instance['subtitle'] ) ? esc_attr( $instance['subtitle'] ) : '';
        $img_link     = isset( $instance['img_link'] ) ? esc_attr( $instance['img_link'] ) : '';
        $auth_desc    = isset( $instance['auth_desc'] ) ? esc_attr( $instance['auth_desc'] ) : '';

        ?>
        <p><label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e( 'Title:', 'gutenmag' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"
                   name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr($title); ?>"/>
        </p>
        <p><label for="<?php echo esc_attr($this->get_field_id( 'subtitle' )); ?>"><?php esc_html_e( 'SubTitle:', 'gutenmag' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'subtitle' )); ?>"
                   name="<?php echo esc_attr($this->get_field_name( 'subtitle' )); ?>" type="text" value="<?php echo esc_attr($subtitle); ?>"/>
        </p>
        <p><label for="<?php echo esc_attr($this->get_field_id( 'img_link' )); ?>"><?php esc_html_e( 'Author Img:', 'gutenmag' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'img_link' )); ?>"
                   name="<?php echo esc_attr($this->get_field_name( 'img_link' )); ?>" type="text" value="<?php echo esc_attr($img_link); ?>"/>
        </p>
        <p><label for="<?php echo esc_attr($this->get_field_id( 'auth_desc' )); ?>"><?php esc_html_e( 'Description:', 'gutenmag' ); ?></label>
            <textarea class="widefat" id="<?php echo esc_attr($this->get_field_id( 'auth_desc' )); ?>"
                   name="<?php echo esc_attr($this->get_field_name( 'auth_desc' )); ?>"><?php echo esc_html($auth_desc); ?></textarea>
        </p>
        <?php
    }
}

function register_stm_wp_widget_author() {
    register_widget( 'STM_WP_Widget_Author' );
}
add_action( 'widgets_init', 'register_stm_wp_widget_author' );