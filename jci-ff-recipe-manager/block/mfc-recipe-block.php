<?php 
/**
 * MFC Recipe Block
 */

$id = 'recipe-block-' . $block['id'];

$className = 'mfc-recipe-block';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
} 

$recipe = get_field('recipe_post');
$recipe_id = $recipe->ID;
// $text = get_field('custom_text');
$text = get_the_excerpt( $recipe_id );
$recipe_url = get_field('recipe_link', $recipe_id);
$recipe_img_url = get_field('recipe_image_link', $recipe_id);
$orientation = get_field('orientation');


?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className);  ?>">
    <div class="mfc-recipe-block__img-container">
        <img class="mfc-recipe-block__img src="<?php echo $recipe_img_url ; ?>" />
    </div>
    <div class="mfc-recipe-block__text-container">
        <img src="<?php echo plugin_dir_url(__FILE__); ?>../images/farm-flavor-icon-150x150.png" alt="" class="mfc-recipe-block__ff-logo">
    <?php echo '<h3 class="mfc-recipe-block__title">'.get_the_title($recipe_id ).'</h3>'; ?>
    <?php echo '<div class="mfc-recipe-block__text">'.$text.'</div>';  ?>
    <a href="<?php echo esc_url($recipe_url ); ?>"><button class="mfc-recipe-block__button">See the recipe on Farmflavor.com</button></a>
    </div>
    
    
    
</div>