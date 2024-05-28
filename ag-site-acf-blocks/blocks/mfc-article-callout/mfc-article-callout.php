

<?php 
/**
 * Article Callout Block
 */

$id = 'mfc-article-callout-' . $block['id'];

$className = 'mfc-article-callout';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
} 

$article = get_field('article');
$article_id = $article->ID;
$categories = get_the_terms( $article_id, 'category' );
$cat = $categories[0];
// print_r($categories);
// echo get_term_link($cat->term_id );
?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
    <div class="mfc-article-callout__img-container">
        <?php echo get_the_post_thumbnail($article_id ); ?>
    </div>
    <div class="mfc-article-callout__text">
        <h4 class="mfc-cat-title"><a href ="<?php echo get_term_link($cat->term_id ); ?>"><?php echo $cat->name; ?></a></h4>
        <h3 class="mfc-article-callout__title"><a class="unstyle-link" href="<?php echo get_the_permalink($article_id ); ?>"><?php echo get_the_title($article_id); ?></a></h3>
        <p><?php echo get_the_excerpt($article_id ); ?></p>
    </div>
</div>