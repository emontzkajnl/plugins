<?php 

/**
 * Featured Article Plus Sidebar
 */
// featured_article, side_article_one
 $featured_image = get_field('featured_article');
 $side_article_one = get_field('side_article_one');
 $side_article_two = get_field('side_article_two');
 $side_article_three = get_field('side_article_three');

$id = 'ag-featured-post-sidebar-' . $block['id'];

$className = 'ag-featured-post-sidebar row';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
} ?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
<div class="col-12 m-col-9">
    <a class="title-link" href="<?php echo get_the_permalink($featured_image->ID); ?>">
        <div class="faps-featured" style="background-image: url('<?php echo get_the_post_thumbnail_url( $featured_image->ID, 'large' ); ?>');">
            <h2 class="faps-title title-link"><?php echo $featured_image->post_title; ?></h2>
        </div>
    </a>
    </div>
<div class="col-12 m-col-3">
    <a class="title-link faps-sideblock" href="<?php echo get_the_permalink($side_article_one->ID); ?>" style="background-image: url('<?php echo get_the_post_thumbnail_url( $side_article_one->ID, 'large' ); ?>');">
        <h3 class="faps-title title-link"><?php echo $side_article_one->post_title; ?></h3>
    </a>
    <a class="title-link faps-sideblock" href="<?php echo get_the_permalink($side_article_two->ID); ?>"  style="background-image: url('<?php echo get_the_post_thumbnail_url( $side_article_two->ID, 'large' ); ?>');">
        <h3 class="faps-title title-link"><?php echo $side_article_two->post_title; ?></h3>
    </a>
    <a class="title-link faps-sideblock" href="<?php echo get_the_permalink($side_article_three->ID); ?>" style="background-image: url('<?php echo get_the_post_thumbnail_url( $side_article_three->ID, 'large' ); ?>');">
        <h3 class="faps-title title-link"><?php echo $side_article_three->post_title; ?></h3>
    </a>
</div>
</div>
