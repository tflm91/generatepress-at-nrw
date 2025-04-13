<?php

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

function generate_modal_content_script($modal_content_function_name, $item_with_article, $delete_function_name): void {
    $words =  explode(' ', $item_with_article);
    $subWords = array_slice($words, 1);

    $subWords[0] = ucfirst(strtolower($subWords[0]));

    $item_without_article = implode(' ', $subWords);
    ?>
    <script>
        function <?php echo $modal_content_function_name ?>(modalContent, itemId) {
            modalContent.innerHTML = "<span class='close' onclick='closeDialogue()' aria-label='Modal schließen'>&times;</span>" +
                "<h2 id='modal-heading'><?php echo $item_without_article; ?> löschen?</h2>" +
                "<p>Bist du sicher, dass du <?php echo $item_with_article ?> löschen möchtest?</p>" +
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
            let dialogue = document.getElementById('delete-dialogue');
            let modalContent = document.getElementById('modal-content');

            document.querySelectorAll(".<?php echo $delete_button_class ?>").forEach(button => {
                button.addEventListener("click" , function (event) {
                    event.preventDefault();
                    let itemId = this.getAttribute("data-id");
                    lastFocusedElement = this;

                    <?php echo $modal_content_function_name ?>(modalContent, itemId);
                    dialogue.style.display = "block";
                    trapFocus(dialogue);
                });
            });
        });
    </script>
    <?php
}