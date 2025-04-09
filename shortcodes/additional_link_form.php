<?php

require_once get_stylesheet_directory() . '/inc/display_helpers.php';
require_once get_stylesheet_directory() . '/inc/form_helpers.php';
require_once get_stylesheet_directory() . '/inc/database.php';
require_once get_stylesheet_directory() . '/constants.php';

function additional_link_form(): bool|string {
    $link_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $is_editing = ($link_id > 0);

    $current_link = null;
    $selected_product_category_ids = [];
    $selected_disability_category_ids = [];

    if ($is_editing) {
        $current_link = get_by_id(ADDITIONAL_LINK_TABLE, $link_id);
        $selected_product_category_ids = get_connected_ids(
            LINK_FOR_AID_TABLE,
            'linkId',
            'aidId',
            $link_id
        );

        $selected_disability_category_ids = get_connected_ids(
            LINK_FOR_DISABILITY_TABLE,
            'linkId',
            'disabilityId',
            $link_id
        );
    }

    $product_categories = get_all(PRODUCT_CATEGORY_TABLE, 'name');
    $disability_categories = get_all(DISABILITY_CATEGORY_TABLE, 'name');

    ob_start();
    ?>
    <form method="post">
        <?php link_input(
                'link_url',
                'link_alt',
                true,
                255,
            $is_editing ? esc_html($current_link->URL) : '',
            $is_editing ? esc_html($current_link->altText) : ''
        );?>

        <?php checkbox_input(
                'comprehensive_link',
            $is_editing ? $current_link->comprehensive : false,
            'Dies ist ein übergreifender Link. '
        );?>

        <fieldset>
            <legend>Passende Behinderungskategorien auswählen: </legend>
            <?php checkbox_list(
                    $disability_categories,
                    $selected_disability_category_ids,
                    'selected_disability_categories[]',
                    'name',
                'Keine Behinderungskategorie vorhanden. '
        ); ?>
        </fieldset>

        <fieldset>
            <legend>Passende assistive Technologien auswählen: </legend>
            <?php checkbox_list(
                    $product_categories,
                    $selected_product_category_ids,
                    'selected_product_categories[]',
                    'name',
                'Keine assistiven Technologien vorhanden. '
            ); ?>
        </fieldset>

        <?php if ($is_editing) id_field('link_id', $link_id); ?>
        <?php close_buttons('save_link', site_url('/weiterfuehrende-links-editieren')); ?>
    </form>
    <?php
    return ob_get_clean();
}

add_shortcode('additional_link_form', 'additional_link_form');