<?php 
/**
 *JCI Recent Posts Block
 */

$id = 'jci-recent-' . $block['id'];
$className = 'jci-recent ';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
} 

$args = array(
    'posts_per_page'        => 4,
    'post_type'             => 'post',
    'paged'                 => 1,
    'post_status'           => 'publish',
);
$recent_query = new WP_Query($args);
// echo 'max pages is '.$recent_query->max_num_pages;
if ($recent_query->have_posts()): ?>
    <div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
    <?php echo '<h2 class="section-heading">Most Recent</h2>';
    echo '<div class="jci-recent-container">';
    while ($recent_query->have_posts()):
        $recent_query->the_post(); 
        $ID = get_the_ID(); 
        $cat = get_the_category($ID); 
        $primary_cat = get_post_meta($ID,'_yoast_wpseo_primary_category', TRUE ); 
        $cat_name = $primary_cat ? get_the_category_by_ID($primary_cat) : $cat[0]->name;?>
        <div class="jci-recent__item">
            <div class="jci-recent__img-container">
                <?php echo '<a href="'.get_the_permalink().'">'.get_the_post_thumbnail($ID, 'full').'</a>'; ?>
            </div>
            <div class="jci-recent__text-container">
            <p class="jci-recent__category"><?php echo $cat_name; ?></p>
            <h2 class="jci-recent__title"><a class="unstyle-link" href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title(); ?></a></h2>
            <div class="jci-recent__excerpt"><?php echo the_excerpt( ); ?></div>
            </div>
        </div> <!-- container -->
        
    <?php endwhile;
    echo '</div>'; // jci-recent-container
    $max_pages = $recent_query->max_num_pages;
    if ($max_pages > 1) {
        echo '<div style="text-align: center;">';
            echo '<button data-max="'.$max_pages.'" class="jci-recent__btn background__primary ">Load More</button>';
            echo '</div>';
    }
    
    echo '</div>'; // jci-recent
endif;
wp_reset_postdata();
?>

