<?php 

define('INCLUDED', TRUE);

include_once('dBug.php');


include_once('init/get_webhooks.php');

include_once('helper_functions/reset_password.php');
include_once('init/modify_login_page.php');
include_once('init/current_user.php');
include_once('init/mw_logout.php');
include_once('helper_functions/timer.php');

include_once('helper_functions/js_redirect.php');
global $timer;
$timer = new ScriptTimer();
add_filter('show_admin_bar', '__return_false');
add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));


// Get flash message from querystring if there is one
if ( wp_verify_nonce ( $_GET['nonce'], 'bisons_flashmessage_nonce') )
    $GLOBALS['bisons_flash_message'] = stripslashes ( $_GET['flash'] );

// Dependencies
include_once('Mandrill/Mandrill.php');
$mandrill = new Mandrill('ZzbBwttWRHJ41GL4BZmmsQ');

// Modify Dashboard menus
include_once( 'init/admin_menus.php' );
include_once( 'init/set_bloginfo.php' );
include_once( 'init/enqueue_header_linked_files.php');
include_once( 'init/sidebar.php' );
include_once( 'init/menus.php' );

// Classes
include_once('classes/Wordpress_Form.php');
include_once('classes/WP_List_table_copy.php');

// List tables
include_once('listTables/fixtures.php');
include_once('listTables/emails.php');

// Feeds
include_once('feeds/ical-all.php');

// CRON scripts
include_once('cron/cron_init.php');
include_once('init/excerpt.php');

// Email handling
include_once('PHPMailer/PHPMailerAutoload.php');
include_once('email/send_bison_mail.php');


// Load official GoCardless library
include_once('GoCardless/init.php');
include_once('init/payment_statuses.php');


// Custom shortcodes
include_once('shortcodes/feestable.php');
include_once('shortcodes/contactform.php');

// My blog settings
include_once('init/settings.php');

// API wrappers which provide the Facebook and Twitter widget feeds
include_once('API_Wrapper/twitter.php');
include_once('API_Wrapper/facebook.php');
include_once('API_Wrapper/flikr.php');

// Custom widgets built into this theme
include_once('widgets/twitter.php');
include_once('widgets/facebook.php');
 include_once('widgets/mobiletext.php');


// Various helper functions I used to reduce typing
include_once('helper_functions/how_long_ago.php');
include_once('helper_functions/boom.php');
include_once('helper_functions/check_user_roles.php');
include_once('helper_functions/link_if_available.php');
include_once('helper_functions/reformat_date.php');
include_once('helper_functions/fixture_usort_by_date.php');
include_once('helper_functions/getage.php');
include_once('helper_functions/pencetopounds.php');
include_once('helper_functions/datetime_string.php');
include_once('helper_functions/login_fix.php');



// API Customization
include_once( 'init/custom_roles.php' );
include_once( 'init/custom_taxonomies.php' );
include_once( 'init/custom_post_types.php' );
include_once( 'init/advanced_posting_layout.php' );
include_once( 'init/settings_api.php');
include_once( 'init/tinymce.php' );
include_once( 'init/shortcodes.php' );
include_once( 'init/rewrites.php' );
include_once( 'init/createtables.php');
include_once( 'init/dashboard.php'); 
include_once( 'init/redirects.php');
include_once( 'init/better-comments.php' );
include_once( 'init/start_sessions.php');


// Form handlers
if ( wp_verify_nonce( $_POST['nonce'], 'wordpress_form_submit' ) )
    include_once('form_handlers/' . $_POST['wp_form_id']. '.php');

include_once('listTables/players_no_mem_form.php');
if ( wp_verify_nonce ( 'bulk-'.Players_No_Mem_form::$plural )  && $_POST['action'] != '-1' )
    include_once ('list_table_bulk_actions/' . $_POST['action'] . '.php' );


include_once('listTables/membership_forms.php');
if ( wp_verify_nonce ( $_POST['_wpnonce'], 'bulk-'.Membership_Forms_Table::$plural ) && $_POST['action'] != '-1')
    include_once ('list_table_bulk_actions/' . $_POST['action'] . '.php' );
    

// Fix 'insert to post' button not visible bug.
add_filter( 'get_media_item_args', 'force_send' );
function force_send($args){
    $args['send'] = true;
    return $args;
}
$timer->record_execution_time('End of functions.php');