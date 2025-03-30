<?php

require_once get_stylesheet_directory() . '/inc/database.php';
require_once get_stylesheet_directory() . '/constants.php';

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
) {
    $items = get_all($table_name, $order_by);
    $output = '';

    if (!empty($items)) {
        $output .= '<table>';
        $output .= '<tr><th>' . $item_singular .'</th><td>Aktionen</td></tr>';

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

function generate_delete_function($delete_function_name, $delete_action_name): void {
    ?>
    <script>
        function <?php echo $delete_function_name ?>(itemId) {
            fetch('<?php echo admin_url("admin-ajax.php")?>', {
                method: "POST",
                headers: {"Content-Type": "application/x-www-form-urlencoded"},
                body: "action=<?php echo $delete_action_name?>&item_id=" + itemId
            })
                .then(() => {
                    this.location.reload();
                })
        }
    </script>
    <?php
}

function generate_modal_content_script($modal_content_function_name, $item_singular, $delete_function_name): void {
    ?>
    <script>
        function <?php echo $modal_content_function_name ?>(modalContent, itemId) {
            modalContent.innerHTML = "<span class='close' onclick='closeDialogue()'>&times;</span>" +
                "<p>Bist du sicher, dass du diese <?php echo $item_singular ?> löschen möchtest?</p>" +
                "<button onclick='<?php echo $delete_function_name ?>(" + itemId + ")'>Ja</button> " +
                "<button onclick='closeDialogue()'>Abbrechen</button>";
        }
    </script>
    <?php
}

function delete_empty_item_script($delete_button_class, $modal_content_function_name): void {
    ?>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".<?php echo $delete_button_class ?>").forEach(button => {
                button.addEventListener("click" , function (event) {
                    event.preventDefault();
                    let itemId = this.getAttribute("data-id");
                    let dialogue = document.getElementById('delete-dialogue');
                    let modalContent = document.getElementById('modal-content');
                    <?php echo $modal_content_function_name ?>(modalContent, itemId);
                    dialogue.style.display = "block";
                });
            });
        });
    </script>
    <?php
}