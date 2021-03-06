<?php
/**
 * @var $field
 * @var $field_name
 * @var $section_name
 *
 */

$field_key = "data['{$section_name}']['fields']['{$field_name}']";

include STMT_TO_DIR . '/post_type/metaboxes/components_js/image.php';
?>

<label v-html="<?php echo esc_attr($field_key); ?>['label']"></label>

<stm-image v-bind:stored_image="<?php echo esc_attr($field_key); ?>['value']"
             v-on:get-image="<?php echo esc_attr($field_key); ?>['value'] = $event"></stm-image>

<input type="hidden"
       name="<?php echo esc_attr($field_name);?>"
       v-model="<?php echo esc_attr($field_key); ?>['value']" />