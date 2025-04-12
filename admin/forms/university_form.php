<?php

require_once get_stylesheet_directory() . '/core/database.php';
require_once get_stylesheet_directory() . '/core/constants.php';
require_once get_stylesheet_directory() . '/admin/forms/helpers.php';

function university_form(): bool|string {
    $university_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $is_editing = ($university_id > 0);

    if ($is_editing) {
        $current_university = get_by_id(UNIVERSITY_TABLE, $university_id);
        $selected_product_ids = get_connected_ids(
            AVAILABILITY_TABLE,
            'universityId',
            'productId',
            $university_id
        );
    }

    $general_products = get_all(PRODUCT_TABLE, ['availableGeneral' => true], order_by: 'name');
    $products = get_all(PRODUCT_TABLE,  order_by: 'name');
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
                <p>Die folgenden Produkte sind aktuell als <i>allgemein
                    verfügbar</i> markiert. Das bedeutet: Auch wenn du
                    sie für diese Hochschule auswählst, erscheinen sie
                    für Endnutzer nicht unter der Hochschule, sondern
                    werden als hochschulunabhängig angezeigt.

                    Wenn du möchtest, dass ein Produkt explizit dieser
                    Hochschule zugeordnet wird, entferne bitte die
                    Markierung <i>„allgemein verfügbar“</i> im Bereich <b>Produkte
                        editieren</b>.
                <ul>
                    <?php foreach ($general_products as $product): ?>
                        <li><?php echo esc_html($product->name)?></li>
                    <?php endforeach;?>
                </ul>
            </div>
            <p>Folgende Produkte können ausgewählt werden: </p>
            <?php checkbox_list(
                    $products,
                $is_editing ? $selected_product_ids : [],
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