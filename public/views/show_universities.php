<?php
require_once get_stylesheet_directory() . "/core/constants.php";
require_once get_stylesheet_directory() . "/core/database.php";
require_once get_stylesheet_directory() . "/models/University.php";

add_shortcode("universities", "show_universities");

/* the shortcodes for displaying the universities */
function show_universities(): string {
    $university_id = get_query_var('university_id');
    if ($university_id) {
        return show_university_details_page($university_id);
    }
    return list_universities();
}

/* show details page for a specific university */
function show_university_details_page($university_id): string {
    $row = get_by_id(UNIVERSITY_TABLE, $university_id);
    $output = "<div>\n";
    if ($row) {
        $university = construct_university_from_row($row);
        $output .= $university->display_information();
        $output .= "<h3>Verfügbare Hifsmittel</h3>\n";
        $output .= $university->list_special_aids();
        $output .= list_general_aids();
    } else {
        $output .= "<p>Die Hochschule konnte nicht gefunden werden. </p>";
    }
    $output .= "<a href='" . site_url("/hochschulen") . "'>Zur Übersicht aller Hochschulen</a>\n";
    $output .= "</div>\n";
    return $output;
}

/* construct university object from database entry */
function construct_university_from_row($row): University {
    return new University(
        $row->id ?? 0,
        $row->name ?? 'Unbekannt',
        $row->division ?? 'Unbekannt',
        $row->contactName ?? 'Unbekannt',
        $row->phoneNumber ?? '',
        $row->phoneAlt ?? '',
        $row->email ?? '',
        $row->contactURL ?? '',
        $row->contactAlt ?? '',
        $row->workspaces ?? 'Unbekannt'
    );
}

/* lists all generally available aids*/
function list_general_aids(): string {
    $before_html =  '<h4>Allgemein verfügbare Produkte</h4>';
    return generate_item_list(
        get_all(PRODUCT_TABLE, ['availableGeneral' => true], order_by: 'name'),
        "assistive-technologien",
        $before_html
    );
}

/* list all universities in NRW */
function list_universities(): string {
    $rows = get_all(UNIVERSITY_TABLE, order_by: 'name');

    $output = "<div>\n";
    if ($rows) {
        foreach ($rows as $row) {
            $university = construct_university_from_row($row);
            $output .= $university->display_information();
            $output .= "<p><a href='" . site_url("/hochschulen/" . esc_attr($row->id))
                . "'>Verfügbare assistive Produkte anzeigen</a></p>\n";
        }
    } else {
        $output .= "<h2>Keine Universitäten gefunden</h2>\n";
    }
    $output .= "</div>\n";
    return $output;
}
?>
