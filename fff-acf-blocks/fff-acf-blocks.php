<?php
/*
Plugin Name: FFF ACF Blocks
Plugin URI: https://www.jnlcom.com/
Description: Adds Custom Blocks Based On ACF Fields
Author: Journal Communications, Inc.
Text Domain: acf-blocks
Version: 1.0.0

*/

add_action('acf/init', 'my_fff_init_block_types');

function my_fff_init_block_types() {
    if( function_exists('acf_register_block_type')) {
        register_block_type( plugin_dir_path(__FILE__ ) . 'blocks/fff-newsletter/block.json');
        register_block_type(plugin_dir_path( __FILE__ ) . 'blocks/fff-popular-posts/block.json');
        register_block_type(plugin_dir_path( __FILE__ ) . 'blocks/fff-recent-posts/block.json');
    }
}
?>