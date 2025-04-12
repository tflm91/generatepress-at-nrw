<?php

require_once get_stylesheet_directory() . '/models/DisabilityCategory.php';
require_once get_stylesheet_directory() . '/models/Disability.php';

/**
 * Shortcode to display disabilities
 */

add_shortcode("disabilities", "show_disabilities");

/* the shortcode for the disability page */
function show_disabilities(): string {
    $disability_id = get_query_var('disability_id');
    if ($disability_id) {
        return show_detailed_disability_information($disability_id);
    }
    return list_disability_categories();
}

/* show detailed information about a specific disability */
function show_detailed_disability_information ($disability_id): string {
    $row = get_by_id(DISABILITY_TABLE, $disability_id);
    if ($row) {
        $disability = new Disability(
            $row->id ?? 0,
                $row->categoryId ?? 0,
            $row->name ?? 'Unbekannt',
            $row->description ?? 'Unbekannt'
        );

        $output = "<div>\n";
        $output .= $disability->display();
        $back_url = site_url('/beeintraechtigungsformen');
        $output .= "<a href='". $back_url ."'>Zurück zur Übersicht</a>\n";
        $output .= "</div>\n";
        return $output;
    } else {
        return "<p>Keine Beeinträchtigungsform mit dieser ID gefunden.</p>";
    }
}

/* list all disability categories */
function list_disability_categories(): string {
    $results = get_all(DISABILITY_CATEGORY_TABLE, order_by: 'name');
    $output = "<div>\n";
    if ($results) {
        foreach ($results as $row) {
            $output .= display_disability_category_information($row);
        }
    } else {
        $output .= "<p>Keine Behinderungskategorien vorhanden.</p>\n";
    }
    $output .= "</div>\n";
    return $output;
}

/* display information about the specified disability_category */
function display_disability_category_information($row): string {
    $output = "";
    if (category_has_objects(DISABILITY_TABLE, $row->id)) {
        $disability_category = new DisabilityCategory(
            $row->id ?? 0,
                $row->name ?? 'Unbekannt',
            $row->description ?? 'Unbekannt'
        );
        $output .= $disability_category->display();
    }
    return $output;
}