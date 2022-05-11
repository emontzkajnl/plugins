<?php
function stmt_to_term_meta_field_color($field_key, $value)
{
    wp_enqueue_script( 'wp-color-picker' );
    wp_enqueue_style( 'wp-color-picker' );

    ?>
    <input type="text"
           name="<?php echo esc_attr($field_key) ?>"
           id="<?php echo esc_attr($field_key) ?>"
           value="<?php echo esc_attr($value); ?>"
           class="term-meta-text-field"/>

    <script type="text/javascript">
        jQuery(document).ready(function($){
            $('input[name*="color"]').wpColorPicker();
        });
    </script>
<?php }