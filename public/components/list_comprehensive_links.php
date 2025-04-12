<?php
require_once get_stylesheet_directory() . '/core/constants.php';
require_once get_stylesheet_directory() . '/core/database.php';
require_once get_stylesheet_directory() . '/core/display_helpers.php';

add_shortcode('comprehensive_links', 'list_comprehensive_links');

function list_comprehensive_links(): string {
    $links = get_all(ADDITIONAL_LINK_TABLE, ["comprehensive" => true], order_by: 'altText');
    $output = '';
    if (!empty($links)) {
        $output .= generate_link_list($links);
    }
    return $output;
}