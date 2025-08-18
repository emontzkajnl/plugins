<?php 
/**
 *THF Contests and Giveaways block
 */

$id = 'thf-contests-' . $block['id'];
$className = 'thf-contests ';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
} 

$args = array(
    'post_type'         => 'post',
    'post_status'       => 'publish',
    'posts_per_page'    => 2,
    'category_name'     => 'contests-giveaways'
);

$contest_query = new WP_Query($args); ?>
<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?> tan-bkgrnd">
<?php 
if ($contest_query->have_posts(  )): ?>

<h2 class="section-heading">Contests & Giveaways</h2>
<div class="thf-contests__container">
    <?php while ($contest_query->have_posts(  )): $contest_query->the_post(); 
    $ID = get_the_ID(); ?>
    <div class="thf-contests__item ">
        <div class="thf-contests__img-container object-fit-image">
            <a href="<?php echo get_the_permalink(); ?>">
            <?php echo get_the_post_thumbnail( $ID, 'medium_large'); ?>
            </a>
        </div>
        <div class="thf-contests__text-container">
            <h3><a  class="unstyle-link" href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title(); ?></a></h3>
            <p class="thf-contests__excerpt"><?php echo get_the_excerpt(  ); ?></p>
        </div>
    </div>
<?php endwhile;
echo '</div>'; // container
$max_pages = $contest_query->max_num_pages;
if ($max_pages > 1) {
    echo '<div style="text-align: center;">';
        echo '<button data-max="'.$max_pages.'" class="thf-contests__btn background__primary ">More Contests and Giveaways</button>';
        echo '</div>';
}
endif;
wp_reset_query(  );
?>
</div>

