<?php 
/**
 * Related Articles Block
 */

$id = 'related-articles-' . $block['id'];

$className = 'related-articles';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
} 


$categories = get_the_terms( get_the_ID(), 'category' );
if ($categories):
    $cat_ids = [];
    foreach($categories as $c){
        $cat_ids[] = $c->term_id;
    }
    $rel_args = array(
        'post_type'         => 'post',
        'post_status'       => 'publish',
        'posts_per_page'    => 3,
        'orderby'           => 'rand',
        'category__in'      => $cat_ids
    );
    $rel_articles = new WP_Query($rel_args);
    if ($rel_articles->have_posts( )):
        while ($rel_articles->have_posts( )): $rel_articles->the_post( );
        
        endwhile;
    endif;
    wp_reset_postdata( );
endif;
// $cat = $categories[0];
// print_r($categories);
// echo get_term_link($cat->term_id );
?>