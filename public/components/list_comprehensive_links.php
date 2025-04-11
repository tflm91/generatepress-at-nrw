<?php
require_once get_stylesheet_directory() . '/core/constants.php';
require_once get_stylesheet_directory() . '/core/database.php';
require_once get_stylesheet_directory() . '/core/display_helpers.php';

add_shortcode('comprehensive_links', 'list_comprehensive_links');

function list_comprehensive_links(): string {
    $links = get_by_condition(ADDITIONAL_LINK_TABLE, "comprehensive", true, 'altText');
    $output = '';
    if (!empty($links)) {
        $output .= generate_link_list($links);
    }
    return $output;
}