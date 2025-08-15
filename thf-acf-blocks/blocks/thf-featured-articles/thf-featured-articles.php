<?php 
/**
 * THF Featured Articles Block
 */

$id = 'thf-featured-' . $block['id'];
$className = 'thf-featured ';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
} 
$fa_one = get_field('top_article');
$fa_two = get_field('article_two');
$fa_three = get_field('article_three');
$fa_four = get_field('article_four');

$primary_cat = get_post_meta($fa_one,'_yoast_wpseo_primary_category', TRUE );
$cats = get_the_category( $fa_one);
$cat_id = $primary_cat ? $primary_cat :  $cats[0]->term_id ; 
$cat_name = $primary_cat ? get_the_category_by_ID($primary_cat) : $cats[0]->name;
?>
<div class="tan-bkgrnd">
<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
<div class="thf-featured__top-container">
<div class="thf-featured__top-img-container">
    <a href="<?php echo get_the_permalink($fa_one ); ?>">
        <?php echo get_the_post_thumbnail( $fa_one, '1536×1536' ); ?>
    </a>
    </div>
    <div class="thf-featured__top-text-container nc-panel">
        <p class="thf-cat-title"><a href="<?php echo get_category_link( $cat_id ); ?>"><?php echo strtolower($cat_name);  ?></a></p>
    <h2><?php echo '<a href="'.get_the_permalink($fa_one).'" class="unstyle-link">'.get_the_title($fa_one).'</a>'; ?></h2>
    <p><?php echo get_the_excerpt( $fa_one ); ?></p>
    <a href="<?php echo get_the_permalink($fa_one ); ?>" class="align-center"><button class="background__primary">READ MORE</button></a>
    
    </div>
   
</div>
<div class="thf-featured__bottom-container row">
    <?php foreach (array($fa_two, $fa_three, $fa_four) as $key => $value) { 
        $primary_cat = get_post_meta($value,'_yoast_wpseo_primary_category', TRUE );
        $cats = get_the_category( $value);
        $cat_id = $primary_cat ? $primary_cat :  $cats[0]->term_id ; 
        $cat_name = $primary_cat ? get_the_category_by_ID($primary_cat) : $cats[0]->name;
        // echo 'primary is '.get_the_category_by_ID($primary_cat); ?>
        <div class="col-12 m-col-4">
        <div class="thf-featured__sub-item">
            <div class="thf-featured__sub-item-text">
                <p class="thf-featured__cat"><a href="<?php echo get_category_link( $cat_id ); ?>"><?php echo strtolower($cat_name);  ?></a></p>
                <h3 class="thf-featured__bottom-title ">
                    <?php echo '<a href="'.get_the_permalink($value).'" class="unstyle-link">'.get_the_title($value).'</a>'; ?>
                </h3>

            </div>
            <div class="thf-featured__sub-item-img object-fit-image">
                <?php echo '<a href="'.get_the_permalink($value).'">'.get_the_post_thumbnail( $value, 'post-thumb').'</a>'; ?>
            </div>
        </div>
        </div>
   <?php } ?>
</div>

</div>
</div> <!-- container -->