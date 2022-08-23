<?php 
/**
 * Read the Magazine Block
 */

$id = 'read-the-magazine-' . $block['id'];

$className = 'read-the-magazine';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
} 
$magPost = get_posts(array(
    'number_posts'      => 1,
    'category'          => 86,

));
$magPost = $magPost[0];
$heading = get_field('heading');
$btntext = get_field('button_text');


?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
<div class="row" style="align-items: center;">
    <div class="mag-container">
    <a href="<?php echo get_the_permalink($magPost->ID ); ?>">  
        <?php echo get_the_post_thumbnail( $magPost->ID ); ?> <!-- add max-width 320 -->
    </a>
        <div class="rtm-text">
            <h4><?php echo $heading; ?></h4>
            <p><?php echo $magPost->post_content; ?></p>
            <a class="rtm-button" href="<?php echo get_the_permalink($magPost->ID ); ?>"><?php echo $btntext; ?></a>
        </div>
    </div>
</div>
</div>