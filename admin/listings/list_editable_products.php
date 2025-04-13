<?php

require_once get_stylesheet_directory() . '/core/constants.php';
require_once get_stylesheet_directory() . '/admin/listings/list_editable_items.php';
require_once get_stylesheet_directory() . '/admin/listings/item_delete_modal.php';

add_shortcode('list_editable_products', 'list_editable_products');

function list_editable_products(): string {
    return list_editable_items(
            PRODUCT_TABLE,
        'name',
        'Produkt',
        'display_by_name',
        'produkt-bearbeiten',
        'delete-product',
        'Produkte'
    );
}

function delete_product_script(): void {
    generate_delete_function(
            'deleteProduct',
        'delete_product'
    );

    generate_modal_content_script(
            'generateProductModal',
        'dieses Produkt',
        'deleteProduct'
    );

    delete_empty_item_script(
            'delete-product',
        'generateProductModal',
    );
}

add_action('wp_footer', 'delete_product_script');

function delete_product(): void {
    global $wpdb;
    $product_id = intval($_POST['item_id']);
    $wpdb->delete(PRODUCT_TABLE, ['id' => $product_id]);
    wp_send_json(['success' => true]);
}

add_action('wp_ajax_delete_product', 'delete_product');