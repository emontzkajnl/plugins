<?php

if ( ! defined( 'ABSPATH' ) ) exit; //Exit if accessed directly

?>
<h1><?php esc_html_e('Theme Options', 'stmt_theme_options'); ?></h1>

<?php
$id = $metabox['id'];
$sections = $metabox['args'][$id];

$data_vue = "data-vue='" . str_replace('\'', '', json_encode($sections)) . "'";
?>

<div class="stmt-to-settings" <?php echo ($data_vue); ?>>

    <?php require_once(STMT_TO_DIR . '/post_type/metaboxes/metabox-display.php'); ?>

    <div class="stmt_metaboxes_grid">
        <div class="stmt_metaboxes_grid__inner">
            <a href="#"
               @click.prevent="saveSettings('<?php echo esc_attr($id); ?>')"
               v-bind:class="{'loading': loading}"
               class="button load_button">
                <span><?php esc_html_e('Save Settings', 'stmt_theme_options'); ?></span>
                <i class="lnr lnr-sync"></i>
            </a>
        </div>
    </div>
</div>