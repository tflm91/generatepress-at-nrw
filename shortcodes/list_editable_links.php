<?php

require_once get_stylesheet_directory() . '/inc/database.php';
require_once get_stylesheet_directory() . '/constants.php';
require_once get_stylesheet_directory() . '/shortcodes/list_editable_items.php';
require_once get_stylesheet_directory() . '/shortcodes/item_delete_modal.php';


add_shortcode('list_editable_links', 'list_editable_links');


function display_by_alttext($item) {
    return $item->altText;
}

function list_editable_links(): string {
    return list_editable_items(
            ADDITIONAL_LINK_TABLE,
        'altText',
        'Link',
        'display_by_alttext',
        'weiterfuehrenden-link-bearbeiten',
        'delete-link',
        'Links'
    );
}

function delete_link_script(): void {
    generate_delete_function(
            'deleteLink',
        'delete_link'
    );

    generate_modal_content_script(
            'generateLinkModal',
        'diesen Link',
        'deleteLink'
    );

    delete_empty_item_script(
            'delete-link',
        'generateLinkModal'
    );
}

add_action('wp_footer', 'delete_link_script');

function delete_link(): void {
    global $wpdb;
    $link_id = intval($_POST['item_id']);
    $wpdb->delete(ADDITIONAL_LINK_TABLE, ['id' => $link_id]);
    wp_send_json(['success' => true]);
}

add_action('wp_ajax_delete_link', 'delete_link');