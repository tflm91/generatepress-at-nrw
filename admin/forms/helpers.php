<?php

function link_input(
    $url_field_name,
    $alt_field_name,
    $required,
    $alt_maxlength,
    $old_url = "",
    $old_alt = ""
): void {
    ?>
    <label>URL: <input type="url" maxlength="2048" name="<?php echo $url_field_name; ?>" value="<?php echo $old_url; ?>" <?php if ($required): ?> required <?php endif; ?></label><br><br>
    <label>Alternativtext für den Link (max. <?php echo $alt_maxlength; ?> Zeichen): <input type="text" name="<?php echo $alt_field_name; ?>" maxlength="<?php echo $alt_maxlength ?>" value="<?php echo $old_alt; ?>" <?php if ($required): ?> required <?php endif; ?>></label><br><br>
    <?php
}

function checkbox_input($field_name_and_id, $checked, $text): void {
    ?>
    <label>
        <input type="checkbox" name="<?php echo $field_name_and_id; ?>" id="<?php echo $field_name_and_id; ?>"
            <?php checked($checked) ?>>
        <?php echo $text; ?>
    </label><br><br>
    <?php
}

function checkbox_list($connection_items, $selected_items, $field_name, $display_attribute, $empty_error = null): void {
    if (!empty($connection_items)) {
        foreach ($connection_items as $item) {
            $is_checked = false;

            if ($selected_items === 'all') {
                $is_checked = true;
            } elseif (is_array($selected_items)) {
                $is_checked = in_array($item->id, $selected_items);
            }

            ?>
            <label>
                <input type="checkbox" name="<?php echo $field_name; ?>" value="<?php echo esc_attr($item->id); ?>"
                    <?php checked($is_checked);  ?>>
                <?php echo esc_html($item->{$display_attribute}); ?>
            </label><br>
            <?php
        }
        ?><br><?php
    } elseif ($empty_error) {
        ?><p><?php echo $empty_error; ?></p><?php
    }
}

function sorted_checkbox_list($field_name, $selected_label, $selected_items, $unselected_label, $unselected_items, $display_attribute, $unselected_error, $empty_error = null): void {
    if (!empty($selected_items)) {
        ?>
        <p><?php echo $selected_label; ?></p>
        <?php checkbox_list($selected_items, 'all', $field_name, $display_attribute); ?>
        <br>
        <p><?php echo $unselected_label; ?></p>
        <?php checkbox_list($unselected_items, 'none', $field_name, $display_attribute, $unselected_error);?>
        <br>
        <?php
    } else {
        checkbox_list($unselected_items, 'none',  $field_name, $display_attribute, $empty_error);
    }
}

function text_input($field_name_and_id, $label, $maxlength, $required, $old_value): void {
    ?>
    <label for="<?php echo $field_name_and_id; ?>"><?php echo $label; ?> (max. <?php echo $maxlength; ?> Zeichen)</label>
    <input type="text" id="<?php echo $field_name_and_id; ?>" name="<?php echo $field_name_and_id; ?>" maxlength="<?php echo $maxlength; ?>" <?php if($required): ?> required <?php endif; ?>
           value="<?php echo $old_value; ?>"><br><br>

    <?php
}

function textarea_input($field_name_and_id, $label, $maxlength, $rows, $required, $old_value): void {
    ?>
    <label for="<?php echo $field_name_and_id; ?>"><?php echo $label; ?> (max. <?php echo $maxlength; ?> Zeichen): </label>
    <textarea id="<?php echo $field_name_and_id; ?>" name="<?php echo $field_name_and_id; ?>" maxlength="<?php echo $maxlength; ?>" rows="<?php echo esc_attr($rows); ?>" <?php if($required): ?> required <?php endif; ?>><?php echo $old_value; ?></textarea><br><br>
    <?php
}

function select_input($field_name_and_id, $label, $required, $display_attribute, $options, $selected_option = null): void {
    ?>
    <label for="<?php echo $field_name_and_id; ?>"><?php echo $label; ?></label>
    <select id="<?php echo $field_name_and_id; ?>" name="<?php echo $field_name_and_id; ?>" <?php if($required): ?> required <?php endif; ?>>
        <?php foreach ($options as $option): ?>
            <?php if ($selected_option && $option->id == $selected_option): ?>
                <option value="<?php echo esc_attr($option->id)?>" selected><?php echo esc_html($option->{$display_attribute})?></option>
            <?php else: ?>
                <option value="<?php echo esc_attr($option->id)?>"><?php echo esc_html($option->{$display_attribute})?></option>
            <?php endif; ?>
        <?php endforeach; ?>
    </select><br><br>
    <?php
}

function phone_input($number_field_name, $old_phone_number, $required, $alt_field_name, $old_alt): void {
    ?>
    <b>Telefonnummer: </b><br>
    <label>Telefonnummer im internationalen Format: <input type="tel" name="<?php echo $number_field_name; ?>"
                                                           pattern="\+{1-9}{0-9}+" maxlength="20"
                                                           value="<?php echo $old_phone_number; ?>"
                                                           <?php if($required): ?> required <?php endif; ?>"></label><br><br>
    <label>Angezeigter Text (max. 20 Zeichen): <input type="text" name="<?php echo $alt_field_name; ?>" maxlength="20" value="<?php echo $old_alt; ?>"></label><br><br>
    <?php
}

function mail_input($field_name_and_id, $required, $old_email): void {
    ?>
    <label for="<?php echo $field_name_and_id; ?>">E-Mail-Adresse</label>
    <input type="email" id="<?php echo $field_name_and_id; ?>" name="<?php echo $field_name_and_id; ?>" <?php if($required): ?> required <?php endif; ?> maxlength="255"
           value="<?php echo $old_email;?>"><br><br>
    <?php
}

function id_field($name, $id): void {
     ?> <input type="hidden" name="<?php echo $name?>" value="<?php echo esc_attr($id) ?>"><?php
}

function close_buttons($action_name, $back_url): void {
    ?>
    <button type="submit" name="<?php echo $action_name; ?>">Speichern</button>
        <a href="<?php echo $back_url; ?>">
            <button type="button">Abbrechen</button>
        </a>
    <?php
}