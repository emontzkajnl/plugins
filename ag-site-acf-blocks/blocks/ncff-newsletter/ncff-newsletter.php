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
    <h3 class="h2">Read <span class="ampersand">&</span>Connect</h3>
    <?php echo get_the_post_thumbnail( $link, 'full');
    echo $text; ?>
</div>
<div class="ncff-newsletter__form-area">
<p>Get the lastest news, recipes, articles and more, right to your inbox.</p>
<?php gravity_form( 3, false, false, false, '', false ); ?>
</div>
</div>
</div> <!-- container -->
</div> <!-- background white -->