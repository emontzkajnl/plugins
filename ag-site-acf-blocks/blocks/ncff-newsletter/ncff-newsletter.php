<?php 
/**
 * NCFF Newsletter Block
 */

$id = 'ncff-newsletter-' . $block['id'];
$className = 'ncff-newsletter ';
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
// echo 'id '.$magazine[0]->ID;
// print_r($magazine);


// gravity form id

// read and connect title wysiwyg
// newsletter description
// connect title


?>

<div class="container"> 
<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
<div class="ncff-newsletter__text-area">
    <!-- <h3 class="h2 read-connect">Read<span class="ampersand">&</span>Connect </h3> -->
    <h3 class="h2 read-connect"><?php echo $magazine_title; ?></h3>
    <?php echo '<a href="'.get_the_permalink($mag_id).'">'.get_the_post_thumbnail( $mag_id, array(205,280)).'</a>';
    echo '<div class="ncff-newsletter__text" >'.$magazine[0]->post_content.'</div>'; ?> 
</div>
<div class="ncff-newsletter__form-area">
<p class="ncff-newsletter__description"><?php echo $text; ?></p>
<?php gravity_form( $form, false, false, false, '', false ); ?>
<p style="margin-bottom: .5em"><?php echo $newsletter_title; ?></p>
<ul class="newsletter-social">
    <?php
    $facebook = get_field('facebook', 'options');
    $instagram = get_field('instagram', 'options');
    $pinterest = get_field('pinterest', 'options');
    $youtube = get_field('youtube', 'options');
        echo $facebook ? '<li class="facebook"><a href="'.esc_url($facebook).'" target="_blank"><i class="fab fa-facebook-f"></i></a></li>' : '';
        echo $instagram ? '<li class="instagram"><a href="'.esc_url($instagram).'" target="_blank"><i class="fab fa-instagram"></i></a></li>' : '';
        echo $pinterest ? '<li class="pinterest"><a href="'.esc_url($pinterest).'" target="_blank"><i class="fab fa-pinterest-p"></i></a></li>' : '';
        echo $youtube ? '<li class="youtube"><a href="'.esc_url($youtube).'" target="_blank"><i class="fab fa-youtube"></i></a></li>' : '';
    ?>
</ul>
</div>
</div>
</div> 
