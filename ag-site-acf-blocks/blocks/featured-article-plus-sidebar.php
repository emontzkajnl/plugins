<?php 

/**
 * Featured Article Plus Sidebar
 */
// featured_article, side_article_one
 $featured_image = get_field('featured_article');
 $side_article_one = get_field('side_article_one');
 $side_article_two = get_field('side_article_two');
 $side_article_three = get_field('side_article_three');

 $featured_cat = get_the_category($featured_image->ID);
 $article_one_cat = get_the_category($side_article_one->ID);
 $article_two_cat = get_the_category($side_article_two->ID);
 $article_three_cat = get_the_category($side_article_three->ID);
 

$id = 'ag-featured-post-sidebar-' . $block['id'];

$className = 'faps row';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
} ?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
<div class="col-12 m-col-8 faps__featured-col">
    <a class="title-link" href="<?php echo get_the_permalink($featured_image->ID); ?>">
    <?php echo get_the_post_thumbnail( $featured_image->ID, 'large', array('class' => 'faps__featured') ); ?>
        <!-- <div class="faps-featured" style="background-image: url('<?php //echo get_the_post_thumbnail_url( $featured_image->ID, 'large' ); ?>');"></div> -->
    </a>
    <?php if ($article_one_cat) {
        echo '<p class="mfc-cat-title"><a href="'.get_category_link($article_one_cat[0]).'">'.$article_one_cat[0]->name.'</a></p>';
    } ?>
    <h2 class="faps__featured-title title-link"><a class="title-link" href="<?php echo get_the_permalink($featured_image->ID); ?>"><?php echo $featured_image->post_title; ?></a></h2>
    <?php echo '<p class="faps__featured-excerpt">'.get_the_excerpt( $featured_image->ID ).'</p>'; ?>
    </div>
<div class="col-12 m-col-4 faps__sidebar-col">
    <div class="faps__side-block">
    <a class="faps__img-container" href="<?php echo get_the_permalink($side_article_one->ID); ?>"><?php echo get_the_post_thumbnail( $side_article_one->ID, 'large'); ?>  </a>  
    <div class="faps__side-text">
        <?php if ($article_one_cat) {
            echo '<p class="mfc-cat-title"><a href="'.get_category_link($article_one_cat[0]).'">'.$article_one_cat[0]->name.'</a></p>';
        } ?>
        <h3 class="faps__title title-link"><a class="title-link" href="<?php echo get_the_permalink($side_article_one->ID); ?>"><?php echo $side_article_one->post_title; ?></a></h3>
    </div>
    </div>
    <div class="faps__side-block">
    <a class="faps__img-container" href="<?php echo get_the_permalink($side_article_two->ID); ?>" ><?php echo get_the_post_thumbnail( $side_article_two->ID, 'large'); ?>   </a> 
    <div class="faps__side-text">
        <?php if ($article_two_cat) {
            echo '<p class="mfc-cat-title"><a href="'.get_category_link($article_two_cat[0]).'">'.$article_two_cat[0]->name.'</a></p>';
        } ?>
   
        <h3 class="faps__title title-link"><a class="title-link" href="<?php echo get_the_permalink($side_article_two->ID); ?>"><?php echo $side_article_two->post_title; ?></a></h3>
    </div>
    </div>
    <div class="faps__side-block">
    <a class="faps__img-container" href="<?php echo get_the_permalink($side_article_three->ID); ?>"   ><?php echo get_the_post_thumbnail( $side_article_three->ID, 'large'); ?>   </a> 
        <div class="faps__side-text">
        <?php if ($article_three_cat) {
            echo '<p class="mfc-cat-title"><a href="'.get_category_link($article_three_cat[0]).'">'.$article_three_cat[0]->name.'</a></p>';
        } ?>

        <h3 class="faps__title title-link"><a class="title-link" href="<?php echo get_the_permalink($side_article_three->ID); ?>"><?php echo $side_article_three->post_title; ?></a></h3>
        </div>
    </div>
</div>
</div>
