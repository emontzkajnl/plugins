
<?php 
/**
 * Social Cube Block
 */
?>
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