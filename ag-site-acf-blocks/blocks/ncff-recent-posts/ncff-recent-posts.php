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
$pop_query = new WP_Query($args);
if ($pop_query->have_posts()):
    echo '<div style="background: #fff"><div class="container">';
    echo '<h3 class="h2">Most Recent</h2>';
    while ($pop_query->have_posts()):
        $pop_query->the_post(); 
        $ID = get_the_ID(); 
        $primary_cat = get_post_meta($ID,'_yoast_wpseo_primary_category', TRUE );?>
        <div class="ncff-recent__container">
            <div class="ncff-recent__img-container">
                <?php echo '<a href="'.get_the_permalink().'">'.get_the_post_thumbnail($ID, 'medium-thumb' ).'</a>'; ?>
            </div>
            <div class="ncff-recent__text-container">
            <p class="ncff-featured__cat"><?php echo get_the_category_by_ID($primary_cat); ?></p>
            <h2 class="ncff-recent__title"><?php echo get_the_title(); ?></h2>
            <?php echo the_excerpt(  ); ?>
            </div>
            </div> <!-- container -->
        
    <?php endwhile;
    
endif;

?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
<?php ?>

</div>
</div> <!-- container -->
</div> <!-- background white -->