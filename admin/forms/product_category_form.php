<?php

require_once get_stylesheet_directory() . '/core/database.php';
require_once get_stylesheet_directory() . '/core/constants.php';
require_once get_stylesheet_directory() . '/admin/forms/helpers.php';


function product_category_form(): bool|string {
    $category_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $is_editing = ($category_id > 0);

    $disabilities = get_all(DISABILITY_TABLE, order_by: 'name');
    $limitations = get_all(FUNCTIONAL_LIMITATION_TABLE, order_by: 'name');
    $products = get_all(PRODUCT_TABLE, order_by: 'name');

    if ($is_editing) {
        $current_category = get_by_id(PRODUCT_CATEGORY_TABLE, $category_id);
        $selected_disability_ids = get_connected_ids(
            AIDS_WITH_DISABILITY_TABLE,
            'categoryId',
            'impairmentId',
            $category_id
        );

        $selected_limitation_ids = get_connected_ids(
            AIDS_WITH_LIMITATION_TABLE,
            'categoryId',
            'impairmentId',
            $category_id
        );

        $selected_product_ids = get_connected_ids(
            CATEGORY_OF_PRODUCT_TABLE,
            'categoryId',
            'productId',
            $category_id
        );

        $selected_links = get_connected(
                LINK_FOR_AID_TABLE,
            'aidId',
            ADDITIONAL_LINK_TABLE,
            'linkId',
            $category_id,
            order_by: 'altText'
        );

        $unselected_links =  get_unconnected_to_object(
                LINK_FOR_AID_TABLE,
            'aidId',
            ADDITIONAL_LINK_TABLE,
            'linkId',
            $category_id,
            'altText'
        );
    } else {
        $unselected_links = get_all(ADDITIONAL_LINK_TABLE, order_by: 'altText');
    }

    ob_start();
    ?>
    <form method="post">
        <?php text_input(
                'category_name',
            'Name der assistiven Technologie',
            150,
            true,
            $is_editing ? esc_attr($current_category->name) : ''
        ); ?>

        <?php textarea_input(
                'category_description',
            'Beschreibung',
            2000,
                TEXTAREA_ROW_COUNT,
                false,
            $is_editing ? esc_attr($current_category->description) : ''
        ); ?>

        <fieldset>
            <legend>Unterstützte Beeinträchtigungsformen auswählen:</legend>
            <?php checkbox_list(
                    $disabilities,
                    $is_editing ? $selected_disability_ids : [],
                    'selected_disabilities[]',
                'name',
                'Keine Beeinträchtigungsformen vorhanden. '
            ); ?>
        </fieldset><br>


        <fieldset>
            <legend>Unterstützte Funktionseinschränkungen auswählen:</legend>
            <?php checkbox_list(
                    $limitations,
                $is_editing ? $selected_limitation_ids : [],
                'selected_limitations[]',
                'name',
                'Keine Funktionseinschränkungen vorhanden. '
            ); ?>
        </fieldset><br>


        <fieldset>
            <legend>Passende Produkte auswählen:</legend>
            <?php checkbox_list(
                    $products,
                    $is_editing ? $selected_product_ids : [],
                    'selected_products[]',
                    'name',
                    'Keine Produkte vorhanden. '
            ); ?>
        </fieldset><br>

        <fieldset>
            <legend>Weiterführende Links auswählen:</legend>
            <?php sorted_checkbox_list(
                'selected_links[]',
                'Bislang verknüpfte Links: ',
                $is_editing ? $selected_links : [],
                'Weitere Links: ',
                $unselected_links,
                'altText',
                'Alle verfügbaren Links waren bereits ausgewählt. ',
                'Keine weiterführenden Links vorhanden. '
            );?>
        </fieldset><br>

        <?php if($is_editing) id_field('category_id', $category_id);?>
       <?php close_buttons(
               'save_product_category',
               site_url('/assistive-technologien-editieren')
       );?>
    </form>
    <?php
    return ob_get_clean();
}

add_shortcode('product_category_form', 'product_category_form');