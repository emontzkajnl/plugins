<?php 
/**
 *MIH Farm Directories and Maps Block
 */

$id = 'mih-recipes-' . $block['id'];
$className = 'mih-recipes ';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
} 
$paged = ( get_query_var('page') ) ? get_query_var('page') : 1;
$args = array(
    'paged'             => $paged,
    'posts_per_page'    => 4,
    'post_type'         => 'post',
    'category_name'     => 'recipes',
    'post_status'       => 'publish'
);
$recipe_query = new WP_Query($args);
if ($recipe_query->have_posts()):
   $max_pages = $recipe_query->max_num_pages; ?>
<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
<h2 class="section-heading">My Indiana Home Recipes</h2>
<div class="mih-recipes-container">
<div class="row">
    <?php while ($recipe_query->have_posts()):
    $recipe_query->the_post(); 
    $ID = get_the_ID(); ?>
    <div class="col-12 l-col-3 m-col-6">
    <div class="mih-directories__panel">
        <div class="mih-directories__img-container object-fit-image">
        <?php echo '<a href="'.get_the_permalink().'">'.get_the_post_thumbnail($ID, 'full').'</a>'; ?>
        </div>
        <div class="mih-directories__text-container">
                <h3 class="mih-directories__title"><a href="<?php echo get_the_permalink(); ?> " class="unstyle-link"><?php echo get_the_title(); ?></a></h3>
          
        </div>
    </div>
    </div>
    <?php endwhile;
    echo '</div></div>'; // row, mih-directories, mih-recipes-container
    if ($max_pages > 1) { 
        ?>
        <div style="text-align: center;">
        <button data-max="<?php echo $max_pages; ?>" id="load-more-mih-recipes">More Recipes</button>
    </div>
    <?php }
    echo '</div'; // container
endif;
wp_reset_postdata();