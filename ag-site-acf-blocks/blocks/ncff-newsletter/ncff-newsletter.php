<?php 
/**
 * NCFF Newsletter Block
 */

$id = 'ncff-newsletter-' . $block['id'];
$className = 'ncff-newsletter ';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
} 
$text = get_field('newsletter_text', 'options');
$link = get_field('newsletter_link', 'options');
?>
<div style="background: #fff">
<div class="container">
<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
<div class="ncff-newsletter__text-area">
    <h3 class="h2">Read<span class="ampersand">&</span>Connect </h3>
    <?php echo '<a href="'.get_the_permalink($link).'">'.get_the_post_thumbnail( $link, 'full').'</a>';
    echo '<div class="ncff-newsletter__text" >'.$text.'</div>'; ?> 
</div>
<div class="ncff-newsletter__form-area">
<p>Get the lastest news, recipes, articles and more, right to your inbox.</p>
<?php gravity_form( 3, false, false, false, '', false ); ?>
<p style="margin-bottom: .5em">Connect with us</p>
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
</div> <!-- container -->
</div> <!-- background white -->