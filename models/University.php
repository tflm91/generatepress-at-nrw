<?php

require_once get_stylesheet_directory() . '/core/display_helpers.php';
require_once get_stylesheet_directory() . '/core/database.php';
require_once get_stylesheet_directory() . '/core/constants.php';
require_once get_stylesheet_directory() . '/models/Consultant.php';

class University {
    public int $id;
    public string $name;
    public string $division;
    public string $contact_name;
    public string $phone_number;
    public string $phone_alt;
    public string $email;
    public string $contact_url;
    public string $contact_alt;
    public string $workspaces;

    public function __construct(
        $id,
        $name,
        $division,
        $contact_name,
        $phone_number,
        $phone_alt,
        $email,
        $contact_url,
        $contact_alt,
        $workspaces
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->division = $division;
        $this->contact_name = $contact_name;
        $this->phone_number = $phone_number;
        $this->phone_alt = $phone_alt;
        $this->email = $email;
        $this->contact_url = $contact_url;
        $this->contact_alt = $contact_alt;
        $this->workspaces = $workspaces;
    }

    function get_aids() {
        return get_connected(
            AVAILABILITY_TABLE,
            'universityId',
            PRODUCT_TABLE,
            'productId',
            $this->id,
            ['hidden' => false, 'availableGeneral' => false],
            order_by: 'name'
        );
    }

    public function list_special_aids(): string {
        $before_html = "<h4>Spezielle assistive Technologien der Hochschule</h4>\n";
        $error = "Diese Hochschule bietet keine Informationen über eigene assistive Technologien an. Es können dort nur allgemein verfügbare assistive Technologien genutzt werden. ";
        return generate_item_list(
            $this->get_aids(),
            "assistive-technologien",
            $before_html,
            $error
        );
    }

    function get_contact_information(): array {
        return array_map(
            fn($row) => Consultant::create_from_row($row),
            get_by_category(CONSULTANT_TABLE, 'universityId' , $this->id)
        );
    }

    public function list_contact_information() :string {
        $contact_information = $this->get_contact_information();
        $contact_information_output = array_map(fn($contact_information) => $contact_information->display(), $contact_information);
        $output = '<h3>Beratungskontakte</h3>';

        if (!empty($contact_information)) {
            $output .= implode('', $contact_information_output);
        } else {
            $output .= 'Keine Beratungskontakte vorhanden';
        }

        return $output;
    }

    public function display_information(): string {
        $output = "<h2>" . esc_html($this->name) . "</h2>\n";
        $output .= "<h3>Kontaktinformationen zur Beratungsstelle für behinderte Studierende </h3>\n";
        $output .= '<p><b>Arbeitsbereich: </b>' . esc_html($this->division) . '</p>';

        if ($this->contact_url != '') {
            $output .= '<p><b>Link zur Beratungsstelle: </b><a href="' . esc_url($this->contact_url) . '">' . esc_html($this->contact_alt) . '</a></p>';
        } else {
            $output .= "<p>Kein Link zur Beratungsstelle vorhanden. </p>";
        }

        $output .= $this->list_contact_information();

        $output .= '<h3>Arbeitsräume</h3>';
        $output .= '<p>' . esc_html($this->workspaces) . '</p>';
        return $output;
    }
}