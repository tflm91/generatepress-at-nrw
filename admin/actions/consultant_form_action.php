<?php

require_once get_stylesheet_directory() . '/core/constants.php';

function sanitize_consultant_phone_number($phone): array|string|null {
    $phone = preg_replace('/[^0-9+]/', '', $phone);

    if (!filter_var($phone, FILTER_SANITIZE_NUMBER_INT)) {
        return'';
    }

    return $phone;
}

function save_consultant(): void {
    if (isset($_POST['save_consultant'])) {
        global $wpdb;

        $university_id = $_POST['consultant_university'];
        $name = sanitize_text_field($_POST['consultant_name']);
        $phone_number = htmlspecialchars(
            sanitize_consultant_phone_number($_POST['consultant_phone_number']),
            ENT_QUOTES,
            'UTF-8'
        );

        $phone_alt = sanitize_text_field($_POST['consultant_phone_alt']);
        $email = sanitize_email($_POST['consultant_email']);

        if (!empty($_POST['consultant_id'])) {
            $consultant_id = $_POST['consultant_id'];
            $wpdb->update(CONSULTANT_TABLE,
                ['name' => $name, 'universityId' => $university_id, 'phoneNumber' => $phone_number, 'phoneAlt' => $phone_alt, 'email' => $email],
                ['id' => $consultant_id]
            );
        } else {
            $wpdb->insert(CONSULTANT_TABLE, [
                'name' => $name,
                'universityId' => $university_id,
                'phoneNumber' => $phone_number,
                'phoneAlt' => $phone_alt,
                'email' => $email
            ]);
        }

        wp_redirect(site_url('/beratungspersonen-editieren'));
        exit;
    }
}

add_action('init', 'save_consultant');