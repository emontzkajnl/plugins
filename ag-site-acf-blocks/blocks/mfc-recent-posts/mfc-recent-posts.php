<?php 

/**
 * MFC Magazine Social
 */

 $id = 'mfc-recent-posts-' . $block['id'];

 $className = 'mfcrp';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
} 
$args = array(
    'post_type'         => 'post',
    'paged'             => 1,
    'posts_per_page'    => 6,
    'post_status'       => 'publish'
);
$recent_posts = new WP_Query($args);
$max_pages = $recent_posts->max_num_pages;
// print_r($recent_posts);
?>
<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
<?php if ($recent_posts->have_posts()): ?>
<h2 class="mfcrp__section-heading section-heading"><span>Most Recent</span></h2>
<?php $count = 1;
echo '<div class="row">';
while ($recent_posts->have_posts()): $recent_posts->the_post(); 
$cat = get_the_category(); 
$cat = $cat[0]; 
// if fifth post, insert advanced ads, close and open .row 
if ($count == 5 && function_exists('the_ad_placement')) {
    echo '</div><div style="text-align: center;">'; 
        the_ad_placement('in-content');
        echo '</div><div class="row">';
} 
?>
<div class="col-12 m-col-6 mfcrp__item">
    <div class="mfcrp__img">
        <a href="<?php echo get_the_permalink(); ?>">
        <?php echo get_the_post_thumbnail( get_the_ID(), 'large' ); ?>
        </a>
    </div>
    <?php if ($cat) {
        echo '<div class="mfc-cat-title"><a href="'.get_category_link( $cat ).'">'.$cat->name.'</a></div>';
    } ?>
    <h3 class="mfcrp__title"><?php echo get_the_title(); ?></h3>
</div>
<?php $count++; 
endwhile;
echo '</div>'; //row
if ($max_pages > 1) {
    echo '<button class="mfcrp__button">Load More</button>';
    echo '<div class="row mfcrp__results"></div>';
}
endif;
wp_reset_postdata(); ?>
</div>