<?php
$widgets = array ('latest-comments', 'socials', 'latest-posts', 'widget-author', 'widget-mlchmp', 'widget-category-imgs');

foreach ($widgets as $widget) {
    require_once STM_GUTENBERG_INC_PATH . 'widgets/' . $widget . '.php';
}