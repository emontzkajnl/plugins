<?php
function render_block_stm_gutenberg_single_post ($attributes) {
    $bgStyle = '';
    $containerBgStyle = '';
    $wrapStyle = stmt_gutenmag_generateWrapStyle($attributes);
    $offset = ( !empty( $attributes['postOffset'] ) ) ? $attributes['postOffset'] : 0;
    $hTag = $attributes['headingTag'];
    $headingCFSStyle = ( !empty($attributes['headingCFS']) ) ? 'style="font-size: ' . $attributes['headingCFS'] . 'px !important;"' : '';

    if($attributes['contWidth'] == 'boxed' && !empty($attributes['bgColor'])) {
        $containerBgStyle = 'style="background-color: ' . $attributes['bgColor'] . '";';
    } else {
        if( !empty($attributes['bgColor']) ) {
            $wrapStyle .= ' background-color: ' . $attributes['bgColor'] . ';';
        }
    }

    //$post = get_post();


    $containerClass = ($attributes['contWidth'] == 'full_width_with_content') ? 'full_width_with_content' : '';

    $args = array (
        'post_type' => 'post',
        'post_status' => 'publish',
        'ignore_sticky_posts' => 1,
        'posts_per_page' => 1,
        'orderby' => ($attributes['postOrder'] == 'rand') ? 'rand' : 'post_id',
        'order' => $attributes['postOrder'],
        'offset' => $offset * 1,
    );

    if(!empty($attributes['only_sticky'])) {
        $args = array(
            'posts_per_page' => 1,
            'post__in'  => get_option( 'sticky_posts' ),
            'ignore_sticky_posts' => 1
        );
    }

    if($attributes['postOrder'] == 'rand') unset($args['order']);

    if($attributes['categories'] != '') {
        $tax = array (
            array (
                'taxonomy' => 'category',
                'field' => 'id',
                'terms' => $attributes['categories']
            )
        );
        $args['tax_query'] = $tax;
    }

    if(!empty($attributes['postId'])) {
        $args = array (
            'p' => $attributes['postId'],
            'post_status' => 'publish',
            'ignore_sticky_posts' => 1,
        );
    }

    $post = new WP_Query($args);
    ob_start();

    $postId = $post->post->ID;

    $postFeatImg = (!empty(get_the_post_thumbnail_url($postId, 'full'))) ? get_the_post_thumbnail_url($postId, 'full') : '';

    if($attributes['useFeatImgBG']) $wrapStyle .= ' background-image: url(\'' . $postFeatImg . '\'); background-position: cover;';
    if(!empty($wrapStyle)) $bgStyle = 'style="' . $wrapStyle . '"';
?>
<div class="stm_gutenberg_single_post_wrap <?php echo esc_attr($attributes['contWidth']); ?>" <?php echo stmt_gutenmag_print_lmth($bgStyle); ?>>
    <div class="container <?php echo esc_attr($containerClass . ' ' . $attributes['alignment']); ?>" <?php echo stmt_gutenmag_print_lmth($containerBgStyle); ?>>
        <div class="stm_gutenberg_single_post">
            <?php if( !empty( $attributes['title'] ) ) : ?>
                <<?php echo stmt_gutenmag_print_lmth($hTag); ?> class="heading-font block-title <?php echo esc_attr($attributes['headerStyle']); ?>" <?php echo stmt_gutenmag_print_lmth($headingCFSStyle); ?>><?php echo esc_html($attributes['title']); ?></<?php echo stmt_gutenmag_print_lmth($hTag); ?>>
            <?php endif;?>
            <div class="stm_gutenberg_sp-content">
                <?php
                if($post->have_posts()) {
                    while($post->have_posts()) {
                        $post->the_post();
                        if(is_sticky($postId)) {
                            echo '<div class="stmt-sticky-post normal-font">' . esc_html__('Sticky Post', 'stm-gutenberg') . '</div>';
                        }
                        get_template_part('template-parts/loop/single-post/s-p-' . $attributes['viewStyle']);
                    }
                }
                wp_reset_postdata();
                ?>
            </div>
        </div>
    </div>
</div>
<?php

    $output = ob_get_clean();

    return $output;
}

function register_block_stm_gutenberg_single_post () {
    wp_register_script('stm_gutenberg_single_post',
        STM_GUTENBERG_URL . 'gutenberg/js/single-post.js',
        array('wp-blocks', 'wp-element', 'wp-data'),
        STM_GUTENBERG_VER,
        true
    );

    wp_register_style('stm_gutenberg_single_post',
        STM_GUTENBERG_URL . 'gutenberg/css/single-post.css',
        array( 'wp-edit-blocks' ),
        STM_GUTENBERG_VER
    );

    register_block_type( 'stm-gutenberg/single-post', array(
        'attributes'      => array(
            'contWidth'         => array ( 'type' => 'string',  'default' => 'boxed' ),
            'viewStyle'         => array ( 'type' => 'string', 'default' => 'style_1' ),
            'categories'        => array ( 'type' => 'string', 'default' => 'all' ),
            'postOrder'         => array ( 'type' => 'string', 'default' => 'desc' ),
            'postOffset'        => array ( 'type' => 'string' ),
            'postId'            => array ( 'type' => 'string' ),
            'only_sticky'       => array ( 'type' => 'boolean', 'default' => false ),
            'title'             => array ( 'type' => 'string', ),
            'headerStyle'      => array ( 'type' => 'string', 'default' => 'global' ),
            'headingTag'        => array ( 'type' => 'string', 'default' => 'h3' ),
            'headingCFS'        => array ( 'type' => 'string' ),
            'bgColor'           => array ( 'type' => 'string' ),
            'alignment'         => array ( 'type' => 'string', 'default' => 'left' ),
            'useFeatImgBG'      => array ( 'type' => 'boolean', 'default' => false),
            'margin_top' => array ( 'type' => 'string' ),
            'padding_top' => array ( 'type' => 'string' ),
            'padding_left' => array ( 'type' => 'string' ),
            'margin_left' => array ( 'type' => 'string' ),
            'padding_right' => array ( 'type' => 'string' ),
            'margin_right' => array ( 'type' => 'string' ),
            'padding_bottom' => array ( 'type' => 'string' ),
            'margin_bottom' => array ( 'type' => 'string' ),
        ),
        'editor_script' => 'stm_gutenberg_single_post',
        'editor_style' => 'stm_gutenberg_single_post',
        'style' => 'stm_gutenberg_single_post',
        'render_callback' => 'render_block_stm_gutenberg_single_post',
    ) );
}

add_action( 'init', 'register_block_stm_gutenberg_single_post' );