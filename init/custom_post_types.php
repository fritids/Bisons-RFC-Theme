<?php



// Register post types with Wordpress
function create_post_types() {
    register_post_type( 'fixture', array(
        'labels' => array (
            'name' => __( 'Fixtures', 'bisonsrfc'  ),
            'singular_name' => __( 'Fixture', 'bisonsrfc'  ),
            'add_new_item' => __( 'Add new fixture', 'bisonsrfc' ),
            'edit_item' => __( 'Edit fixture', 'bisonsrfc' ),
            'view_item' => __( 'View fixture', 'bisonsrfc' ),
            'search_item' => __( 'Search fixtures', 'bisonsrfc' ),
            ),
        'public' => true,
        'has_archive' => true,
        'menu_position' => 4,
        'menu_icon' => 'dashicons-flag',
        'taxonomies' => array('seasons'),
        'supports' => array('comments', 'revisions')
        )
    );
    
    
    register_post_type( 'playerprofile', array(
        'labels' => array (
            'name' => __( 'Player Profiles', 'bisonsrfc' ),
            'singular_name' => __( 'Player Profile', 'bisonsrfc' ),
            'add_new_item' => __( 'Add new player profile', 'bisonsrfc' ),
            'edit_item' => __( 'Edit player profile', 'bisonsrfc' ),
            'view_item' => __( 'View player profile', 'bisonsrfc' ),
            'search_item' => __( 'Search player profiles', 'bisonsrfc' ),
            ),
        'public' => true,
        'show_in_menu' => false,
        'has_archive' => true,
        'menu_position' => 3,
        'supports' => array('comments', 'title')
        )
    );
    /**
      * Create 'Events' post type
      */
    register_post_type( 'event', array(
        'labels' => array (
            'name' => __( 'Events', 'bisonsrfc' ),
            'singular_name' => __( 'Event', 'bisonsrfc' ),
            'add_new_item' => __( 'Add new event', 'bisonsrfc' ),
            'edit_item' => __( 'Edit event', 'bisonsrfc' ),
            'view_item' => __( 'View event', 'bisonsrfc' ),
            'search_item' => __( 'Search events', 'bisonsrfc' ),
            ),
        'public' => true,
        'has_archive' => true,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-calendar',
        'supports' => array('comments', 'revisions', 'title', 'editor')
        )
    );
    
    /**
     * Create 'results' post type
     */
    register_post_type( 'result', array(
        
        'labels' => array (
            'name' => __( 'Results', 'bisonsrfc' ),
            'singular_name' => __( 'Result', 'bisonsrfc' ),
            'add_new_item' => __( 'Add new result', 'bisonsrfc' ),
            'edit_item' => __( 'Edit result', 'bisonsrfc' ),
            'view_item' => __( 'View result', 'bisonsrfc' ),
            'search_item' => __( 'Search results', 'bisonsrfc' ),
            ),
        'public' => true,
        'show_in_menu' => false,
        'has_archive' => false,
        'hierarchical' => true,
        'menu_position' => 6,
        'supports' => array(
            'page-attributes'
        )
        )
    );



    /*
     *  Create player pages post type
     */
    register_post_type( 'player-page', array(

        'labels' => array (
            'name' => __( 'Player Pages', 'bisonsrfc' ),
            'singular_name' => __( 'Player Page', 'bisonsrfc' ),
            'add_new_item' => __( 'Add new player page', 'bisonsrfc' ),
            'edit_item' => __( 'Edit player page', 'bisonsrfc' ),
            'view_item' => __( 'View player page', 'bisonsrfc' ),
            'search_item' => __( 'Search player page', 'bisonsrfc' ),
            ),
        'public' => true,
        'rewrite'=> array('slug' => 'players-area'),
        'show_in_menu' => false,
        'has_archive' => true,
        'hierarchical' => true,
        'menu_position' => 8,

        )

    );
    
    
    /*
     *  Create committee member pages post type
     */
    register_post_type( 'committee-page', array(
        
        'labels' => array (
            'name' => __( 'Committee Pages', 'bisonsrfc' ),
            'singular_name' => __( 'Committee Page', 'bisonsrfc' ),
            'add_new_item' => __( 'Add new committee page', 'bisonsrfc' ),
            'edit_item' => __( 'Edit committee page', 'bisonsrfc' ),
            'view_item' => __( 'View committee page', 'bisonsrfc' ),
            'search_item' => __( 'Search committee page', 'bisonsrfc' ),
            ),
        'taxonomies' => array('page group'),
        'public' => true,
        'rewrite'=> array('slug' => 'committee-area'),
        'show_in_menu' => false,
        'has_archive' => true,
        'hierarchical' => true,
            )
    );
    
    
    register_post_type( 'committee-profile', array(
        
        'labels' => array (
            'name' => __( 'Committee Profile', 'bisonsrfc' ),
            'singular_name' => __( 'Committee Profile', 'bisonsrfc' ),
            'add_new_item' => __( 'Add new Committee Profile', 'bisonsrfc' ),
            'edit_item' => __( 'Edit Committee Profile', 'bisonsrfc' ),
            'view_item' => __( 'View Committee Profile', 'bisonsrfc' ),
            'search_item' => __( 'Search Committee Profile', 'bisonsrfc' ),
            ),
        'public' => true,
        'show_in_menu' => false,
        'has_archive' => true,
        'hierarchical' => true,
        'supports' => false
            )
    );
    
    
    /**
     * Flickr gallery type
     * Used to index flickr galleries, give them a freindly name and add them to the blog
     * 
     */
    register_post_type( 'photos', array(
        'labels' => array(
            'name' => __( 'photos', 'bisonsrfc' ),
            'singular_name' => __( 'photo', 'bisonsrfc' ),
        ),
	    'public' => true,
	    'exclude_from_search' => false,
	    'has_archive' => true
	    )
    );
    
    
    register_post_type ( 'membership_form', array(
        'public' => false, 
        'exclude_from_search' => true,
        'has_archive' => false,
    ) );
    
    register_post_type ( 'membership_fee', array(
        'labels' => array (
            'name' => __( 'Membership Fees', 'bisonsrfc' ),
            'singular_name' => __( 'Membership Fee', 'bisonsrfc' ),
            'add_new_item' => __( 'Add new fee', 'bisonsrfc' ),
            'edit_item' => __( 'Edit fee', 'bisonsrfc' ),
            'view_item' => __( 'View fee details', 'bisonsrfc' ),
            'search_item' => __( 'Search membership fees', 'bisonsrfc' ),
            ),

        'public' => true, 
        'exclude_from_search' => true,
        'show_ui' => true,
        'show_in_menu' => false,
        'show_in_nav_menus' => false, 
        'has_archive' => false,
        'supports' => false
    ) );
    
    register_post_type ( 'email_log', array(
        'public' => false, 
        'exclude_from_search' => true,
        'has_archive' => false,
    ) );
    
    register_post_type ( 'webhook', array(
        'public' => false, 
        'exclude_from_search' => true,
        'has_archive' => false,
    ) );

}
add_action( 'init', 'create_post_types');

