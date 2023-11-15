<?php 
/**
 * NCFF Article Link Block
 */

$id = 'ncff-infobox-' . $block['id'];
$className = 'ncff-infobox ';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
} 

$infobox = get_field('infobox');
?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
<div class="ncff-infobox__container">
    <?php echo $infobox; ?>
</div>
</div>

