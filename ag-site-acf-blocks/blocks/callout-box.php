<?php 
/**
 * Related Articles Block
 */

$id = 'callout-box-' . $block['id'];

$className = 'callout-box';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
} 
$wysiwyg = get_field('wysiwyg');
?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
<?php  echo $wysiwyg; ?>
</div>
