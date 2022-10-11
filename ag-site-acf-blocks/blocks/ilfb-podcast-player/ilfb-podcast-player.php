<?php 
/**
 * ILFB Podcast Player Block
 */

$id = 'podcast-player-' . $block['id'];

$className = 'podcast-player';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
} 

?>
<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
<p>podcast player.</p>
</div>