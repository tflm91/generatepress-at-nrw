<?php

require_once get_stylesheet_directory() . '/models/FunctionalLimitation.php';
require_once get_stylesheet_directory() . '/core/database.php';
require_once get_stylesheet_directory() . '/core/constants.php';

add_shortcode("limitations", "show_limitations");

function show_limitations(): string {
    $rows = get_all(FUNCTIONAL_LIMITATION_TABLE, order_by: 'name');
    $output = "<div>\n";
    if ($rows) {
        foreach ($rows as $row) {
            $limitation = new FunctionalLimitation(
                $row->id ?? 0,
                $row->name ?? 'Unbekannt'
            );
            $output .= $limitation->display();
        }
    } else {
        $output .= "<p>Keine Funktionseinschränkungen gefunden. </p>\n";
    }
    $output .= "</div>\n";
    return $output;
}
?>