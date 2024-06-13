<?php
class STM_WP_Widget_Hot_News extends WP_Widget {

	public function __construct() {
		$widget_ops = array(
			'classname'   => 'stm_widget_hot_entries',
			'description' => __( "Your site&#8217;s most Hot News.", 'gutenmag' )
		);
		parent::__construct( 'stm-hot-news', __( 'STM Hot News', 'gutenmag' ), $widget_ops );
		$this->alt_option_name = 'stm_widget_hot_entries';

	}

	/**
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		$cache = array();
		if ( ! $this->is_preview() ) {
			$cache = wp_cache_get( 'stm_widget_hot_news', 'widget' );
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

		ob_start();

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'STM Hot News', 'gutenmag' );

		$number_of_posts = ( ! empty( $instance['number_of_posts'] ) ) ? $instance['number_of_posts'] : __( '1', 'gutenmag' );

		/** This filter is documented in wp-includes/default-widgets.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		if(empty($number_of_posts)) {
			$number_of_posts = 1;
		}

		/**
		 * Filter the arguments for the Hot Posts widget.
		 *
		 * @since 3.4.0
		 *
		 * @see WP_Query::get_posts()
		 *
		 * @param array $args An array of arguments used to retrieve the hot news.
		 */
		$r = new WP_Query( apply_filters( 'widget_posts_args', array(
			'posts_per_page'      => $number_of_posts,
			'no_found_rows'       => true,
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true
		) ) );

		if ( $r->have_posts() ) :
			?>
			<?php echo stmt_gutenmag_print_lmth($args['before_widget']); ?>
			<?php if ( $title ) {
                echo stmt_gutenmag_print_lmth($args['before_title'] . $title . $args['after_title']);
			} ?>
			<?php while ( $r->have_posts() ) : $r->the_post(); ?>
                <a href="<?php the_permalink(); ?>" class="stm-hot-news clearfix">
                    <?php if(has_post_thumbnail()): ?>
                        <div class="image">
                            <?php stmt_gutenmag_get_the_post_thumbnail( get_the_ID(), 'stm-gm-120-97'); ?>
                        </div>
                    <?php endif; ?>
                    <div class="stm-post-content">
                        <div class="meta-top">
                            <h4 class="heading-font"><?php the_title(); ?></h4>
                        </div>
                        <?php if(function_exists('stmt_gutenmag_get_post_view_count')) : ?>
                        <div class="meta-bottom">
                            <div class="views-wrap">
                                <i class="stm-gm-icon-eye"></i>
                                <?php echo stmt_gutenmag_get_post_view_count(get_the_ID()); ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </a>
			<?php endwhile; ?>
			<?php echo stmt_gutenmag_print_lmth($args['after_widget']); ?>
			<?php
			wp_reset_postdata();

		endif;
	}

	public function update( $new_instance, $old_instance ) {
		$instance              = $old_instance;
		$instance['title']     = strip_tags( $new_instance['title'] );
		$instance['number_of_posts']     = strip_tags( $new_instance['number_of_posts'] );

		return $instance;
	}

	public function form( $instance ) {
		$title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$number_of_posts     = isset( $instance['number_of_posts'] ) ? esc_attr( $instance['number_of_posts'] ) : '';
		$show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
		?>
		<p><label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e( 'Title:', 'gutenmag' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"
			       name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr($title); ?>"/>
		</p>
		<p><label for="<?php echo esc_attr($this->get_field_id( 'number_of_posts' )); ?>"><?php esc_html_e( 'Number of posts:', 'gutenmag' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'number_of_posts' )); ?>"
			       name="<?php echo esc_attr($this->get_field_name( 'number_of_posts' )); ?>" type="number" value="<?php echo esc_attr($number_of_posts); ?>"/>
		</p>
	<?php
	}
}

function register_stm_wp_widget_hot_news() {
	register_widget( 'STM_WP_Widget_Hot_News' );
}
add_action( 'widgets_init', 'register_stm_wp_widget_hot_news' );