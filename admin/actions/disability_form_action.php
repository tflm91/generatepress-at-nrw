<?php

require_once get_stylesheet_directory() . '/core/constants.php';

function save_disability(): void {
    if (isset($_POST['save_disability'])) {
        global $wpdb;

        $name = sanitize_text_field($_POST['disability_name']);
        $category_id = intval($_POST['disability_category']);
        $description = sanitize_textarea_field($_POST['disability_description']);
        $selected_product_categories = $_POST['selected_product_categories'] ?? [];

        if (!empty($_POST['disability_id'])) {
            $disability_id = intval($_POST['disability_id']);
            $wpdb->update(
                DISABILITY_TABLE,
                ['name' => $name, 'categoryId' => $category_id, 'description' => $description],
                ['id' => $disability_id]
            );

            $wpdb->delete(AIDS_WITH_DISABILITY_TABLE, ['impairmentId' => $disability_id]);
        } else {
            $wpdb->insert(DISABILITY_TABLE, [
                'name'=> $name,
                'categoryId' => $category_id,
                'description' => $description,
            ]);

            $disability_id = $wpdb->insert_id;
        }

        foreach ($selected_product_categories as $product_category_id) {
            $wpdb->insert(AIDS_WITH_DISABILITY_TABLE, [
                'impairmentId' => $disability_id,
                'categoryId' => $product_category_id
            ]);
        }

        wp_redirect(site_url('/beeintraechtigungsformen-editieren'));
        exit;
    }
}

add_action('init', 'save_disability');