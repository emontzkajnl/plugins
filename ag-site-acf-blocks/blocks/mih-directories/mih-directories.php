<?php 
/**
 *MIH Farm Directories and Maps Block
 */

$id = 'mih-directories' . $block['id'];
$className = 'mih-directories ';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
} 

$args = array(
    'post_type'     => 'post',
    'category_name' => 'directory',
    'post_status'   => 'publish'
);
$directory_query = new WP_Query($args);

if ($directory_query->have_posts()): ?>
<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
<h2 class="section-heading">Farm Directories and Maps</h2>
<div class="row">
    <?php while ($directory_query->have_posts()):
    $directory_query->the_post(); 
    $ID = get_the_ID(); ?>
    <div class="col-12 l-col-3 m-col-6">
    <div class="mih-directories__panel">
        <div class="mih-directories__img-container object-fit-image">
        <?php echo '<a href="'.get_the_permalink().'">'.get_the_post_thumbnail($ID, 'full').'</a>'; ?>
        </div>
        <div class="mih-directories__text-container">
            <h3 class="mih-directories__title"><a class="unstyle-link" href="<?php echo get_the_permalink( ); ?>"><?php echo get_the_title(); ?></a></h3>
        </div>
    </div>
    </div>
    <?php endwhile;
    echo '</div></div>'; // row, mih-directories
endif;
wp_reset_postdata();