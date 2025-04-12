<?php

require_once get_stylesheet_directory() . '/core/constants.php';

function save_disability_category(): void {
    if (isset($_POST['save_disability_category'])) {
        global $wpdb;

        $name = sanitize_text_field($_POST['category_name']);
        $description = sanitize_textarea_field($_POST['category_description']);
        $selected_links = $_POST['selected_links'] ?? [];

        if (!empty($_POST['category_id'])) {
            $category_id = intval($_POST['category_id']);
            $wpdb->update(
                DISABILITY_CATEGORY_TABLE,
                ['name' => $name, 'description' => $description],
                ['id' => $category_id]
            );
            $wpdb->delete(LINK_FOR_DISABILITY_TABLE, ['disabilityId' => $category_id]);
        } else {
            $wpdb->insert(DISABILITY_CATEGORY_TABLE, [
                'name'=> $name,
                'description' => $description,
            ]);
            $category_id = $wpdb->insert_id;
        }

        foreach ($selected_links as $link_id) {
            $wpdb->insert(LINK_FOR_DISABILITY_TABLE, [
                'disabilityId' => $category_id,
                'linkId' => $link_id
            ]);
        }

        wp_redirect(site_url('/behinderungskategorien-editieren'));
        exit;
    }
}

add_action('init', 'save_disability_category');