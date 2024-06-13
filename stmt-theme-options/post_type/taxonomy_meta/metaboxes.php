<?php

require_once STMT_TO_DIR . '/post_type/taxonomy_meta/fields/default.php';
require_once STMT_TO_DIR . '/post_type/taxonomy_meta/fields/image.php';
require_once STMT_TO_DIR . '/post_type/taxonomy_meta/fields/color.php';

function stmt_to_term_meta_fields()
{
	return apply_filters('stmt_to_term_meta_fields', array(
		'category' => array(
			'category_color' => array(
				'label' => esc_html__('Category BG Color', 'stmt_theme_options'),
				'type'  => 'color',
			),
            'category_img' => array(
				'label' => esc_html__('Category BG Img', 'stmt_theme_options'),
				'type'  => 'image',
			),
		)
	));
}

add_action('init', 'stmt_to_register_term_meta');
function stmt_to_register_term_meta()
{
	$term_meta = stmt_to_term_meta_fields();
	foreach ($term_meta as $taxonomy => $meta_fields) {
		foreach ($meta_fields as $meta_field_key => $meta_field) {
			register_meta('term', $meta_field_key, 'stmt_to_sanitize_term_meta');
		}
	}
}

function stmt_to_sanitize_term_meta($value)
{
	return sanitize_text_field($value);
}

function stmt_to_get_term_meta_text($term_id, $term_key)
{
	$value = get_term_meta($term_id, $term_key, true);
	$value = stmt_to_sanitize_term_meta($value);
	return $value;
}

$taxonomies = stmt_to_term_meta_fields();
foreach ($taxonomies as $taxonomy => $fields) {
	add_action("{$taxonomy}_add_form_fields", 'stmt_to_add_term_meta_fields');
	add_action("{$taxonomy}_edit_form_fields", 'stmt_to_edit_term_meta_fields');

	add_action("edit_{$taxonomy}", 'stmt_to_save_term_meta_field');
	add_action("create_{$taxonomy}", 'stmt_to_save_term_meta_field');
}

function stmt_to_add_term_meta_fields($tax)
{
	$meta = stmt_to_term_meta_fields();
	$fields = $meta[$tax]; ?>
    <table class="form-table">
        <tbody>
		<?php foreach ($fields as $field_key => $field): ?>

            <tr class="form-field">
                <th scope="row">
                    <label for="<?php echo esc_attr($field_key) ?>"><?php echo sanitize_text_field($field['label']); ?></label>
                </th>
                <td>
					<?php switch ($field['type']) {
						case 'image':
							stmt_to_term_meta_field_image($field_key, '');
							break;
						case 'color':
                            stmt_to_term_meta_field_color($field_key, '');
							break;
						default:
							stmt_to_term_meta_field_default($field_key, '');
					} ?>
                </td>
            </tr>


		<?php endforeach; ?>
        </tbody>
    </table>
<?php }

function stmt_to_edit_term_meta_fields($term)
{
	$taxonomy = $term->taxonomy;
	$meta = stmt_to_term_meta_fields();
	$fields = $meta[$taxonomy]; ?>
    <table class="form-table">
        <tbody>
		<?php foreach ($fields as $field_key => $field):
			$value = stmt_to_get_term_meta_text($term->term_id, $field_key);
			?>

            <tr class="form-field">
                <th scope="row">
                    <label for="<?php echo esc_attr($field_key) ?>"><?php echo sanitize_text_field($field['label']); ?></label>
                </th>
                <td>
					<?php switch ($field['type']) {
						case 'image':
							stmt_to_term_meta_field_image($field_key, $value);
							break;
						case 'color':
                            stmt_to_term_meta_field_color($field_key, $value);
							break;
						default:
							stmt_to_term_meta_field_default($field_key, $value);
					} ?>
                </td>
            </tr>


		<?php endforeach; ?>
        </tbody>
    </table>
<?php }

function stmt_to_save_term_meta_field($term_id)
{
	if (!empty($_POST['taxonomy'])) {
		$taxonomy = sanitize_text_field($_POST['taxonomy']);
		$meta = stmt_to_term_meta_fields();
		if (!empty($meta[$taxonomy])) {
			$fields = $meta[$taxonomy];
			foreach ($fields as $field_key => $field) {
				$field_value = (!empty($_POST[$field_key])) ? sanitize_text_field($_POST[$field_key]) : '';
				update_term_meta($term_id, $field_key, $field_value);
			}
		}
	}
}