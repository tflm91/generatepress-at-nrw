<?php

require_once get_stylesheet_directory() . '/constants.php';

function save_limitation(): void {
    if (isset($_POST['save_limitation'])) {
        global $wpdb;

        $name = sanitize_text_field($_POST['limitation_name']);
        $selected_categories = $_POST['selected_categories'] ?? [];

        if (!empty($_POST['limitation_id'])) {
            $limitation_id = intval($_POST['limitation_id']);
            $wpdb->update(
                FUNCTIONAL_LIMITATION_TABLE,
                ['name' => $name],
                ['id' => $limitation_id]
            );
            $wpdb->delete(AIDS_WITH_LIMITATION_TABLE, ['impairmentId' => $limitation_id]);
        } else {
            $wpdb->insert(FUNCTIONAL_LIMITATION_TABLE, ['name'=> $name]);
            $limitation_id = $wpdb->insert_id;
        }

        foreach ($selected_categories as $product_category_id) {
            $wpdb->insert(AIDS_WITH_LIMITATION_TABLE, [
                'impairmentId' => $limitation_id,
                'categoryId' => $product_category_id
            ]);
        }

        wp_redirect(site_url('/funktionseinschraenkungen-editieren'));
        exit;
    }
}

add_action('init', 'save_limitation');