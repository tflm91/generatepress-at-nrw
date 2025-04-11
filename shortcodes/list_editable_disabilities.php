<?php

require_once get_stylesheet_directory() . '/inc/database.php';
require_once get_stylesheet_directory() . '/constants.php';
require_once get_stylesheet_directory() . '/shortcodes/list_editable_items.php';

add_shortcode('list_editable_disabilities', 'list_editable_disabilities');

function list_editable_disabilities(): string {
    return list_editable_items(
            DISABILITY_TABLE,
        'name',
        'Beeinträchtigungsform',
        'display_by_name',
        'beeintraechtigungsform-bearbeiten',
        'delete-disability',
        'Beeinträchtigungsformen'
    );
}

function delete_disability_script(): void {
    generate_delete_function(
        'deleteDisability',
        'delete_disability'
    );

    generate_modal_content_script(
        'generateDisabilityModal',
        'diese Beeinträchtigungsform',
        'deleteDisability'
    );

    delete_empty_item_script(
        'delete-disability',
        'generateDisabilityModal'
    );
}

add_action('wp_footer', 'delete_disability_script');

function delete_disability(): void {
    global $wpdb;
    $disability_id = intval($_POST['item_id']);
    $wpdb->delete(DISABILITY_TABLE, ["id" => $disability_id]);
    wp_send_json(['success' => true]);
}

add_action('wp_ajax_delete_disability', 'delete_disability');