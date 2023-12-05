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
    <h3 class="page-header">Recipe</h3>
    <?php //echo get_the_post_thumbnail($recipe_id ); ?>
    <img src="<?php echo esc_attr($recipe_img_url ); ?>" />
    <?php echo '<h3 class="recipe-block-title">'.get_the_title($recipe_id ).'</h3>'; ?>
    <?php echo '<div class="recipe-block-text">'.$text.'</div>';  ?>
    <a href="<?php echo esc_url($recipe_url ); ?>"><button>See the recipe</button></a>
</div>
