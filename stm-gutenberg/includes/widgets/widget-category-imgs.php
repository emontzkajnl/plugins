<?php
class STM_WP_Widget_Category_With_Img extends WP_Widget {

    public function __construct() {
        $widget_ops = array(
            'classname' => 'stm_widget_category_with_img',
            'description' => esc_html__( 'A list category with image.', 'gutenmag' ),
            'customize_selective_refresh' => true,
        );
        parent::__construct( 'stm-widget-category-img', esc_html__( 'STM Category With Image', 'gutenmag' ), $widget_ops );
    }

    public function widget( $args, $instance ) {

        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Categories', 'gutenmag' );

        $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

        echo $args['before_widget'];

        $nums = (!empty($instance['max_number'])) ? $instance['max_number'] : '';

        $arg = array(
            'taxonomy'              => 'category',
            'orderby'                => 'name',
            'order'                  => 'ASC',
            'hide_empty'             => true,
            'number'                 => $nums,
            'fields'                 => 'all',
            'count'                  => false,
            'hierarchical'           => true,
        );

        $cats = get_terms($arg);

        $li = '';

        foreach ($cats as $k => $cat) {
            $catImgId = get_term_meta($cat->term_id, 'category_img', true);
            $catImg = stmt_gutenmag_get_thumbnail($catImgId, 'stm-gm-270-120');

            $style = (!empty($catImg)) ? 'style="background: url(' . $catImg[0] . ') no-repeat 0 0; "' : '';
            $cls = (!empty($catImg)) ? '' : 'no-img';

            $li .= '<li><a class="' . $cls . '" href="' . get_category_link($cat->term_id) . '" ' . $style . '><span class="normal-font">' . $cat->name . '</span></a></li>';
        }

        $catList = $li;

        $tags = ( !empty( $catList ) ) ? '<ul class="style_2">' . $catList . '</ul>' : esc_html__('No Categories', 'gutenmag' );

        $widget = '<div class="widget_category_list_wrapper">';

        $widget .= '<h5 class="heading-font block-title block_header_5">' . esc_html($title) . '</h5>';

        $widget .= '<div class="category_list">';
        $widget .= $tags;
        $widget .= "</div>";
        $widget .= '</div>';

        echo stmt_gutenmag_print_lmth($widget);

        echo $args['after_widget'];
    }

    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = sanitize_text_field( $new_instance['title'] );
        $instance['max_number'] = sanitize_text_field( $new_instance['max_number'] );

        return $instance;
    }

    /**
     * Outputs the settings form for the Categories widget.
     *
     * @since 2.8.0
     *
     * @param array $instance Current settings.
     */
    public function form( $instance ) {
        //Defaults
        $instance = wp_parse_args( (array) $instance, array( 'title' => '', 'max_number' => '') );
        $title = sanitize_text_field( $instance['title'] );
        $max_number = sanitize_text_field( $instance['max_number'] );
        ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e( 'Title:', 'gutenmag' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>
        <p><label for="<?php echo $this->get_field_id('max_number'); ?>"><?php esc_html_e( 'Max Number:', 'gutenmag' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('max_number'); ?>" name="<?php echo $this->get_field_name('max_number'); ?>" type="text" value="<?php echo esc_attr( $max_number ); ?>" /></p>

        <?php
    }

}

function register_stm_wp_widget_category_with_img() {
    register_widget( 'STM_WP_Widget_Category_With_Img' );
}
add_action( 'widgets_init', 'register_stm_wp_widget_category_with_img' );