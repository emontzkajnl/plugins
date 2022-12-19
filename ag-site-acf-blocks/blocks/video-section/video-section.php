<?php 
/**
 * Video Section Block
 */

$title = get_field('title') ? get_field('title') : 'Videos';
$featured = get_field('featured_video');
$yoast_primary_key = get_post_meta( $featured->ID, '_yoast_wpseo_primary_category', TRUE ); 
$cats = get_the_category( $featured->ID);
$cat_id = $yoast_primary_key ? $yoast_primary_key :  $cats[0]->term_id ; 

 $id = 'video-section-' . $block['id'];
 $className = 'video-section ';
 if( !empty($block['className']) ) {
     $className .= ' ' . $block['className'];
 } 
 ?>
 
 <div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
 <div class="align-center"><button class="background__primary fon__serif"><?php echo $title; ?></button></div>
 <div class="row">
     <div class="col-12 l-col-6">
         <div class="video-section__img-container">
         <a href="<?php echo get_the_permalink( $featured->ID ); ?>">
             <?php echo get_the_post_thumbnail( $featured->ID ); ?>
             <div class="play-btn"><i class="fa fa-play"></i></div>
        </a>
         </div>
         <?php if ($cat_id) { echo '<p class="cat-text"><a href="'.get_category_link( $cat_id ).'">'.get_cat_name($cat_id).'</a></p>'; } ?>
        <h3 class="video-section__title">
            <?php echo '<a class="unstyle-link" href="'.get_the_permalink($featured->ID ).'">'.get_the_title($featured->ID ).'</a>'; ?>
        </h3>
     </div>
     <div class="col-12 l-col-6 video-section__sidebar">
         <?php if(have_rows('sidebar_videos')):  while(have_rows('sidebar_videos')): the_row();
        $item = get_sub_field('video'); 
        $yoast_primary_key = get_post_meta( $item->ID, '_yoast_wpseo_primary_category', TRUE ); 
        $cats = get_the_category( $item->ID);
        $cat_id = $yoast_primary_key ? $yoast_primary_key :  $cats[0]->term_id ; 
         ?>
         <div class="video-section__sidebar-item">
             <div class="video-section__sb-img-container">
                 <a href="<?php echo get_the_permalink( $item->ID ); ?>">
                 <?php echo get_the_post_thumbnail( $item->ID ); ?>
                 <div class="play-btn"><i class="fa fa-play"></i></div>
                </a>
             </div>
             <div class="video-section__text-container">
                 <?php if ($cat_id) { echo '<p class="cat-text"><a href="'.get_category_link( $cat_id ).'">'.get_cat_name($cat_id).'</a></p>'; } 
                 echo '<h4><a class="unstyle-link" href="'.get_the_permalink($item->ID ).'">'.get_the_title($item->ID ).'</a></h4>'; ?>
             </div>
             
         </div>
         <?php endwhile; endif; ?>

    </div>
 </div>
</div>

