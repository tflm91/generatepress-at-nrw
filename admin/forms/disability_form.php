<?php

require_once get_stylesheet_directory() . '/core/database.php';
require_once get_stylesheet_directory() . '/core/constants.php';
require_once get_stylesheet_directory() . '/admin/forms/helpers.php';

function disability_form(): bool|string {
    $disability_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $is_editing = ($disability_id > 0);

    if ($is_editing) {
        $current_disability = get_by_id(DISABILITY_TABLE, $disability_id);
        $selected_product_category_ids = get_connected_ids(
                AIDS_WITH_DISABILITY_TABLE,
            'impairmentId',
            'categoryId',
            $disability_id
        );
    }

    $disability_categories = get_all(DISABILITY_CATEGORY_TABLE, order_by: 'name');
    $product_categories = get_all(PRODUCT_CATEGORY_TABLE, order_by: 'name');

    ob_start();
    ?>
    <form method="post">
        <?php text_input(
                'disability_name',
            'Name der Beeinträchtigungsform',
            50,
            true,
            $is_editing ? esc_attr($current_disability->name) : ''
        ); ?>

        <?php select_input(
                'disability_category',
            'Behinderungskategorie',
            true,
            'name',
            $disability_categories,
            $is_editing ? $current_disability->categoryId : null
        );
        ?>

        <?php textarea_input(
                'disability_description',
            'Beschreibung',
            2500,
            TEXTAREA_ROW_COUNT,
                false,
            $is_editing ? $current_disability->description : ''
        ) ?>

        <fieldset>
            <legend>Passende assistive Technologien auswählen:</legend>
            <?php checkbox_list(
                    $product_categories,
                    $is_editing ? $selected_product_category_ids : [],
                    'selected_product_categories[]',
                    'name',
                    'Keine assistiven Technologien vorhanden. '
            );?>
        </fieldset><br>

        <?php if ($is_editing) id_field('disability_id', $disability_id); ?>
        <?php close_buttons(
                'save_disability',
                site_url('/beeintraechtigungsformen-editieren')
        ); ?>
    </form>
    <?php
    return ob_get_clean();
}

add_shortcode('disability_form', 'disability_form');