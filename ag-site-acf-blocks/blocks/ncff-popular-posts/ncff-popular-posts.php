<?php 
/**
 * NCFF Popular Posts Block
 */

$id = 'ncff-popular-' . $block['id'];
$className = 'ncff-popular offwhite-bkgrnd ';
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
    'orderby'               => 'meta_value_num',
    'order'                 => 'DESC',
    'meta_key'              => 'wpb_post_views_count'
);
$pop_query = new WP_Query($args); ?>
<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
<?php if ($pop_query->have_posts()):

    echo '<h2 class="ncff-section-title">Most Popular</h2><div class="ncff-popular__row"><div class="row">';
    while ($pop_query->have_posts()):
        $pop_query->the_post(); 
        $ID = get_the_ID(); 
        $visited = get_post_meta( $ID, 'wpb_post_views_count', TRUE); 
        $cat = get_the_category($ID); 
        $primary_cat = get_post_meta($ID,'_yoast_wpseo_primary_category', TRUE ); 
        $cat_name = $primary_cat ? get_the_category_by_ID($primary_cat) : $cat[0]->name;
        ?>
        <div class="col-12 m-col-3 s-col-6">
        <div class="ncff-popular__container nc-panel">
            <div class="ncff-popular__img-container">
                <?php echo '<a href="'.get_the_permalink().'">'.get_the_post_thumbnail( ).'</a>'; ?>
            </div>
            <div class="ncff-popular__text-container">
            <p class="ncff-featured__cat"><?php echo $cat_name; ?></p>
            <h3 class="ncff-popular__title"><a class="unstyle-link" href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title(); ?></a></h3>
            </div>
            </div> <!-- container -->
        </div> <!-- col-12 -->
    <?php endwhile;
    echo '</div></div>'; // popular__row, row
    $max_pages = $pop_query->max_num_pages;
    if ($max_pages > 1) {
        ?>
        <script>
            window.maxPopularPages = <?php echo $max_pages; ?>;
        </script>
        <?php echo '<div style="text-align: center;">';
            echo '<button class="ncff-popular__btn background__primary">Load More</button>';
            echo '</div>';
    }
endif;
wp_reset_postdata(  );
?>
</div>
