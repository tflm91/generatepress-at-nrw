<?php

require_once get_stylesheet_directory() . '/core/database.php';
require_once get_stylesheet_directory() . '/core/constants.php';
require_once get_stylesheet_directory() . '/admin/forms/helpers.php';


function limitation_form(): bool|string {
    $limitation_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $is_editing = ($limitation_id > 0);

    if ($is_editing) {
        $current_limitation = get_by_id(FUNCTIONAL_LIMITATION_TABLE, $limitation_id, 'name');
        $selected_product_category_ids = get_connected_ids(
            AIDS_WITH_LIMITATION_TABLE,
            'impairmentId',
            'categoryId',
            $limitation_id
        );
    }

    $product_categories = get_all(PRODUCT_CATEGORY_TABLE, 'name');

    ob_start();
    ?>
    <form method="post">
        <?php text_input(
                'limitation_name',
            'Name der Funktionseinschränkung',
            100,
            true,
            $is_editing ? esc_attr($current_limitation->name) : ''
        ); ?>

        <fieldset>
            <legend>Passende assistive Technologien auswählen:</legend>
            <?php checkbox_list(
                    $product_categories,
                    $is_editing ? $selected_product_category_ids : [],
                    'selected_product_categories[]',
                    'name',
                    'Keine assistiven Technologien vorhanden'
            ); ?>
        </fieldset><br>

        <?php if ($is_editing) id_field('limitation_id', $limitation_id); ?>
        <?php close_buttons(
                'save_limitation',
                site_url('/funktionseinschraenkungen-editieren')
        ); ?>
    </form>
    <?php
    return ob_get_clean();
}

add_shortcode('limitation_form', 'limitation_form');