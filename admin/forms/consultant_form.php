<?php

require_once get_stylesheet_directory() . '/core/database.php';
require_once get_stylesheet_directory() . '/core/constants.php';
require_once get_stylesheet_directory() . '/admin/forms/helpers.php';

function consultant_form(): bool|string {
    $consultant_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $is_editing = $consultant_id > 0;

    if ($is_editing) {
        $current_consultant = get_by_id(CONSULTANT_TABLE, $consultant_id);
    }

    $universities =  get_all(UNIVERSITY_TABLE, order_by: 'name');

    ob_start();
    ?>
    <form method="post">
    <?php text_input(
        'consultant_name',
        'Name des Beratungskontakts',
        100,
        true,
        $is_editing ?  esc_attr($current_consultant->name) : ''
    ); ?>

    <?php select_input(
        'consultant_university',
        'Hochschule: ',
        true,
        'name',
        $universities,
        $is_editing ? $current_consultant->universityId : null
    );?>

    <?php phone_input(
        'consultant_phone_number',
        $is_editing ? esc_html($current_consultant->phoneNumber) : '',
        false,
        'consultant_phone_alt',
        $is_editing ? esc_html($current_consultant->phoneAlt) : ''
    ); ?>

    <?php mail_input(
        'consultant_email',
        false,
        $is_editing ? esc_attr($current_consultant->email) : ''
    ); ?>

    <?php checkbox_input(
            'consultant_spam_protection',
        $is_editing ? $current_consultant->spamProtection : 1,
        '<b>Spamschutz aktivieren:</b> Wenn du diese Option aktivierst, wird die E-Mail-Adresse nicht verlinkt. '
    ); ?>

    <?php if ($is_editing) id_field('consultant_id', $consultant_id); ?>

    <?php close_buttons(
        'save_consultant',
        site_url('/beratungskontakte-editieren')
    );?>
    </form>
    <?php
    return ob_get_clean();
}

add_shortcode('consultant_form', 'consultant_form');