<?php

function custom_taxonomies() {

    /*
     * Create 'Seasons' taxonomy
     */
    register_taxonomy(
        'seasons',
        'fixture',
        array(
            'hierarchical' => false,
            'labels' => array(
            'name' => _x( 'Seasons', 'taxonomy general name' ),
            'singular_name' => _x( 'Season', 'taxonomy singular name' ),
            'search_items' =>  __( 'Search Seasons' ),
            'all_items' => __( 'All Seasons' ),
            'edit_item' => __( 'Edit Season' ),
            'update_item' => __( 'Update Season' ),
            'add_new_item' => __( 'Add New Season' ),
            'new_item_name' => __( 'New Season Name' ),
            'menu_name' => __( 'Seasons' ),
            )
        )
    );
    
    

    /*
     * Create separate categories for committee and player pages
     */
    register_taxonomy(
        'committee-page-groups',
        'committee-page',
        array(
            'hierarchical' => false,
            'labels' => array(
                'name' => _x( 'Page Groups', 'taxonomy general name' ),
                'singular_name' => _x( 'Page Group', 'taxonomy singular name' ),
                'search_items' =>  __( 'Search Page Groups' ),
                'all_items' => __( 'All Page Groups' ),
                'edit_item' => __( 'Edit Page Group' ),
                'update_item' => __( 'Update Page Group' ),
                'add_new_item' => __( 'Add New Page Group' ),
                'new_item_name' => __( 'New Page Group Name' ),
                'menu_name' => __( 'Page Groups' ),
            )
        )
    );
    register_taxonomy(
        'player-page-groups',
        'player-page',
        array(
            'hierarchical' => false,
            'labels' => array(
                'name' => _x( 'Page Groups', 'taxonomy general name' ),
                'singular_name' => _x( 'Page Group', 'taxonomy singular name' ),
                'search_items' =>  __( 'Search Page Groups' ),
                'all_items' => __( 'All Page Groups' ),
                'edit_item' => __( 'Edit Page Group' ),
                'update_item' => __( 'Update Page Group' ),
                'add_new_item' => __( 'Add New Page Group' ),
                'new_item_name' => __( 'New Page Group Name' ),
                'menu_name' => __( 'Page Groups' ),
            )
        )
    );


}
add_action('init', 'custom_taxonomies');