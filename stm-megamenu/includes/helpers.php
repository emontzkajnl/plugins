<?php
if( !function_exists('stm_mm_layout_name')) {
    function stm_mm_layout_name () {
        $layout = get_option('current_layout', 'personal');
        return $layout;
    }
}