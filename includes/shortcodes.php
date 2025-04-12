<?php

/**
 * List of all available shortcodes
 */

/* forms */
require_once get_stylesheet_directory() . '/admin/forms/disability_category_form.php';
require_once get_stylesheet_directory() . '/admin/forms/disability_form.php';
require_once get_stylesheet_directory() . '/admin/forms/limitation_form.php';
require_once get_stylesheet_directory() . '/admin/forms/product_category_form.php';
require_once get_stylesheet_directory() . '/admin/forms/product_form.php';
require_once get_stylesheet_directory() . '/admin/forms/university_form.php';
require_once get_stylesheet_directory() . '/admin/forms/additional_link_form.php';

/* listings */
require_once get_stylesheet_directory() . '/admin/listings/list_editable_disability_categories.php';
require_once get_stylesheet_directory() . '/admin/listings/list_editable_disabilities.php';
require_once get_stylesheet_directory() . '/admin/listings/list_editable_limitations.php';
require_once get_stylesheet_directory() . '/admin/listings/list_editable_product_categories.php';
require_once get_stylesheet_directory() . '/admin/listings/list_editable_products.php';
require_once get_stylesheet_directory() . '/admin/listings/list_editable_universities.php';
require_once get_stylesheet_directory() . '/admin/listings/list_editable_links.php';

/* components */
require_once get_stylesheet_directory() . '/public/components/list_comprehensive_links.php';

 /* views */
require_once get_stylesheet_directory() . '/public/views/show_disabilities.php';
require_once get_stylesheet_directory() . '/public/views/show_limitations.php';
require_once get_stylesheet_directory() . '/public/views/show_aids.php';
require_once get_stylesheet_directory() . '/public/views/show_universities.php';