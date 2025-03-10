<?php 
/**
 * NCFF Infobox  Block
 */

$id = 'ncff-infobox-' . $block['id'];
$className = 'ncff-infobox ';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
} 

$infobox = get_field('infobox');
$link = get_field('link');
?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
<div class="ncff-infobox__container">
    <?php echo $infobox; 
    if ($link) {
        $text = $link['title'] ? $link['title'] : "Learn More";
        echo '<div style="text-align: center;">';
        echo '<a href="'.$link['url'].'" target="'.$link['target'].'" ><button class="background__primary">'.$text.'</button></a>';
        echo '</div>';
    } ?>
</div>
</div>

