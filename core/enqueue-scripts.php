<?php
/**
 * Enqueue parent and child theme assets
 */
function gp_child_enqueue_assets(): void {
    // parent theme CSS
    wp_enqueue_style(
        'parent-style',
        get_template_directory_uri() . '/style.css'
    );

    // own CSS-file from assets/css
    wp_enqueue_style(
        'child-style',
        get_stylesheet_directory_uri() . '/assets/css/style.css',
        array('parent-style'),
        filemtime(get_stylesheet_directory() . '/assets/css/style.css')
    );

    // own javascript file from assets/js
    wp_enqueue_script(
        'child-scripts',
        get_stylesheet_directory_uri() . '/assets/js/scripts.js',
        filemtime(get_stylesheet_directory() . '/assets/js/scripts.js'),
        true // load in footer
    );
}
add_action('wp_enqueue_scripts', 'gp_child_enqueue_assets');
