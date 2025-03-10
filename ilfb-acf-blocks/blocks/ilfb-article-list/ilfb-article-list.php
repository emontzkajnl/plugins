<?php 
/**
 * ILFB Article List Block
 */

$filter = get_field('filter') == 'popular' ? 'popular' : 'recent';
$layout = get_field('layout') == 'two' ? 'two' : 'three';
$layout_grid_class = $layout == 'two' ? 'col-12 m-col-6' : 'col-12 m-col-4';

$args = array(
    'post_type'         => 'post',
    'posts_per_page'    => 6,
    'post_status'       => 'publish'
);

if ($filter == 'popular') {
    $args['meta_key']   = 'wpb_post_views_count';
    $args['orderby']    = 'meta_value_num';
    $args['order']      = 'DESC';
} else {
    $args['orderby']    = 'date';
}

$articles = new WP_Query($args);


$id = 'ilfb-article-list-' . $block['id'];
$className = 'ilfb-article-list '. $layout;
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
} 
?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
<?php if ($articles->have_posts()):
    echo '<div class="align-center"><button class="background__primary font__serif">Most '.$filter.'</button></div>';
    echo '<div class="row">';
        while ($articles->have_posts()): $articles->the_post(); ?>

        <div class="<?php echo $layout_grid_class ?>" >
        
            <?php 
            echo '<div class="ilfb-article-list__image"><a href="'.get_the_permalink().'">';
            echo get_the_post_thumbnail(get_the_ID(),'full' ).'</a></div>'; ?>
            <div class="ilfb-article-list__text">
            <?php $yoast_primary_key = get_post_meta( get_the_ID(), '_yoast_wpseo_primary_category', TRUE ); 
            $cats = get_the_category( );
            $cat_id = $yoast_primary_key ? $yoast_primary_key :  $cats[0]->term_id ;
            if ($cat_id) { echo '<p class="cat-text color__primary"><a href="'.get_category_link( $cat_id ).'">'.get_cat_name($cat_id).'</a></p>'; }
             ?>
            <h3 class="ilfb-article-list__title"><a class="unstyle-link" href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title(); ?></a></h3>
            </div>
        </div>
        <?php endwhile;
        echo '</div>';
    endif;

?>
</div>