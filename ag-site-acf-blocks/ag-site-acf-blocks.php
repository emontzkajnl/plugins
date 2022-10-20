<?php
/*
Plugin Name: Ag Site ACF Blocks
Plugin URI: https://www.jnlcom.com/
Description: Adds Custom Blocks Based On ACF Fields
Author: Journal Communications, Inc.
Text Domain: acf-blocks
Version: 1.0.0

*/

add_action('acf/init', 'my_acf_init_block_types');

function my_acf_init_block_types() {
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
        acf_register_block_type(array(
            'name'              => 'ilfb_featured_article_plus_sidebar',
            'title'             => __('ILFB Featured Article Plus Sidebar', 'acf-blocks'),
            'description'       => __('Add one large post with two small in sidebar', 'acf-blocks'),
            'mode'              => 'preview',
            'render_template'   => plugin_dir_path(__FILE__) . 'blocks/ilfb-featured-article-plus-sidebar.php',
            'icon'              => 'layout', 
            'keywords'          => array('article', 'featured', 'sidebar')
        ));
        acf_register_block_type(array(
            'name'              => 'custom_article_list',
            'title'             => __('Custom Article List', 'acf-blocks'),
            'description'       => __('Custom list of articles in two or three columns', 'acf-blocks'),
            'mode'              => 'preview',
            'render_template'   => plugin_dir_path(__FILE__) . 'blocks/custom-article-list.php',
            'icon'              => 'layout', 
            'keywords'          => array('article', 'list')
        ));
        acf_register_block_type(array(
            'name'              => 'read_the_magazine',
            'title'             => __('Read The Magazine', 'acf-blocks'),
            'description'       => __('Features current magazine', 'acf-blocks'),
            'mode'              => 'preview',
            'render_template'   => plugin_dir_path(__FILE__) . 'blocks/read-the-magazine.php',
            'icon'              => 'layout', 
            'keywords'          => array('magazine')
        ));
        acf_register_block_type(array(
            'name'              => 'social_cube',
            'title'             => __('Social Cube', 'acf-blocks'),
            'description'       => __('Displays social icons in cube', 'acf-blocks'),
            'mode'              => 'preview',
            'render_template'   => plugin_dir_path(__FILE__) . 'blocks/social-cube.php',
            'icon'              => 'layout', 
            'keywords'          => array('social, facebook, twitter, pinterest, instagram')
        ));
        acf_register_block_type(array(
            'name'              => 'article_callout',
            'title'             => __('Article Callout', 'acf-blocks'),
            'description'       => __('Displays box to link a single article', 'acf-blocks'),
            'mode'              => 'preview',
            'render_template'   => plugin_dir_path(__FILE__) . 'blocks/article-callout.php',
            'icon'              => 'layout', 
            'keywords'          => array('single, callout')
        ));
        acf_register_block_type(array(
            'name'              => 'related_articles',
            'title'             => __('Related Articles', 'acf-blocks'),
            'description'       => __('Displays three related articles at random', 'acf-blocks'),
            'mode'              => 'preview',
            'render_template'   => plugin_dir_path(__FILE__) . 'blocks/related-articles.php',
            'icon'              => 'layout', 
            'keywords'          => array()
        ));
        acf_register_block_type(array(
            'name'              => 'callout_box',
            'title'             => __('Callout Box', 'acf-blocks'),
            'description'       => __('Display a callout box with any content you wish and optional icon', 'acf-blocks'),
            'mode'              => 'preview',
            'render_template'   => plugin_dir_path(__FILE__) . 'blocks/callout-box.php',
            'icon'              => 'layout', 
            'keywords'          => array()
        ));
        register_block_type( plugin_dir_path(__FILE__ ) . 'blocks/ilfb-podcast-player/block.json');
        register_block_type( plugin_dir_path(__FILE__ ) . 'blocks/ilfb-article-list/block.json');
        // wp_register_script('podcast-block',plugin_dir_url(__FILE__) . '/blocks/ilfb-podcast-player/amplitude.js',array(), null, true);
    }
} 

?>