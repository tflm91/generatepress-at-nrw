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
    <label>URL: <input type="url" name="<?php echo $url_field_name; ?>" value="<?php echo $old_url; ?>" <?php if ($required): ?> required <?php endif; ?></label><br><br>
    <label>Alternativtext (max. <?php echo $alt_maxlength; ?> Zeichen): <input type="text" name="<?php echo $alt_field_name; ?>" maxlength="<?php echo $alt_maxlength ?>" value="<?php echo $old_alt; ?>" <?php if ($required): ?> required <?php endif; ?>></label><br><br>
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

function checkbox_list($legend, $connection_items, $selected_items, $field_name, $display_attribute): void {
    ?>
    <fieldset>
        <legend><?php echo $legend; ?></legend>
        <?php foreach ($connection_items as $item): ?>
            <label>
                <input type="checkbox" name="<?php echo $field_name; ?>" value="<?php echo esc_attr($item->id); ?>"
                    <?php checked(in_array($item->id, $selected_items));  ?>>
                <?php echo esc_html($item->{$display_attribute}); ?>
            </label><br>
        <?php endforeach; ?>
    </fieldset><br>
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