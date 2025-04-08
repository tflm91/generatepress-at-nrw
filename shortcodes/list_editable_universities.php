<?php

require_once get_stylesheet_directory() . '/inc/database.php';
require_once get_stylesheet_directory() . '/constants.php';
require_once get_stylesheet_directory() . '/shortcodes/list_editable_items.php';

add_shortcode('list_editable_universities', 'list_editable_universities');

function list_editable_universities(): string {
    return list_editable_items(
            UNIVERSITY_TABLE,
        'name',
        'Hochschule',
        'display_by_name',
        'hochschule-bearbeiten',
        'delete-university',
        'Hochschulen'
    );
}

function delete_university_script(): void {
    generate_delete_function(
            'deleteUniversity',
        'delete_university',
    );

    generate_modal_content_script(
            'generateUniversityModal',
        'Hochschule',
        'deleteUniversity'
    );

    delete_empty_item_script(
            'delete-university',
        'generateUniversityModal'
    );
}

add_action('wp_footer', 'delete_university_script');

function delete_university(): void {
    global $wpdb;
    $university_id = intval($_POST['item_id']);
    $wpdb->delete(UNIVERSITY_TABLE, ['id' => $university_id]);
    wp_send_json(['success' => true]);
}

add_action('wp_ajax_delete_university', 'delete_university');