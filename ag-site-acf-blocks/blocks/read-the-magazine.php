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
    <div class="col-12 m-col-9 mag-container">
    <a href="<?php echo get_the_permalink($magPost->ID ); ?>">  
        <?php echo get_the_post_thumbnail( $magPost->ID ); ?> <!-- add max-width 320 -->
    </a>
        <div class="rtm-text">
            <h4><?php echo $heading; ?></h4>
            <p><?php echo $magPost->post_content; ?></p>
            <a class="rtm-button" href="<?php echo get_the_permalink($magPost->ID ); ?>"><?php echo $btntext; ?></a>
        </div>
    </div>
    <div class="col-12 m-col-3">
        <div class="social-cube" >
        <?php $facebook = get_field('facebook', 'options');
        $pinterest = get_field('pinterest', 'options');
        $instagram = get_field('instagram', 'options');
        ?>
        <h4 class="section-heading">Connect with us</h4>
        <ul>
            <?php
            echo $facebook ? '<li><a href="'.esc_url($facebook).'" target="_blank"><i class="fab fa-facebook-square"></i></a></li>' : '';
            echo $pinterest ? '<li><a href="'.esc_url($pinterest).'" target="_blank"><i class="fab fa-pinterest-square"></i></a></li>' : '';
            echo $instagram ? '<li><a href="'.esc_url($instagram).'" target="_blank"><i class="fab fa-instagram"></i></a></li>' : '';
            ?>
        </ul>
        
        </div>
    </div>
</div>
</div>