// Add custom metaboxes to the forms
function add_custom_forms ( $post ) {
    add_meta_box(
        'player-page-edit',
        'Description',
        'player_page_description_form',
        'player-page',
        'normal',
        'core'

    );
    
    add_meta_box(
        'player-profile-edit',
        'Details',
        'player_profile_edit_form',
        'playerprofile',
        'normal',
        'core'
    );
    
    add_meta_box(
        'com-page-edit',
        'Description',
        'committee_page_description_form',
        'committee-page',
        'normal',
        'core'
    );

    add_meta_box(
        'fixture-edit',
        'Fixture details',
        'fixtures_content',
        'fixture',
        'normal',
        'high'
    );

    add_meta_box(
        'event-edit',
        'Event details',
        'events_content',
        'event',
        'normal',
        'high'
    );
    
    add_meta_box(
        'result-edit',
        'Match Result',
        'results_content',
        'result',
        'normal',
        'high'
    );
    

       add_meta_box(
        'fixture-link-selector',
        'Link to fixture',
        'fixture_link_selector',
        'post',
        'normal',
        'high'
    );
    
    
    if ( current_user_can('attribute_post' ) )
    {
       add_meta_box(
        'attribute-post',
        'Attribute Post',
        'attribute_post',
        'post',
        'normal',
        'high'
        );
    }
    
    
       add_meta_box(
        'committee-profile-edit',
        'Profile',
        'committee_profile',
        'committee-profile',
        'normal',
        'high'
    );
    
    add_meta_box(
        'fees-edit-form',
        'Membership Fees',
        'membership_fee_postform',
        'membership_fee',
        'normal',
        'core'
    );
    
}
add_action( 'add_meta_boxes', 'add_custom_forms');

