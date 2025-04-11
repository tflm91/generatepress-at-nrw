<?php

require_once get_stylesheet_directory() . '/core/database.php';
require_once get_stylesheet_directory() . '/core/display_helpers.php';
require_once get_stylesheet_directory() . '/core/constants.php';

class ProductCategory {
    public int $id;
    public string $name;
    public string $description;

    public function __construct($id, $name, $description) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
    }

    public function display(): string {
        $output = "<h2 id='category-" . $this->id . "'>" . esc_html($this->name) . "</h2>\n";
        if ($this->description && $this->description != "") {
            $output .= "<p>" . esc_html($this->description) . "</p>\n";
        }
        $output .= $this->list_products();

        $additional_links = get_connected(
            LINK_FOR_AID_TABLE,
            "aidId",
            ADDITIONAL_LINK_TABLE,
            "linkId",
            $this->id,
            'altText'
        );

        if (!empty($additional_links)) {
            $output .= generate_link_list($additional_links);
        }

        return $output;
    }

    function get_products() {
        return get_connected(
            CATEGORY_OF_PRODUCT_TABLE,
            'categoryId',
            PRODUCT_TABLE,
            'productId',
            $this->id,
            'name'
        );
    }

    function list_products(): string {
        return generate_item_list(
            $this->get_products(),
            "assistive-technologien",
            error: "Keine Produkte zu dieser assistiven Technologie gefunden. "
        );
    }
}