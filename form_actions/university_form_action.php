<?php

require_once get_stylesheet_directory() . '/constants.php';

function sanitize_phone_number($phone): array|string|null {
    $phone = preg_replace('/[^0-9+]/', '', $phone);

    if (!filter_var($phone, FILTER_SANITIZE_NUMBER_INT)) {
        return'';
    }

    return $phone;
}
function save_university(): void {
    if (isset($_POST['save_university'])) {
        global $wpdb;
        $name = sanitize_text_field($_POST['university_name']);
        $division = sanitize_text_field($_POST['university_division']);
        $contact_name = sanitize_text_field($_POST['university_contact_name']);

        $phone_number = htmlspecialchars(
            sanitize_phone_number($_POST['university_phone_number']),
            ENT_QUOTES,
            'UTF-8'
        );

        $phone_alt = sanitize_text_field($_POST['university_phone_alt']);
        $email = sanitize_email($_POST['university_email']);
        $contact_url = esc_url_raw($_POST['university_contact_url']);
        $contact_alt = sanitize_text_field($_POST['university_contact_alt']);
        $workspaces = sanitize_textarea_field($_POST['university_workspaces']);
        $selected_products = $_POST['selected_products'] ?? [];

        if (!empty($_POST['university_id'])) {
            $university_id = intval($_POST['university_id']);
            $wpdb->update(
                UNIVERSITY_TABLE,
                [
                    'name' => $name,
                    'division' => $division,
                    'contactName' => $contact_name,
                    'phoneNumber' => $phone_number,
                    'phoneAlt' => $phone_alt,
                    'email' => $email,
                    'contactUrl' => $contact_url,
                    'contactAlt' => $contact_alt,
                    'workspaces' => $workspaces
                ],
                ['id' => $university_id]
            );

            $wpdb->delete(AVAILABILITY_TABLE, ['universityId' => $university_id]);
        } else {
            $wpdb->insert(UNIVERSITY_TABLE, [
                'name'=> $name,
                'division' => $division,
                'contactName' => $contact_name,
                'phoneNumber' => $phone_number,
                'phoneAlt' => $phone_alt,
                'email' => $email,
                'contactUrl' => $contact_url,
                'contactAlt' => $contact_alt,
                'workspaces' => $workspaces
            ]);

            $university_id = $wpdb->insert_id;
        }

        foreach ($selected_products as $product_id) {
            $wpdb->insert(AVAILABILITY_TABLE, [
                'universityId' => $university_id,
                'productId' => $product_id
            ]);
        }

        wp_redirect(site_url('/hochschulen-editieren'));
        exit;
    }
}

add_action('init', 'save_university');