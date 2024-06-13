<?php
/**
 * @var $field_name
 * @var $section_name
 *
 */

$field_key = "data['{$section_name}']['fields']['{$field_name}']";

include STMT_TO_DIR . '/post_type/metaboxes/components_js/multiselect.php';

if ($field_name == 'cryptos') {
    $crypto = json_decode(file_get_contents(STMT_TO_DIR . 'post_type/metaboxes/assets/js/cryptodata.json'), true);

	$currencies = array();
	if (!empty($crypto)) {
		foreach ($crypto as $currency_key => $currency_value) {
			$currencies[] = array(
				'name'  => $currency_value['name'],
				'value' => $currency_value['id']
			);
		}
	}

}

?>

<label v-html="<?php echo esc_attr($field_key); ?>['label']"></label>

<stmt-multiselect v-bind:options='<?php echo str_replace('\'', '', json_encode($currencies)); ?>'
                  v-bind:selected_options='<?php echo esc_attr($field_key); ?>["value"]'
                  v-on:get-selects="<?php echo esc_attr($field_key); ?>['value'] = $event"></stmt-multiselect>

<input type="hidden"
       name="<?php echo esc_attr($field_name); ?>"
       v-model="<?php echo esc_attr($field_key); ?>['value']"/>

