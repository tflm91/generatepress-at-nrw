<?php
require_once get_stylesheet_directory() . '/inc/database.php';
require_once get_stylesheet_directory() . '/constants.php';
require_once get_stylesheet_directory() . '/shortcodes/list_editable_items.php';

add_shortcode('list_editable_disability_categories', 'list_editable_disability_categories');

function list_editable_disability_categories(): string {
    return list_editable_items(
            DISABILITY_CATEGORY_TABLE,
        'name',
        'Behinderungskategorie',
        'display_by_name',
        'behinderungskategorie-bearbeiten',
        'delete-disability-category',
        'Behinderungskategorien'
    );
}

function disability_category_modal(): void {
    ?>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".delete-disability-category").forEach(button => {
                button.addEventListener("click", function (event) {
                    event.preventDefault();
                    let categoryId = this.getAttribute("data-id");

                    fetch('<?php echo admin_url('admin-ajax.php'); ?>?action=check_disability_category&category_id=' + categoryId)
                        .then(response => response.json())
                        .then(data => {
                            let dialogue = document.getElementById("delete-dialogue");
                            let modalContent = document.getElementById('modal-content');

                            if (data.hasEntries) {
                                modalContent.innerHTML = '<span class="close" onclick="closeDialogue()">&times;</span> ' +
                                    "<p>Diese Kategorie enthält noch folgende Beeinträchtigungsformen und kann daher nicht gelöscht werden. </p><ul>" +
                                    data.entries.map(entry => "<li>" + entry.name + "</li>").join("") +
                                    "</ul><p>Bitte lösche erst diese Beeinträchtigungsformen, bevor du die Kategorie löschst</p>" +
                                    "<button onClick='closeDialogue()'>Schließen</button>";
                            } else {
                                generateDisabilityCategoryModal(modalContent, categoryId);
                            }
                            dialogue.style.display = "block";
                        })
                });
            });
        });
    </script>
    <?php
}

function delete_disability_category_script(): void {
    generate_delete_function(
            'deleteDisabilityCategory',
            'delete_disability_category'
    );

    generate_modal_content_script(
            'generateDisabilityCategoryModal',
            'diese Behinderungskategorie',
            'deleteDisabilityCategory'
    );

    disability_category_modal();
}

add_action('wp_footer', 'delete_disability_category_script');

function check_disability_category(): void {
    $category_id = intval($_GET['category_id']);
    $disabilities = get_by_category(DISABILITY_TABLE, $category_id) ?? [];

    wp_send_json([
       'hasEntries' => count($disabilities) > 0,
        'entries' => $disabilities
    ]);
}

add_action('wp_ajax_check_disability_category', 'check_disability_category');
add_action('wp_ajax_nopriv_check_disability_category', 'check_disability_category');

function delete_disability_category(): void {
    global $wpdb;
    $category_id = intval($_POST['item_id']);

    $disabilities = get_by_category(DISABILITY_TABLE, $category_id, 'name') ?? [];

    if (empty($disabilities)) {
        $wpdb->delete(DISABILITY_CATEGORY_TABLE, ['id' => $category_id]);
        wp_send_json(['success' => true]);
    } else {
        wp_send_json(['success' => false, 'error' => 'Category still contains disabilities. ']);
    }
}
;
add_action('wp_ajax_delete_disability_category', 'delete_disability_category');