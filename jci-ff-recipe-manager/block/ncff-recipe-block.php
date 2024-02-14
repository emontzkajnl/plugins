<?php 
/**
 * NCFF Recipe Block
 */

$id = 'recipe-block-' . $block['id'];

$className = 'ncff-recipe-block';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
} 

$recipe = get_field('recipe_post');
$recipe_id = $recipe->ID;
$text = get_field('custom_text');
$recipe_url = get_field('recipe_link', $recipe_id);
$recipe_img_url = get_field('recipe_image_link', $recipe_id);

?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className);  ?>">
<div class="ncff-recipe-block__img-container">
    <img src="<?php echo esc_attr($recipe_img_url ); ?>" />
</div>
<div class="ncff-recipe-block__text-container">
    <img class="ncff-recipe-block__icon" src="<?php echo plugin_dir_url(__FILE__); ?>../images/farm-flavor-icon-150x150.png" alt="">
    <?php echo '<h3 class="ncff-recipe-block__title">'.get_the_title($recipe_id ).'</h3>'; ?>
    <?php echo '<div class="ncff-recipe-block__text">'.$text.'</div>';  ?>
    <a href="<?php echo esc_url($recipe_url ); ?>"><button class="ncff-recipe-block__button">See the recipe on Farmflavor.com</button></a>
</div>
    
</div>
