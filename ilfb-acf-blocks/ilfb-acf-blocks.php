<?php
/*
Plugin Name: ILFB ACF Blocks
Plugin URI: https://www.jnlcom.com/
Description: Adds Custom Blocks Based On ACF Fields
Author: Journal Communications, Inc.
Text Domain: acf-blocks
Version: 1.0.0

*/

add_action('acf/init', 'my_ilfb_init_block_types');

function my_ilfb_init_block_types() {
    if( function_exists('acf_register_block_type')) {
        acf_register_block_type(array(
            'name'              => 'ilfb_featured_article_plus_sidebar',
            'title'             => __('ILFB Featured Article Plus Sidebar', 'acf-blocks'),
            'description'       => __('Add one large post with two small in sidebar', 'acf-blocks'),
            'mode'              => 'preview',
            'render_template'   => plugin_dir_path(__FILE__) . 'blocks/ilfb-featured-article-plus-sidebar.php',
            'icon'              => 'layout', 
            'keywords'          => array('article', 'featured', 'sidebar')
        ));
        register_block_type( plugin_dir_path(__FILE__ ) . 'blocks/ilfb-podcast-player/block.json');
        register_block_type( plugin_dir_path(__FILE__ ) . 'blocks/ilfb-article-list/block.json');
        register_block_type( plugin_dir_path(__FILE__ ) . 'blocks/video-section/block.json');
    }
}
?>