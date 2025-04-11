<?php

require_once get_stylesheet_directory() . '/core/database.php';
require_once get_stylesheet_directory() . '/core/constants.php';
require_once get_stylesheet_directory() . '/admin/forms/helpers.php';


function product_form(): bool|string {
    $product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $is_editing = ($product_id > 0);

    if ($is_editing) {
        $current_product = get_by_id(PRODUCT_TABLE, $product_id);
        $selected_product_category_ids = get_connected_ids(
            CATEGORY_OF_PRODUCT_TABLE,
            'productId',
            'categoryId',
            $product_id
        );

        $selected_university_ids = get_connected_ids(
                AVAILABILITY_TABLE,
            'productId',
            'universityId',
            $product_id
        );
    }

    $product_categories = get_all(PRODUCT_CATEGORY_TABLE, 'name');
    $universities = get_all(UNIVERSITY_TABLE, 'name');

    ob_start();
    ?>
    <form method="post">
        <?php text_input(
                'product_name',
            'Name des Produkts',
            100,
            true,
            $is_editing ? esc_attr($current_product->name) : ''
        ); ?>

        <?php textarea_input(
                'product_description',
            'Beschreibung',
            3000,
            TEXTAREA_ROW_COUNT,
            false,
            $is_editing ? esc_attr($current_product->description) : ''
        ); ?>


        <b>Link zu weiterführenden Informationen (leer lassen, wenn kein Link vorhanden): </b><br>
        <?php link_input(
           'product_info_url',
           'product_info_alt',
           false,
            200,
            $is_editing ? esc_url($current_product->infoURL) : '',
            $is_editing ? esc_html($current_product->infoAlt) : ''
        ); ?>

        <fieldset>
            <legend>Passende assistiven Technologien auswählen:</legend>
            <?php checkbox_list(
                    $product_categories,
                $is_editing ? $selected_product_category_ids : [],
                'selected_product_categories[]',
                'name',
                'Keine assistiven Technologien vorhanden'
            ); ?>
        </fieldset><br>

        <?php checkbox_input(
                'available_general',
            $is_editing ? $current_product->availableGeneral : false,
            'Dieses Produkt ist allgemein verfügbar. '
        ); ?>

        <fieldset id="university_list">
            <legend>Hochschulen auswählen, die dieses Produkt anbieten:</legend>
            <?php checkbox_list(
                    $universities,
                    $is_editing ? $selected_university_ids : [],
                'selected_universities[]',
                'name',
                'Keine Hochschulen vorhanden'
            ); ?>
        </fieldset><br>

        <?php checkbox_input(
                'hidden',
            $is_editing ? $current_product->hidden : false,
            'Dieses Produkt ausblenden. '
        );?>

        <?php if($is_editing) id_field('product_id', $product_id); ?>
        <?php close_buttons(
                'save_product',
            site_url('/produkte-editieren')
        ); ?>

    </form>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('university_list').style.display = document.getElementById('available_general').checked ? 'none' : 'block';
        });
        document.getElementById('available_general').addEventListener('change', function () {
            document.getElementById('university_list').style.display = this.checked ? 'none' : 'block';
        });
    </script>
    <?php
    return ob_get_clean();
}

add_shortcode('product_form', 'product_form');