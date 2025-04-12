<?php

require_once get_stylesheet_directory() . '/core/constants.php';

function save_product(): void {
    if (isset($_POST['save_product'])) {
        global $wpdb;

        $name = sanitize_text_field($_POST['product_name']);
        $description = sanitize_textarea_field($_POST['product_description']);
        $info_url = esc_url_raw($_POST['product_info_url']);
        $info_alt = sanitize_text_field($_POST['product_info_alt']);
        $selected_product_categories = $_POST['selected_product_categories'] ?? [];
        $available_general = isset($_POST['available_general']);
        $selected_universities = $_POST['selected_universities'] ?? [];
        $hidden = isset($_POST['hidden']);

        if (!empty($_POST['product_id'])) {
            $product_id = intval($_POST['product_id']);
            $wpdb->update(
                PRODUCT_TABLE,
                ['name' => $name, 'description' => $description, 'infoURL' => $info_url, 'infoAlt' => $info_alt, 'availableGeneral' => $available_general, 'hidden' => $hidden],
                ['id' => $product_id]
            );

            $wpdb->delete(CATEGORY_OF_PRODUCT_TABLE, ['productId' => $product_id]);
            if (!$available_general) {
                $wpdb->delete(AVAILABILITY_TABLE, ['productId' => $product_id]);
            }
        } else {
            $wpdb->insert(PRODUCT_TABLE, [
                'name'=> $name,
                'description' => $description,
                'infoURL' => $info_url,
                'infoAlt' => $info_alt,
                'availableGeneral' => $available_general,
                'hidden' => $hidden
            ]);
            $product_id = $wpdb->insert_id;
        }

        foreach ($selected_product_categories as $product_category_id) {
            $wpdb->insert(CATEGORY_OF_PRODUCT_TABLE, [
                'productId' => $product_id,
                'categoryId' => $product_category_id
            ]);
        }

        if (!$available_general) {
            foreach ($selected_universities as $university_id) {
                $wpdb->insert(AVAILABILITY_TABLE, [
                    'productId' => $product_id,
                    'universityId' => intval($university_id)
                ]);
            }
        }

        wp_redirect(site_url('/produkte-editieren'));
        exit;
    }
}

add_action('init', 'save_product');