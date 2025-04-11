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
    ?>
    <script>
        function <?php echo $modal_content_function_name ?>(modalContent, itemId) {
            modalContent.innerHTML = "<span class='close' onclick='closeDialogue()'>&times;</span>" +
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