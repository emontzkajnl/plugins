<?php 
/**
 * NCFF Recent Posts Block
 */

$id = 'ncff-recent-' . $block['id'];
$className = 'ncff-recent ';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
} 
$paged = ( get_query_var('page') ) ? get_query_var('page') : 1;
$args = array(
    'posts_per_page'        => 3,
    'post_type'             => 'post',
    'paginated'             => 1,
    'paged'                 => $paged,
    'post_status'           => 'publish',
);
$recent_query = new WP_Query($args);
// echo 'max pages is '.$recent_query->max_num_pages;
if ($recent_query->have_posts()): ?>
    <div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
    <?php echo '<h2 class="ncff-section-title">Most Recent</h2>';
    while ($recent_query->have_posts()):
        $recent_query->the_post(); 
        $ID = get_the_ID(); 
        $cat = get_the_category($ID); 
        $primary_cat = get_post_meta($ID,'_yoast_wpseo_primary_category', TRUE ); 
        $cat_name = $primary_cat ? get_the_category_by_ID($primary_cat) : $cat[0]->name;?>
        <div class="ncff-recent__container">
            <div class="ncff-recent__img-container">
                <?php echo '<a href="'.get_the_permalink().'">'.get_the_post_thumbnail($ID, 'full').'</a>'; ?>
            </div>
            <div class="ncff-recent__text-container">
            <p class="ncff-featured__cat"><?php echo $cat_name; ?></p>
            <h2 class="ncff-recent__title"><a class="unstyle-link" href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title(); ?></a></h2>
            <?php echo the_excerpt(  ); ?>
            </div>
        </div> <!-- container -->
        
    <?php endwhile;
    echo '</div>';
    $max_pages = $recent_query->max_num_pages;
    if ($max_pages > 1) {
        ?>
        <script>
            window.maxRecentPages = <?php echo $max_pages; ?>;
        </script>
        <?php echo '<div style="text-align: center;">';
            echo '<button class="ncff-recent__btn background__primary">Load More</button>';
            echo '</div>';
    }
    
    
endif;

?>

