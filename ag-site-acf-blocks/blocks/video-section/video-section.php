<?php 
/**
 * Video Section Block
 */

 $featured = get_field('featured_video');

 $id = 'video-section-' . $block['id'];
 $className = 'video-section ';
 if( !empty($block['className']) ) {
     $className .= ' ' . $block['className'];
 } 
 ?>
 
 <div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
 <div class="row">
     <div class="col-12 m-col-6">
         <div class="video-section__img-container">
         <a href="<?php echo get_the_permalink( $featured->ID ); ?>">
             <?php echo get_the_post_thumbnail( $featured->ID ); ?>
        </a>
         </div>
        <h3 class="video-section__title">
            <?php echo '<a class="unstyle-link" href="'.get_the_permalink($featured->ID ).'">'.get_the_title($featured->ID ).'</a>'; ?>
        </h3>
     </div>
     <div class="col-12 m-col-6 video-section__sidebar">
         <?php if(have_rows('sidebar_videos')):  while(have_rows('sidebar_videos')): the_row();
         $item = get_sub_field('video'); ?>
         <div class="video-section__sidebar-item">
             <div class="video-section__sb-img-container">
                 <a href="<?php echo get_the_permalink( $item->ID ); ?>">
                 <?php echo get_the_post_thumbnail( $item->ID ); ?>
                </a>
             </div>
             <?php echo '<h4><a class="unstyle-link" href="'.get_the_permalink($item->ID ).'">'.get_the_title($item->ID ).'</a></h4>'; ?>
             
         </div>
         <?php endwhile; endif; ?>

    </div>
 </div>
</div>

