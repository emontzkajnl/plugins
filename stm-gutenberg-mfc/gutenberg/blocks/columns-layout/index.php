<?php
function register_block_stm_gutenberg_columns_layout() {
    wp_register_script('stm_gutenberg_columns_layout',
        STM_GUTENBERG_URL . 'gutenberg/js/columns-layout.js',
        array('wp-blocks', 'wp-element', 'wp-data'),
        '1.0',
        true
    );

    wp_register_style('stm_gutenberg_columns_layout',
        STM_GUTENBERG_URL . 'gutenberg/css/columns-layout.css',
        array( 'wp-edit-blocks' ),
        '1.0'
    );

    register_block_type( 'stm-gutenberg/columns-layout', array(
        'attributes' => array (
            'margin_top' => array ( 'type' => 'string' ),
            'padding_top' => array ( 'type' => 'string' ),
            'padding_left' => array ( 'type' => 'string' ),
            'margin_left' => array ( 'type' => 'string' ),
            'padding_right' => array ( 'type' => 'string' ),
            'margin_right' => array ( 'type' => 'string' ),
            'padding_bottom' => array ( 'type' => 'string' ),
            'margin_bottom' => array ( 'type' => 'string' ),
        ),
        'editor_script' => 'stm_gutenberg_columns_layout',
        'editor_style' => 'stm_gutenberg_columns_layout',
        'style' => 'stm_gutenberg_columns_layout',
    ) );
}

add_action( 'init', 'register_block_stm_gutenberg_columns_layout' );

?>