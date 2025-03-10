<?php
/*
Plugin Name: NCFF ACF Blocks
Plugin URI: https://www.jnlcom.com/
Description: Adds Custom Blocks Based On ACF Fields
Author: Journal Communications, Inc.
Text Domain: acf-blocks
Version: 1.0.0

*/

add_action('acf/init', 'my_ncff_init_block_types');

function my_ncff_init_block_types() {
    if( function_exists('acf_register_block_type')) {
        register_block_type( plugin_dir_path(__FILE__ ) . 'blocks/ncff-featured-articles/block.json');
        register_block_type( plugin_dir_path(__FILE__ ) . 'blocks/ncff-newsletter/block.json');
        register_block_type( plugin_dir_path(__FILE__ ) . 'blocks/ncff-recent-posts/block.json');
        register_block_type( plugin_dir_path(__FILE__ ) . 'blocks/ncff-popular-posts/block.json');
        register_block_type( plugin_dir_path(__FILE__ ) . 'blocks/ncff-article-link/block.json');
        register_block_type( plugin_dir_path(__FILE__ ) . 'blocks/ncff-infobox/block.json');
        register_block_type( plugin_dir_path(__FILE__ ) . 'blocks/ncff-event-filter/block.json');
    }
}
?>