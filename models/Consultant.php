<?php

class Consultant {
    public int $id;
    public string $name;
    public string $phone_number;
    public string $phone_alt;
    public string $email;

    public function __construct(
        int $id,
        string $name,
        string $phone_number,
        string $phone_alt,
        string $email
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->phone_number = $phone_number;
        $this->phone_alt = $phone_alt;
        $this->email = $email;
    }

    public function display(): void {
        $output = '<h4>' . esc_html($this->name) . '</h4>';

        if ($this->phone_number != '') {
            $output .= '<p><b>Telefonnummer: </b><a href="' . esc_url('tel:' . $this->phone_number) . '">' . esc_html($this->phone_alt) . '</a></p>';
        } else {
            $output .= '<p><b>Telefonnummer: </b>nicht vorhanden</p>';
        }

        if ($this->email != '') {
            $output .= '<p><b>E-Mail: </b><a href="' . esc_url('mailto:' . $this->email) . '">' . $this->email . '</a></p>';
        } else {
            $output .= '<p><b>E-Mail: </b>nicht vorhanden</p>';
        }
    }
}