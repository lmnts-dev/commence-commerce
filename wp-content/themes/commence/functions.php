<?php
//* Code goes here

add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles', 11 );

function my_theme_enqueue_styles() {
    wp_enqueue_style( 'child-style', get_stylesheet_uri() );
}

add_action('wp_enqueue_scripts', 'my_theme_enqueue_scripts');
function  my_theme_enqueue_scripts() {
    wp_enqueue_script('custom', get_stylesheet_directory_uri().'/js/theme.js', 
    array(), false, true);
}
