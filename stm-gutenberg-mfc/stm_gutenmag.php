<?php

add_filter('block_categories', 'stm_guten_block_categories', 20, 2);

function stm_guten_block_categories($default_categories, $post)
{

	$default_categories[] = array(
		'slug'  => 'stm_blocks',
		'title' => __('STM Blocks', 'gutenmag')
	);

	return $default_categories;
}

foreach (glob(STM_GUTENBERG_DIR . 'gutenberg/blocks/*/index.php') as $block_render) {
	require $block_render;
}