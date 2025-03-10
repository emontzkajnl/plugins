<?php 
/**
 * NCFF Article Link Block
 */

$id = 'ncff-article-' . $block['id'];
$className = 'ncff-article ';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
} 

$article = get_field('article_link');
$cat = get_the_category($article); 
$primary_cat = get_post_meta($article,'_yoast_wpseo_primary_category', TRUE ); 
$cat_name = $primary_cat ? get_the_category_by_ID($primary_cat) : $cat[0]->name;
?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
<div class="ncff-article__container">
    <div class="ncff-article__img-container">
        <a href="<?php echo get_the_permalink($article); ?>">
        <?php echo get_the_post_thumbnail( $article, 'medium' ); ?>
        </a>
    </div>
    <div class="ncff-article__text-container">
    <p class="ncff-featured__cat"><?php echo $cat_name; ?></p>
    <h2 class="ncff-article__title"><a class="unstyle-link" href="<?php echo get_the_permalink($article); ?>"><?php echo get_the_title($article); ?></a></h2>
    </div>
</div>
</div>

