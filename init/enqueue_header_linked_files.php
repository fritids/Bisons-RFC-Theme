<?php
/**
 * Anything that needs to go in the HTML header is called from this file
 */

// Main page
function header_css_and_js($hook) {

    // Javascript
    wp_register_script('web_font_loader', '//ajax.googleapis.com/ajax/libs/webfont/1.4.7/webfont.js', null, '1.4.8'); 
    wp_enqueue_script('web_font_loader');
    wp_register_script('web_font_loader_local', get_template_directory_uri() . '/scripts/webfont.js', null, '1.0.9'); 
    wp_enqueue_script('web_font_loader_local');
    wp_register_script('dynamicforms', get_template_directory_uri() . '/scripts/dynamicforms.js', null, '1.8.3', true); 
    wp_register_script('formvalidation', get_template_directory_uri() . '/scripts/formvalidation.js', null, '1.4.3', true); 
    wp_register_script('stripe', 'https://js.stripe.com/v2/', false, '2.0.0', true); // Not enqueued here as not necessary on every page
    if( !is_admin()){
    	wp_deregister_script('jquery');
    	wp_register_script('jquery', ("http://code.jquery.com/jquery-2.1.1.min.js"), false, '2.1.1');
    }
    
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-migrate', $GLOBALS['blog_info']['url'].'/wp-includes/js/jquery/jquery-migrate.min.js', false, '1.2.1', true);
    wp_register_script( 'jquery_maps_plugin', get_template_directory_uri() . '/scripts/gmap3.min.js', false, '1.0.0', true);
    wp_enqueue_script( 'jquery_maps_plugin' );
    wp_register_script( 'google_maps_api', 'http://maps.googleapis.com/maps/api/js?key=AIzaSyB5uu63Ejv1pU0TKQrZa_uzZN_BMbh7Qyo&sensor=false', false, '1.0.1', true);
    wp_enqueue_script( 'google_maps_api' );
        wp_register_script( 'magnific_js', get_template_directory_uri() . "/magnific/magnific.min.js", null, null, true);
    wp_enqueue_script('magnific_js');
  
    wp_register_script( 'main_scripts_file', get_template_directory_uri() . '/scripts/scripts.js', null, '1.7.4', true );
    wp_enqueue_script( 'main_scripts_file' );
    wp_register_script( 'ajax_scripts', get_template_directory_uri() . '/scripts/AJAX.js', null, '1.0.5', true );
    wp_enqueue_script( 'ajax_scripts' );
    wp_register_script( 'hideaddybar', get_template_directory_uri() . '/scripts/hideaddressbar.js', null, '1.0.0', true );
    wp_enqueue_script( 'hideaddybar' );

    wp_register_script( 'respond', get_template_directory_uri() . '/scripts/respond.js', false, '1.0.0', true);
    wp_enqueue_script( 'respond');
    $othersettings = get_option( 'other-settings-page' );
    $id = $othersettings['analytics-id'];
    wp_register_script( 'googleanalytics', get_template_directory_uri() . "/scripts/analytics.js.php?id=$id", null, null, true );
    wp_enqueue_script('googleanalytics');
    
    // CSS
    wp_register_style('main_css_file', $GLOBALS['blog_info']['template_url'].'/stylesheets/main.css', false, '7.1.4');
    wp_enqueue_style('main_css_file');
      
    wp_register_style( 'magnific_css', get_template_directory_uri() . '/magnific/magnific.css', false, '1.0.0');
    wp_enqueue_style( 'magnific_css');
    
    wp_register_style('colorscheme_css_file', $GLOBALS['blog_info']['template_url'].'/stylesheets/blacknpink.css', false, '1.1.3'); 
    wp_enqueue_style('colorscheme_css_file');
}
add_action ( 'wp_enqueue_scripts', 'header_css_and_js');

// Admin area
function admin_js_and_css($hook) {
    global $post_type;
    wp_register_script('dynamicforms', get_template_directory_uri() . '/scripts/dynamicforms.js', null, '1.8.3', true); 
    wp_register_script('formvalidation', get_template_directory_uri() . '/scripts/formvalidation.js', null, '1.3.8  ', true); 
    wp_register_script( 'email_log_js', get_template_directory_uri() . '/scripts/emaillogscripts.js', false, '1.0.6 ', true);
    wp_register_script( 'manage_players_js', get_template_directory_uri() . '/scripts/manage_players.js', false, '1.1.6', true);
    wp_register_script( 'custom_admin_js', get_template_directory_uri() . '/scripts/adminscript.js.php?post='.(isset($_GET['post']) ? $_GET['post'] : '').'&templateurl='.urlencode( get_template_directory_uri() ), false, '1.8.5', true);
    wp_enqueue_script( 'custom_admin_js' );
    wp_register_script( 'jquery_maps_plugin', get_template_directory_uri() . '/scripts/gmap3.min.js', false, '1.0.0', true);
    wp_enqueue_script( 'jquery_maps_plugin' );
    wp_register_script( 'google_maps_api', 'http://maps.googleapis.com/maps/api/js?key=AIzaSyB5uu63Ejv1pU0TKQrZa_uzZN_BMbh7Qyo&sensor=false', false, '1.0.1', true);
    wp_enqueue_script( 'google_maps_api' );        
    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
    wp_register_style( 'custom_edit_css', get_template_directory_uri() . "/stylesheets/style-admin.php?post-type=".$post_type, false, '1.2.5');
    wp_enqueue_style( 'custom_edit_css' );
    wp_register_script('web_font_loader', '//ajax.googleapis.com/ajax/libs/webfont/1.4.7/webfont.js', null, '1.4.8'); 
    wp_enqueue_script('web_font_loader');
    wp_register_script('web_font_loader_local', get_template_directory_uri() . '/scripts/webfont.js', null, '1.0.9'); 
    wp_enqueue_script('web_font_loader_local');

    add_thickbox();
}
add_action ( 'admin_enqueue_scripts' , 'admin_js_and_css');