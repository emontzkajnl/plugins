<?php 

/**
 * Featured Article Plus Sidebar
 */
// featured_article, side_article_one
 $featured_image = get_field('featured_article');
 $side_article_one = get_field('side_article_one');
 $side_article_two = get_field('side_article_two');
 $fi_primary_key = get_post_meta( $featured_image->ID, '_yoast_wpseo_primary_category', TRUE );
 $one_primary_key = get_post_meta( $side_article_one->ID, '_yoast_wpseo_primary_category', TRUE );
 $two_primary_key = get_post_meta( $side_article_two->ID, '_yoast_wpseo_primary_category', TRUE );


$id = 'ilfb-ag-featured-post-sidebar-' . $block['id'];

$className = 'ilfb-afps row';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
} 
?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
<div class="col-12 m-col-9">
    <a href="<?php echo get_the_permalink($featured_image->ID); ?>">
        <?php echo get_the_post_thumbnail($featured_image->ID, '1536x1536'); ?>
    </a>
    <?php if ($fi_primary_key) { echo '<p class="cat-text color__primary font__sans-serif"><a href="'.get_category_link( $fi_primary_key).'">'.get_cat_name($fi_primary_key).'</a></p>'; } ?>
            
            <h2 class="ilfb-afps__featured-title title-link font__serif "><a class="unstyle-link" href="<?php echo get_the_permalink($featured_image->ID); ?>"><?php echo $featured_image->post_title; ?></a></h2>
            <p class="ilfb-afps__excerpt font__serif"><?php echo get_the_excerpt( $featured_image->ID); ?></p>
    
    </div>
<div class="col-12 m-col-3">
    <a href="<?php echo get_the_permalink($side_article_one->ID); ?>">
        <?php echo get_the_post_thumbnail($side_article_one->ID); ?>
    </a>
    <?php if ($one_primary_key) { echo '<p class="cat-text color__primary font__sans-serif"><a href="'.get_category_link( $one_primary_key).'">'.get_cat_name($one_primary_key).'</a></p>'; } ?>
    <a class="unstyle-link href="<?php echo get_the_permalink($side_article_one->ID); ?>">
        <h3 class="ilfb-afps__title title-link font__serif"><?php echo $side_article_one->post_title; ?></h3>
    </a>

    <a href="<?php echo get_the_permalink($side_article_two->ID); ?>">
        <?php echo get_the_post_thumbnail($side_article_two->ID); ?>
    </a>
    <?php if ($two_primary_key) { echo '<p class="cat-text color__primary font__sans-serif"><a href="'.get_category_link( $two_primary_key).'">'.get_cat_name($two_primary_key).'</a></p>'; } ?>
    <a class="unstyle-link href="<?php echo get_the_permalink($side_article_two->ID); ?>">
        <h3 class="ilfb-afps__title title-link font__serif"><?php echo $side_article_two->post_title; ?></h3>
    </a>
</div>
</div>
