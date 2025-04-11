<?php

require_once get_stylesheet_directory() . '/core/database.php';
require_once get_stylesheet_directory() . '/core/constants.php';

function display_by_name($item) {
    return $item->name;
}

function list_editable_items(
    $table_name,
    $order_by,
    $item_singular,
    $display_item_callback,
    $edit_page,
    $delete_button_class,
    $item_plural
): string {
    $items = get_all($table_name, $order_by);
    $output = '';

    if (!empty($items)) {
        $output .= '<table>';
        $output .= '<tr><th>' . $item_singular .'</th><th>Aktionen</th></tr>';

        foreach ($items as $item) {
            $output .= '<tr>';
            $output .= '<td>' . $display_item_callback($item) . '</td>';
            $output .= '<td>';
            $output .= '<a href="' . esc_url(site_url('/' . $edit_page . '?id=' . $item->id)) . '">';
            $output .= '<button>Bearbeiten</button>';
            $output .= '</a>';
            $output .= '<button class="' . $delete_button_class . '" data-id="' . esc_attr($item->id) . '">Löschen</button>';
            $output .= '</td>';
            $output .= '</tr>';
        }

        $output .= '</table>';

        $output .= '<div id="delete-dialogue" style="display: none">' .
            '<div class="modal-content" id="modal-content"></div>' .
            '</div>';
    } else {
        $output .= '<p>Keine ' . $item_plural . ' gefunden.</p>';
    }

    return $output;
}