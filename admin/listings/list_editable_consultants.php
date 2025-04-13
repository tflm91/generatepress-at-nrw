<?php

require_once get_stylesheet_directory() . '/core/constants.php';
require_once get_stylesheet_directory() . '/admin/listings/list_editable_items.php';
require_once get_stylesheet_directory() . '/admin/listings/item_delete_modal.php';

function list_editable_consultants(): string {
    return list_editable_items(
        CONSULTANT_TABLE,
        'name',
        'Beratungsperson',
        'display_by_name',
        '/beratungsperson-bearbeiten',
        'delete-consultant',
        'Beratungspersonen'
    );
}

add_shortcode('list_editable_consultants', 'list_editable_consultants');

function delete_consultant_script(): void {
    generate_delete_function(
        'deleteConsultant',
        'delete_consultant'
    );

    generate_modal_content_script(
        'generateConsultantModal',
        'die Beratungsperson',
        'deleteConsultant'
    );

    delete_empty_item_script(
        'delete-consultant',
        'generateConsultantModal'
    );
}

add_action('wp_footer',  'delete_consultant_script');

function delete_consultant(): void {
    global $wpdb;
    $consultant_id =  intval($_POST['item_id']);
    $wpdb->delete(CONSULTANT_TABLE, ['id' => $consultant_id]);
    wp_send_json(['success' => true]);
}

add_action('wp_ajax_delete_consultant', 'delete_consultant');