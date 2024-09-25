<?php 

/**
 * Magazine Social
 */

 $id = 'magazine-social-' . $block['id'];

 $className = 'jcims';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
} 
$magPost = get_posts(array(
    'number_posts'      => 1,
    'post_type'         => 'magazine',
    'post_status'       => 'publish'
));
$magPost = $magPost[0];

$facebook = get_field('facebook', 'options');
$twitter = get_field('twitter', 'options');
$pinterest = get_field('pinterest', 'options');
?>
<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>" style="margin-bottom: 80px;">
    <div class="container">
        <div class="jcims__container">
        <div class="jcims__mag-container">
            <a href="<?php echo site_url( 'magazine'); ?>">
            <?php echo get_the_post_thumbnail( $magPost->ID, 'medium_large' ,array('style' => 'width: 200px; height: auto;')); ?>
            </a>
            <div class="jcims__mag-text">
            <h3 class="handwritten">the magazine</h3>
            <hr />
            <?php echo $magPost->post_content; ?>
            <!-- <a href="<?php //echo site_url( 'magazine'); ?>"><button>Latest Issue</button></a> -->
            </div>

        </div>
        <div class="jcims__social-container">
        <h3 class="handwritten">connect with us</h3>
        <p class="jcims__connect-text">Follow us to get news, recipes, share your thoughts and more.</p>
        <ul class="mih-social-icons">
                    <?php
                        echo $facebook ? '<li class="facebook"><a href="'.esc_url($facebook).'" target="_blank"><svg style="max-width: 25px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M80 299.3V512H196V299.3h86.5l18-97.8H196V166.9c0-51.7 20.3-71.5 72.7-71.5c16.3 0 29.4 .4 37 1.2V7.9C291.4 4 256.4 0 236.2 0C129.3 0 80 50.5 80 159.4v42.1H14v97.8H80z"/></svg></a></li>' : '';
                        echo $twitter ? '<li class="twitter"><a href="'.esc_url($twitter).'" target="_blank"><svg style="max-width: 25px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z"/></svg></a></li>' : '';
                        echo $pinterest ? '<li class="pinterest"><a href="'.esc_url($pinterest).'" target="_blank"><svg style="max-width: 25px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M204 6.5C101.4 6.5 0 74.9 0 185.6 0 256 39.6 296 63.6 296c9.9 0 15.6-27.6 15.6-35.4 0-9.3-23.7-29.1-23.7-67.8 0-80.4 61.2-137.4 140.4-137.4 68.1 0 118.5 38.7 118.5 109.8 0 53.1-21.3 152.7-90.3 152.7-24.9 0-46.2-18-46.2-43.8 0-37.8 26.4-74.4 26.4-113.4 0-66.2-93.9-54.2-93.9 25.8 0 16.8 2.1 35.4 9.6 50.7-13.8 59.4-42 147.9-42 209.1 0 18.9 2.7 37.5 4.5 56.4 3.4 3.8 1.7 3.4 6.9 1.5 50.4-69 48.6-82.5 71.4-172.8 12.3 23.4 44.1 36 69.3 36 106.2 0 153.9-103.5 153.9-196.8C384 71.3 298.2 6.5 204 6.5z"/></svg></a></li>' : '';
                    ?>
                </ul>
        </div>
        </div>
    </div>
</div>

