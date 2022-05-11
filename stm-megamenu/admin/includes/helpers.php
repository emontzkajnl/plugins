<?php
function stm_mm_categories_for_select () {
    $categories = get_categories(array(
        'child_of'            => 0,
        'current_category'    => 0,
        'depth'               => 0,
        'echo'                => 1,
        'exclude'             => '',
        'exclude_tree'        => '',
        'feed'                => '',
        'feed_image'          => '',
        'feed_type'           => '',
        'hide_empty'          => 0,
        'hide_title_if_empty' => false,
        'hierarchical'        => true,
        'order'               => 'ASC',
        'orderby'             => 'name',
        'separator'           => '<br />',
        'show_count'          => 0,
        'show_option_all'     => '',
        'show_option_none'    => __( 'No categories' ),
        'style'               => 'list',
        'taxonomy'            => 'category',
        'title_li'            => __( 'Categories' ),
        'use_desc_for_title'  => 1,
    ));

    $cat_array_walker = new stm_mm_array_walker();
    $cat_array_walker->walk($categories, 4);
    $catOpt = $cat_array_walker->optList;

    return($catOpt);
}

class stm_mm_array_walker extends Walker {

    public $tree_type = 'category';
    public $db_fields = array ('parent' => 'parent', 'id' => 'term_id');

    public $optList = array(0 => 'Default');


    function start_lvl( &$output, $depth = 0, $args = array() ) {}

    function end_lvl( &$output, $depth = 0, $args = array() ) {}

    function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {

        $sep = '';

        for($q=0;$q<$depth;$q++) {
            $sep .= ' - ';
        }

        $this->optList[$category->term_id]= $sep . $category->name . '[id:' . $category->term_id . ']';
    }


    function end_el( &$output, $page, $depth = 0, $args = array() ) {}
}