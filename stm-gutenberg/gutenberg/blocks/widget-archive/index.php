<?php
function render_block_stm_gutenberg_widget_archive ($attributes) {

    $vs = $attributes['viewStyle'];

    $hTag = $attributes['headingTag'];
    $headingCFSStyle = ( !empty($attributes['headingCFS']) ) ? 'style="font-size: ' . $attributes['headingCFS'] . 'px !important;"' : '';
    $wrapStyle = 'style="' . stmt_gutenmag_generateWrapStyle($attributes) . '"';


    $widget = "<div class='widget_archive_wrapper " . $vs . "' " . $wrapStyle . ">";

    if(!empty($attributes['title'])) {
        $widget .= "<" . $hTag . " class='heading-font block-title " . esc_attr($attributes['headerStyle']) . "' " . $headingCFSStyle . ">" . esc_html($attributes['title']) . "</" . $hTag . ">";
    }

    ob_start();

    $widget .= "<div class='select-wrap'>";

    ?>
    <select name='archive-dropdown' class='normal-font' onchange='document.location.href=this.options[this.selectedIndex].value;'>
    <?php
        $dropdown_args = array(
            'type'            => 'monthly',
            'format'          => 'option',
            'show_post_count' => false
        );

        switch ( $dropdown_args['type'] ) {
            case 'yearly':
                $label = __( 'Select Year' );
                break;
            case 'monthly':
                $label = __( 'Select Month' );
                break;
            case 'daily':
                $label = __( 'Select Day' );
                break;
            case 'weekly':
                $label = __( 'Select Week' );
                break;
            default:
                $label = __( 'Select Post' );
                break;
        }
    ?>

    <option value=''><?php echo esc_attr( $label ); ?></option>

    <?php wp_get_archives( $dropdown_args ); ?>

    </select>

    <?php
	$widget .= ob_get_clean();
	$widget .= '</div>';
	$widget .= '</div>';
    $output = $widget;

    return $output;
}

function register_block_stm_gutenberg_widget_archive () {

    wp_register_script('stm_gutenberg_widget_archive',
        STM_GUTENBERG_URL . 'gutenberg/js/widget-archive.js',
        array('wp-blocks', 'wp-element', 'wp-data'),
        STM_GUTENBERG_VER,
        true
    );

    wp_register_style('stm_gutenberg_widget_archive',
        STM_GUTENBERG_URL . 'gutenberg/css/widget-archive.css',
        array( 'wp-edit-blocks' ),
        STM_GUTENBERG_VER
    );

    register_block_type( 'stm-gutenberg/widget-archive', array(
        'attributes'      => array(
            'title'             => array ( 'type' => 'string', ),
            'headerStyle'      => array ( 'type' => 'string', 'default' => 'global' ),
            'headingTag'        => array ( 'type' => 'string', 'default' => 'h3' ),
            'headingCFS'        => array ( 'type' => 'string' ),
            'viewStyle'     => array ( 'type' => 'string', 'default' => 'style_1' ),
            'margin_top' => array ( 'type' => 'string' ),
            'padding_top' => array ( 'type' => 'string' ),
            'padding_left' => array ( 'type' => 'string' ),
            'margin_left' => array ( 'type' => 'string' ),
            'padding_right' => array ( 'type' => 'string' ),
            'margin_right' => array ( 'type' => 'string' ),
            'padding_bottom' => array ( 'type' => 'string' ),
            'margin_bottom' => array ( 'type' => 'string' ),
        ),
        'editor_script' => 'stm_gutenberg_widget_archive',
        'editor_style' => 'stm_gutenberg_widget_archive',
        'style' => 'stm_gutenberg_widget_archive',
        'render_callback' => 'render_block_stm_gutenberg_widget_archive',
    ) );
}

add_action( 'init', 'register_block_stm_gutenberg_widget_archive' );