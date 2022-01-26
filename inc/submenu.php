<?php

add_filter( 'pre_wp_nav_menu', 'filter_function_name_8224', 9, 2 );
function filter_function_name_8224( $output, $args ){

//    $menu_items = wp_get_nav_menu_items($args->menu);
//    z_mon($output);
//    z_mon($menu_items);
//    z_mon(get_object_vars($args));
//    z_mon(wp_get_nav_menu_object($args->menu));
//    z_mon((array)$args);
//    $args->menu = "(.)(.)";
//    z_mon($args->menu);
    z_mon($args->theme_location);

    $location = $args->theme_location;
    $locations = get_nav_menu_locations();
    $menu = wp_get_nav_menu_object($locations[$location]);
    $menu_item = wp_get_nav_menu_items($menu->term_id);
    z_mon($menu_item);

    return $output;
}



add_filter( 'wp_nav_menu_args', 'filter_function_name_555', 1001, 2 );
function filter_function_name_555 ($args) {
//    z_mon((object)$args);

    return $args;
}