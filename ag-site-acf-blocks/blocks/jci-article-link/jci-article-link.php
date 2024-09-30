<?php 
/**
 * JCI Article Link Block
 */

$id = 'jci-article-' . $block['id'];
$className = 'jci-article ';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
} 

$article = get_field('article_link');
?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
<div class="jci-article__container">
    <div class="jci-article__img-container">
        <a href="<?php echo get_the_permalink($article); ?>">
        <?php echo get_the_post_thumbnail( $article, 'medium' ); ?>
        </a>
    </div>
    <div class="jci-article__text-container">
        <h3 class="jci-article__read-more"><a class="unstyle-link" href="<?php echo get_the_permalink($article); ?>">Read More</a></h3>
    <h2 class="jci-article__title"><a class="unstyle-link" href="<?php echo get_the_permalink($article); ?>"><?php echo get_the_title($article); ?></a></h2>
    </div>
</div>
</div>

