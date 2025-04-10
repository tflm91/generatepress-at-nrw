<?php

require_once get_stylesheet_directory() . '/inc/display_helpers.php';
require_once get_stylesheet_directory() . '/inc/database.php';
require_once get_stylesheet_directory() . '/constants.php';

function disability_category_form(): bool|string {
    $category_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $is_editing = ($category_id > 0);
    $selected_links = [];

    if ($is_editing) {
        $current_category = get_by_id(DISABILITY_CATEGORY_TABLE, $category_id);

        $selected_links = get_connected(
                LINK_FOR_DISABILITY_TABLE,
            'disabilityId',
            ADDITIONAL_LINK_TABLE,
            'linkId',
            $category_id,
            'altText'
        );

        $unselected_links = get_unconnected_to_object(
            LINK_FOR_DISABILITY_TABLE,
            'disabilityId',
            ADDITIONAL_LINK_TABLE,
            'linkId',
            $category_id
        );
    } else {
        $unselected_links = get_all(ADDITIONAL_LINK_TABLE, 'altText');
    }

ob_start();
?>
<form method="post">
    <?php text_input(
            'category_name',
            'Name der Behinderungskategorie',
        50,
        true,
        $is_editing ? esc_attr($current_category->name) : ''
    ); ?>

    <?php textarea_input(
            'category_description',
        'Beschreibung',
        1000,
        TEXTAREA_ROW_COUNT,
        true,
        $is_editing ? esc_attr($current_category->description) : ''
    ); ?>

    <fieldset>
        <legend>Weiterführende Links auswählen:</legend>
        <?php sorted_checkbox_list(
                'selected_links[]',
            'Bislang verknüpfte Links: ',
                $selected_links,
            'Weitere Links: ',
            $unselected_links,
            'altText',
            'Alle verfügbaren Links waren bereits ausgewählt. '
        );?>
    </fieldset><br>

    <?php if ($is_editing) id_field('category_id', $category_id); ?>
    <?php close_buttons(
            'save_disability_category',
            site_url('/behinderungskategorien-editieren')
    );?>
</form>
<?php
    return ob_get_clean();
}

add_shortcode('disability_category_form', 'disability_category_form');