<?php

require_once get_stylesheet_directory() . '/inc/display_helpers.php';
require_once get_stylesheet_directory() . '/inc/database.php';
require_once get_stylesheet_directory() . '/constants.php';
require_once get_stylesheet_directory() . '/inc/form_helpers.php';

function university_form(): bool|string {
    $university_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $is_editing = ($university_id > 0);

    $current_university = null;
    $selected_product_ids = [];

    if ($is_editing) {
        $current_university = get_by_id(UNIVERSITY_TABLE, $university_id);
        $selected_product_ids = get_connected_ids(
            AVAILABILITY_TABLE,
            'universityId',
            'productId',
            $university_id
        );
    }

    $general_products = get_by_condition(PRODUCT_TABLE, 'availableGeneral', true, 'name');
    $non_general_products = get_by_condition(PRODUCT_TABLE, 'availableGeneral', false, 'name');
    ob_start();
    ?>
    <form method="post">
        <?php text_input(
                'university_name',
            'Name der Hochschule',
            100,
            true,
            $is_editing ? esc_attr($current_university->name) : ''
        ); ?>

        <?php text_input(
                'university_division',
            'Arbeitsbereich der Ansprechperson',
            500,
            true,
             $is_editing ? esc_attr($current_university->division) : ''
        ); ?>

        <?php text_input(
                'university_contact_name',
            'Name der Ansprechperson',
            100,
            true,
            $is_editing ? esc_attr($current_university->contactName) : ''
        ); ?>

        <?php phone_input(
                'university_phone_number',
             $is_editing ? esc_html($current_university->phoneNumber) : '',
            false,
            'university_phone_alt',
            $is_editing ? esc_html($current_university->phoneAlt) : ''
        ); ?>

        <?php mail_input(
                'university_email',
            true,
            $is_editing ? esc_attr($current_university->email) : ''
        ); ?>

        <b>Link zur Beratungsstelle: </b><br>
        <?php link_input(
                'university_contact_url',
            'university_contact_alt',
            false,
            200,
            $is_editing ? esc_url($current_university->contactURL) : '',
            $is_editing ? esc_html($current_university->contactAlt) : ''
        ); ?>

        <?php textarea_input(
                'university_workspaces',
            'Arbeitsplätze',
            500,
            TEXTAREA_ROW_COUNT,
            false,
            $is_editing ? esc_attr($current_university->workspaces) : ''
        ); ?>

        <fieldset>
            <legend>Angebotene Produkte auswählen:</legend>
            <div>
                <p>Folgende Produkte sind allgemein verfügbar und können daher nicht für diese Hochschule ausgewählt werden: </p>
                <ul>
                    <?php foreach ($general_products as $product): ?>
                        <li><?php echo esc_html($product->name)?></li>
                    <?php endforeach;?>
                </ul>
            </div>
            <p>Folgende Produkte können ausgewählt werden: </p>
            <?php checkbox_list(
                    $non_general_products,
                $selected_product_ids,
                'selected_products[]',
                'name',
                'Es sind keine Produkte vorhanden, die ausgewählt werden können. '
            ); ?>
        </fieldset><br>

        <?php if ($is_editing) id_field('university_id', $university_id); ?>

        <?php close_buttons(
                'save_university',
            site_url('/hochschulen-editieren')
        ); ?>
    </form>
    <?php
    return ob_get_clean();
}

add_shortcode('university_form', 'university_form');