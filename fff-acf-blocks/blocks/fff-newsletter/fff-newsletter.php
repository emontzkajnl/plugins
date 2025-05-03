<?php 
/**
 * FFF Newsletter Block
 */

$id = 'fff-newsletter-' . $block['id'];
$className = 'fff-newsletter ';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
} 

$link = get_field('newsletter_link', 'options');
$magazine_title = get_field('magazine_title', 'options', false, false);
$form = get_field('form_id', 'options');
$newsletter_title = get_field('newsletter_title', 'options');
$text = get_field('newsletter_text', 'options');
$magazine = get_posts(array('numberposts' => 1, 'post_type' => 'magazine')); // ID, post_content, post_title
$mag_id = $magazine[0]->ID;
// $magazine_title = get_the_title($mag_id);
// echo 'id '.$magazine[0]->ID;
// print_r($magazine);


// gravity form id

// read and connect title wysiwyg
// newsletter description
// connect title


?>

<div class="container"> 
<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
<div class="fff-newsletter__text-area">
    <!-- <h3 class="h2 read-connect">Read<span class="ampersand">&</span>Connect </h3> -->
    <h3 class="h2 read-connect"><?php echo $magazine_title; ?></h3>
    <?php echo '<a href="'.get_the_permalink($mag_id).'">'.get_the_post_thumbnail( $mag_id, array(205,280)).'</a>';
    echo '<div class="fff-newsletter__text" >'.$magazine[0]->post_content.'</div>'; ?> 
</div>
<div class="fff-newsletter__form-area">
    <h3 class="h2">Connect with Us</h3>
<p class="fff-newsletter__description"><?php echo $text; ?></p>
<p style="margin-bottom: .5em"><?php echo $newsletter_title; ?></p>
<ul class="newsletter-social">
    <?php
    $facebook = get_field('facebook', 'options');
    $instagram = get_field('instagram', 'options');
    $pinterest = get_field('pinterest', 'options');
    $youtube = get_field('youtube', 'options');
    $twitter = get_field('twitter', 'options');
        echo $facebook ? '<li class="facebook"><a href="'.esc_url($facebook).'" target="_blank"><img src="'.get_stylesheet_directory_uri(  ).'/assets/images/social-icons/facebook.svg" /></a></li>' : '';
        echo $instagram ? '<li class="instagram"><a href="'.esc_url($instagram).'" target="_blank"><img src="'.get_stylesheet_directory_uri(  ).'/assets/images/social-icons/instagram.svg" /></a></li>' : '';
        echo $pinterest ? '<li class="pinterest"><a href="'.esc_url($pinterest).'" target="_blank"><img src="'.get_stylesheet_directory_uri(  ).'/assets/images/social-icons/pinterest.svg" /></a></li>' : '';
        echo $youtube ? '<li class="youtube"><a href="'.esc_url($youtube).'" target="_blank"><img src="'.get_stylesheet_directory_uri(  ).'/assets/images/social-icons/youtube.svg" /></a></li>' : '';
        echo $twitter ? '<li class="twitter"><a href="'.esc_url($twitter).'" target="_blank"><img src="'.get_stylesheet_directory_uri(  ).'/assets/images/social-icons/twitter.svg" /></a></li>' : '';
    ?>
</ul>
</div>
</div>
</div> 
