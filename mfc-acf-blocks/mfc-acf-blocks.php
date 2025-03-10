<?php
/*
Plugin Name: MFC ACF Blocks
Plugin URI: https://www.jnlcom.com/
Description: Adds Custom Blocks Based On ACF Fields
Author: Journal Communications, Inc.
Text Domain: acf-blocks
Version: 1.0.0

*/

add_action('acf/init', 'my_mfc_init_block_types');

function my_mfc_init_block_types() {
    if( function_exists('acf_register_block_type')) {
        acf_register_block_type(array(
            'name'              => 'featured_article_plus_sidebar',
            'title'             => __('Featured Article Plus Sidebar', 'acf-blocks'),
            'description'       => __('Add one large post with three small in sidebar', 'acf-blocks'),
            'mode'              => 'preview',
            'render_template'   => plugin_dir_path(__FILE__) . 'blocks/featured-article-plus-sidebar.php',
            'icon'              => 'layout', 
            'keywords'          => array('article', 'featured', 'sidebar')
        ));
        register_block_type( plugin_dir_path(__FILE__ ) . 'blocks/mfc-magazine-social/block.json');
        register_block_type( plugin_dir_path(__FILE__ ) . 'blocks/mfc-recent-posts/block.json');
        register_block_type( plugin_dir_path(__FILE__ ) . 'blocks/mfc-article-callout/block.json');
    }
}
?>