<?php 
/**
 * NCFF Featured Articles Block
 */

$id = 'ncff-featured-' . $block['id'];
$className = 'ncff-featured ';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
} 
$fa_one = get_field('top_article');
$fa_two = get_field('article_two');
$fa_three = get_field('article_three');
?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
<div class="ncff-featured__top-container">
<div class="ncff-featured__top-img-container">
    <a href="<?php echo get_the_permalink($fa_one ); ?>">
        <?php echo get_the_post_thumbnail( $fa_one, '1536×1536' ); ?>
    </a>
    </div>
    <div class="ncff-featured__top-text-container nc-panel">
    <h2><?php echo '<a href="'.get_the_permalink($fa_one).'" class="unstyle-link">'.get_the_title($fa_one).'</a>'; ?></h2>
    <p><?php echo get_the_excerpt( $fa_one ); ?></p>
    <a href="<?php echo get_the_permalink($fa_one ); ?>" class="align-center"><button class="background__primary">READ MORE</button></a>
    
    </div>
   
</div>
<div class="ncff-featured__bottom-container row">
    <?php foreach (array($fa_one, $fa_two) as $key => $value) { 
        $primary_cat = get_post_meta($value,'_yoast_wpseo_primary_category', TRUE );
        // echo 'primary is '.get_the_category_by_ID($primary_cat); ?>
        <div class="col-12 m-col-6">

        
        <div class="ncff-featured__sub-item nc-panel">
            <div class="ncff-featured__sub-item-text">
                <p class="ncff-featured__cat"><?php echo strtolower(get_the_category_by_ID($primary_cat));  ?></p>
                <h3 class="ncff-featured__bottom-title">
                    <?php echo '<a href="'.get_the_permalink($value).'" class="unstyle-link">'.get_the_title($value).'</a>'; ?>
                </h3>

            </div>
            <div class="ncff-featured__sub-item-img object-fit-image">
                <?php echo '<a href="'.get_the_permalink($value).'">'.get_the_post_thumbnail( $value, 'post-thumb').'</a>'; ?>
            </div>
        </div>
        </div>
   <?php } ?>
</div>

</div>