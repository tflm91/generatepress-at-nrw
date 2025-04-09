<?php

require_once get_stylesheet_directory() . '/constants.php';

function save_product_category(): void {
    if (isset($_POST['save_product_category'])) {
        global $wpdb;

        $name = sanitize_text_field($_POST['category_name']);
        $description = sanitize_textarea_field($_POST['category_description']);
        $selected_disabilities =  $_POST['selected_disabilities'];
        $selected_limitations = $_POST['selected_limitations'];
        $selected_products = $_POST['selected_products'];
        $selected_links = $_POST['selected_links'];

        if (!empty($_POST['category_id'])) {
            $category_id = intval($_POST['category_id']);
            $wpdb->update(
                PRODUCT_CATEGORY_TABLE,
                ['name' => $name, 'description' => $description],
                ['id' => $category_id]
            );

            $wpdb->delete(AIDS_WITH_DISABILITY_TABLE, ['categoryId' => $category_id]);
            $wpdb->delete(AIDS_WITH_LIMITATION_TABLE, ['categoryId' => $category_id]);
            $wpdb->delete(CATEGORY_OF_PRODUCT_TABLE, ['categoryId' => $category_id]);
            $wpdb->delete(LINK_FOR_AID_TABLE, ['aidId' => $category_id]);
        } else {
            $wpdb->insert(PRODUCT_CATEGORY_TABLE, [
                'name' => $name,
                'description' => $description,
            ]);

            $category_id = $wpdb->insert_id;
        }

        foreach ($selected_disabilities as $disability) {
            $wpdb->insert(AIDS_WITH_DISABILITY_TABLE, [
                'categoryId' => $category_id,
                'impairmentId' => $disability
            ]);
        }

        foreach ($selected_limitations as $limitation) {
            $wpdb->insert(AIDS_WITH_LIMITATION_TABLE, [
                'categoryId' => $category_id,
                'impairmentId' => $limitation
            ]);
        }

        foreach ($selected_products as $product) {
            $wpdb->insert(CATEGORY_OF_PRODUCT_TABLE, [
                'categoryId' => $category_id,
                'productId' => $product
            ]);
        }

        foreach ($selected_links as $link) {
            $wpdb->insert(LINK_FOR_AID_TABLE, [
                'aidId' => $category_id,
                'linkId' => $link
            ]);
        }

        wp_redirect(site_url('/assistive-technologien-editieren'));
        exit;
    }
}

add_action('init', 'save_product_category');