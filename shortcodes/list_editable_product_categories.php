<?php

require_once get_stylesheet_directory() . '/inc/database.php';
require_once get_stylesheet_directory() . '/constants.php';
require_once get_stylesheet_directory() . '/shortcodes/list_editable_items.php';

add_shortcode('list_editable_product_categories', 'list_editable_product_categories');

function list_editable_product_categories(): string {
    return list_editable_items(
            PRODUCT_CATEGORY_TABLE,
        'name',
        'Assistive Technologie',
        'display_by_name',
        'assistive-technologie-bearbeiten',
        'delete-product-category',
        'Assistive Technologien'
    );
}

function delete_product_category_script(): void {
    generate_delete_function(
            'deleteProductCategory',
            'delete_product_category'
    );

    generate_modal_content_script(
            'generateProductCategoryModal',
        'Assistive Technologie',
        'deleteProductCategory',
    );

    delete_empty_item_script(
            'delete-product-category',
        'generateProductCategoryModal'
    );
}

add_action('wp_footer', 'delete_product_category_script');

function delete_product_category(): void {
    global $wpdb;
    $category_id = intval($_POST['item_id']);
    $wpdb->delete(PRODUCT_CATEGORY_TABLE, ["id" => $category_id]);
    wp_send_json(['success' => true]);
}

add_action('wp_ajax_delete_product_category', 'delete_product_category');