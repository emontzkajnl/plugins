<?php 
/**
 * NCFF Event Filter Block
 */

$id = 'ncff-event-filter-' . $block['id'];
$className = 'ncff-event-filter ';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
} 

$formId = get_field('form_id');

?>
<div class="container">
<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
<h2 class="ncff-event-filter__title">Find North Carolina Events Near You</h2>
<div class="ncff-event-filter__container">
 <?php echo do_shortcode( '[searchandfilter id="'.$formId.'"]'); ?>
</div>
</div>
</div>