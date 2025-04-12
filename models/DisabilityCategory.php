<?php

require_once get_stylesheet_directory() . '/core/database.php';
require_once get_stylesheet_directory() . '/core/display_helpers.php';
require_once get_stylesheet_directory() . '/core/constants.php';

class DisabilityCategory {
    public int $id;
    public string $name;
    public string $description;

    public function __construct($id, $name, $description) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
    }

    public function display(): string {
        $output = "<h2>" . esc_html($this->name) . "</h2>\n";
        if ($this->description && $this->description != "") {
            $output .= "<p>" . esc_html($this->description) . "</p>\n";
        }
        $output .= $this->list_disabilities();
        $additional_links = get_connected(
            LINK_FOR_DISABILITY_TABLE,
            'disabilityId',
            ADDITIONAL_LINK_TABLE,
            'linkId',
            $this->id,
            order_by: 'altText'
        );

        if (!empty($additional_links)) {
            $output .= generate_link_list($additional_links);
        }

        return $output;
    }

    function list_disabilities(): string {
        $disabilities = get_by_category(DISABILITY_TABLE, $this->id, 'name');
        return generate_item_list(
            $disabilities,
            "beeintraechtigungsformen",
            error: "Keine spezifische Beeinträchtigungsformen gefunden. "
        );
    }
}