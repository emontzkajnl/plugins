<?php 
/**
 * Featured Article Plus Sidebar
 */

 $title = get_field('section_title');
 $columns = get_field('columns');
 $articles = get_field('articles');
 $num_articles = count($articles);
 $grid_class = $columns == 'two' ? 'col-12 m-col-6' : 'col-12 m-col-4';
 $counter = 1;


$id = 'custom-article-list-' . $block['id'];

$className = 'custom-article-list';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
} ?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
<h3 class="section-heading"><?php _e($title, 'acf-blocks'); ?></h3>
<div class="row">
<?php foreach($articles as $a) {
    $article = $a['article'];
    $categories = wp_get_post_categories( $article->ID);
    // $category = $categories[0];
    
// echo '<div class="row">';
echo '<div class="'.$grid_class.'">'; ?>
<a class="title-link" href="<?php echo get_the_permalink( $article->ID ); ?>">
<?php echo get_the_post_thumbnail( $article->ID, 'medium_large'); 
// print_r($categories); 
$yoast_primary_key = get_post_meta( $article->ID, '_yoast_wpseo_primary_category', TRUE ); 
if ($yoast_primary_key) { echo '<p class="cat-text">'.get_cat_name($yoast_primary_key).'</p>'; } ?>

<h2><?php _e($article->post_title, 'acf-blocks'); ?></h2>
</a></div>
<?php 
if ($counter == $num_articles) {
    echo '</div>'; // close row
} elseif ($columns == 'two') {
    echo $counter % 2 == 0 ? '</div><div class="row">' : '';
} else {
    echo $counter % 3 == 0 ? '</div><div class="row">' : '';
}
$counter++;
} ?>
</div>