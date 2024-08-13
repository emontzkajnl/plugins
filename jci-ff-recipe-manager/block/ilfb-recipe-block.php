<?php 
/**
 * ILFB Recipe Block
 */

$id = 'recipe-block-' . $block['id'];

$className = 'recipe-block';
$orientation = get_field('orientation') ? get_field('orientation').'-rblock' : 'right-rblock';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'] . ' '.$orientation;
} else {
    $className .= ' '.$orientation;
}

$recipe = get_field('recipe_post');
$recipe_id = $recipe->ID;
$text = get_field('custom_text');
$recipe_url = get_field('recipe_link', $recipe_id);
$recipe_img_url = get_field('recipe_image_link', $recipe_id);
$orientation = get_field('orientation');


?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className);  ?>">
    <?php //print_r($orientation); ?>
    <h3 class="page-header">Recipe</h3>
    <img src="<?php echo esc_attr($recipe_img_url ); ?>" />
    <?php echo '<h3 class="recipe-block-title">'.get_the_title($recipe_id ).'</h3>'; ?>
    <?php echo '<div class="recipe-block-text">'.$text.'</div>';  ?>
    <a href="<?php echo esc_url($recipe_url ); ?>"><button>See the recipe</button></a>
</div>