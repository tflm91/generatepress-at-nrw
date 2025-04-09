<?php

require_once get_stylesheet_directory() . '/constants.php';

function save_link(): void {
    if (isset($_POST['save_link'])) {
        global $wpdb;

        $link_url = $_POST['link_url'];
        $link_alt = $_POST['link_alt'];
        $comprehensive = isset($_POST['comprehensive_link']);
        $selected_disability_categories = $_POST['selected_disability_categories'];
        $selected_product_categories = $_POST['selected_product_categories'];

        if (!empty($_POST['link_id'])) {
            $link_id = intval($_POST['link_id']);
            $wpdb->update(
                ADDITIONAL_LINK_TABLE,
                ['URL' => $link_url, 'altText' => $link_alt, 'comprehensive' => $comprehensive],
                ['id' => $link_id]
            );

            $wpdb->delete(LINK_FOR_DISABILITY_TABLE, ['linkId' => $link_id]);
            $wpdb->delete(LINK_FOR_AID_TABLE, ['linkId' => $link_id]);
        } else {
            $wpdb->insert(ADDITIONAL_LINK_TABLE, [
                'URL' => $link_url,
                'altText' => $link_alt,
                'comprehensive' => $comprehensive
            ]);
            $link_id = $wpdb->insert_id;
        }

        foreach ($selected_disability_categories as $disability_category_id) {
            $wpdb->insert(LINK_FOR_DISABILITY_TABLE, [
                'linkId' => $link_id,
                'disabilityId' => $disability_category_id
            ]);
        }

        foreach ($selected_product_categories as $product_category_id) {
            $wpdb->insert(LINK_FOR_AID_TABLE, [
                'linkId' => $link_id,
                'aidId' => $product_category_id
            ]);
        }

        wp_redirect(site_url('/weiterfuehrende-links-editieren'));
        exit;
    }
}

add_action('init', 'save_link');