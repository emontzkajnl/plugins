
<?php 
/**
 * Social Cube Block
 */
?>
<div class="social-cube" >
    <?php $facebook = get_field('facebook', 'options');
    $twitter = get_field('twitter', 'options');
    $pinterest = get_field('pinterest', 'options');
    $youtube = get_field('youtube', 'options');
    ?>
    <h4 class="section-heading">Connect with us</h4>
    <ul>
        <?php
        echo $facebook ? '<li><a href="'.esc_url($facebook).'" target="_blank"><i class="fab fa-facebook-f"></i></a></li>' : '';
        echo $twitter ? '<li><a href="'.esc_url($twitter).'" target="_blank"><i class="fab fa-twitter"></i></a></li>' : '';
        echo $pinterest ? '<li><a href="'.esc_url($pinterest).'" target="_blank"><i class="fab fa-pinterest-p"></i></a></li>' : '';
        echo $youtube ? '<li><a class="color__primary" href="'.esc_url($youtube).'" target="_blank"><i class="fab fa-youtube"></i></a></li>' : '';
        ?>
    </ul>
    
</div>