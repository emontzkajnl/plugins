<?php 
$id = 'mih-featured-' . $block['id'];
$className = 'mih-featured ';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
} 
$fa_one = get_field('featured_article');
$fa_two = get_field('article_two');
$fa_three = get_field('article_three');
$fa_four = get_field('article_four');

$cat = get_the_category($fa_one); 
$primary_cat = get_post_meta($fa_one,'_yoast_wpseo_primary_category', TRUE ); 
$cat_name = $primary_cat ? get_the_category_by_ID($primary_cat) : $cat[0]->name;
?>
<div class="lightblue-bkgrnd">
<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">

<div class="mih-featured__top-container">
    <div class="mih-featured__top-img-container object-fit-image">
        <a href="<?php echo get_the_permalink($fa_one ); ?>">
            <?php echo get_the_post_thumbnail( $fa_one, '1536x1536' ); ?>
        </a>
    </div>
    <div class="mih-featured__top-text-container nc-panel">
<p class="mih-featured__cat"><?php echo $cat_name; ?></p>
        <h2><?php echo '<a href="'.get_the_permalink($fa_one).'" class="unstyle-link">'.get_the_title($fa_one).'</a>'; ?></h2>
        <p class="mih-featured__excerpt"><?php echo get_the_excerpt( $fa_one ); ?></p>
    </div>
</div>

<div class="mih-featured__bottom-container row">
    <?php foreach (array($fa_two, $fa_three, $fa_four) as $key => $value) { 
        $primary_cat = get_post_meta($value,'_yoast_wpseo_primary_category', TRUE );
        // echo 'primary is '.get_the_category_by_ID($primary_cat); ?>
        <div class="col-12 l-col-4">

        
        <div class="mih-featured__sub-item">
        <div class="mih-featured__sub-item-img object-fit-image">
                <?php echo '<a href="'.get_the_permalink($value).'">'.get_the_post_thumbnail( $value, 'post-thumb').'</a>'; ?>
            </div>
            <div class="mih-featured__sub-item-text">
                <h3 class="mih-featured__bottom-title ">
                    <?php echo '<a href="'.get_the_permalink($value).'" class="unstyle-link">'.get_the_title($value).'</a>'; ?>
                </h3>

            </div>

        </div>
        </div>
   <?php } ?>
</div>

</div><!-- mih-featured -->
</div> <!-- lightblue-bkgrnd -->