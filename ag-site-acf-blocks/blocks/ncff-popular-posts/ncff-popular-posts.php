<?php 
/**
 * NCFF Popular Posts Block
 */

$id = 'ncff-popular-' . $block['id'];
$className = 'ncff-popular ';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
} 
$paged = ( get_query_var('page') ) ? get_query_var('page') : 1;
$args = array(
    'posts_per_page'        => 4,
    'post_type'             => 'post',
    'paginated'             => 1,
    'paged'                 => $paged,
    'post_status'           => 'publish',
    'orderby'               => 'meta_value',
    'meta_key'              => 'wpb_post_views_count'
);
$pop_query = new WP_Query($args);
if ($pop_query->have_posts()):
    echo '<div class="container">';
    echo '<h2 class="h3">Most Popular</h2><div class="row">';
    while ($pop_query->have_posts()):
        $pop_query->the_post(); 
        $ID = get_the_ID(); 
        $visited = get_post_meta( $ID, 'wpb_post_views_count', TRUE); 
        $primary_cat = get_post_meta($ID,'_yoast_wpseo_primary_category', TRUE );?>
        <div class="col-12 m-col-3 s-col-6">
        <div class="ncff-popular__container nc-panel">
            <div class="ncff-popular__img-container">
                <?php echo '<a href="'.get_the_permalink().'">'.get_the_post_thumbnail( ).'</a>'; ?>
            </div>
            <div class="ncff-popular__text-container">
            <p class="ncff-featured__cat"><?php echo get_the_category_by_ID($primary_cat); ?></p>
            <h2 class="ncff-popular__title"><?php echo get_the_title(); ?></h2>
            </div>
            </div> <!-- container -->
        </div> <!-- col-12 -->
    <?php endwhile;
    echo '</div>'; // row
    echo '</div>'; // container
endif;

?>

<?php ?>

<!-- </div> -->