// Callback functions to print custom form content
function player_profile_edit_form ( $post ) { include_once ( dirname(__FILE__)  . '/../postforms/player-profile.php' ); }
function fixtures_content( $post ) { include_once( dirname(__FILE__) . '/../postforms/fixtures.php'); }
function events_content( $post ) { include_once( dirname(__FILE__) . '/../postforms/events.php'); }
function results_content( $post ) { include_once( dirname(__FILE__) . '/../postforms/results.php');}
function committee_page_description_form( $post ) { include_once( dirname(__FILE__) . '/../postforms/com-page.php');}
function player_page_description_form( $post ) { include_once( dirname(__FILE__) . '/../postforms/player-page.php'); }
function fixture_link_selector ( $post ) { include_once( dirname(__FILE__) . '/../postforms/post.php'); }
function attribute_post ( $post ) { include_once( dirname(__FILE__) . '/../postforms/post-attribute.php'); }
function committee_profile ( $post ) { include_once( dirname(__FILE__) . '/../postforms/committee-profile.php'); } 
function membership_fee_postform ( $post ) { include_once( dirname(__FILE__) . '/../postforms/memfees.php'); } 

// Include custom post types in main blog
function modify_blog_post_types($query) {
    if( is_home() && $query->is_main_query() || is_feed() )
        $query->set( 'post_type', array('post', 'fixture', 'result', 'report', 'event', 'photoalbum') );
    return $query;
}
add_filter ('pre_get_posts', 'modify_blog_post_types');

// Exclude fixtures/results marked as hidden from blog
function exclude_hidden( $query ) {
    if ( $query->is_home() && $query->is_main_query() ) {
        $query->set( 'meta_query', array(
            array(
                'key'       =>  'hide_from_blog',
                'compare'   =>  'NOT EXISTS'
            )
        ) );
    }
}
add_action( 'pre_get_posts', 'exclude_hidden' );



// Load code that handles submission of custom post types
function save_custom_post_form( $post ) {
    if( file_exists( dirname( __FILE__  ) . '/../submission_handling/' . $_POST ['post_type'] . '.php' ) )
        include_once( dirname( __FILE__  ) . '/../submission_handling/' . $_POST ['post_type'] . '.php' );
}
add_action( 'save_post', 'save_custom_post_form');

// For child forms, if the user accidentilly finds his way onto them without a parent post set, remove the editing interface
function restrict_parentless_children_forms() {
    if( $_SERVER['PHP_SELF'] == '/wp-admin/post-new.php' &&
        !$_GET['parent_post']) {
            switch($_GET['post_type']) {
                case "report":
                    $type = 'report';
                    break;
                case "result":
                    $type = 'result';
                    break;
            }

            remove_post_type_support( $type, 'title');
            remove_post_type_support( $type, 'editor');
            remove_post_type_support( $type, 'author');
            remove_post_type_support( $type, 'excerpt');
            remove_post_type_support( $type, 'thumbnail');
            remove_post_type_support( $type, 'trackbacks');
            remove_post_type_support( $type, 'custom-fields');
            remove_post_type_support( $type, 'comments');
            remove_post_type_support( $type, 'revisions');
            remove_post_type_support( $type, 'page-attributes');
            remove_post_type_support( $type, 'post-formats');
        }
}
add_action( 'admin_init', 'restrict_parentless_children_forms');
