<?php

require_once get_stylesheet_directory() . '/core/database.php';
require_once get_stylesheet_directory() . '/core/constants.php';
require_once get_stylesheet_directory() . '/admin/listings/list_editable_items.php';
require_once get_stylesheet_directory() . '/admin/listings/item_delete_modal.php';

add_shortcode('list_editable_limitations', 'list_editable_limitations');

function list_editable_limitations(): string {
    return list_editable_items(
            FUNCTIONAL_LIMITATION_TABLE,
        'name',
        'Funktionseinschränkung',
        'display_by_name',
        'funktionseinschraenkung-bearbeiten',
        'delete-limitation',
        'Funktionseinschränkungen'
    );
}

function delete_limitation_script(): void {
    generate_delete_function(
            'deleteLimitation',
            'delete_limitation'
    );

    generate_modal_content_script(
            'generateLimitationModal',
            'diese Funktionseinschränkung',
            'deleteLimitation'
    );

    delete_empty_item_script(
            'delete-limitation',
            'generateLimitationModal'
    );
}

add_action('wp_footer', 'delete_limitation_script');

function delete_limitation(): void {
    global $wpdb;
    $limitation_id = intval($_POST['item_id']);
    $wpdb->delete(FUNCTIONAL_LIMITATION_TABLE, ['id' => $limitation_id]);
    wp_send_json(['success' => true]);
}

add_action('wp_ajax_delete_limitation', 'delete_limitation');