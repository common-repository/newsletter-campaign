<?php 
/*
Plugin Name: Newsletter Campaign
Description: Declares a plugin that will create a Newsletter For Mailchimp
Version: 1.0
License: GPLv2 */

register_activation_hook(__FILE__, 'plugins_activate');
register_deactivation_hook(__FILE__, 'plugins_dectivate');
function plugins_activate(){
    global $table_prefix, $wpdb;
    $news_create = "CREATE TABLE IF NOT EXISTS {$table_prefix}mailchimp_newsletter (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `title` varchar(100) NOT NULL,
    `subject` varchar(100) NOT NULL,
    `sender_name` varchar(100) NOT NULL,
    `email` varchar(100) NOT NULL,
    `content` text NOT NULL,                
    `campaign_id` text NOT NULL,                
    `send_status` varchar(100) NOT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=MyISAM";
    $wpdb->query($news_create);
}
function plugins_dectivate(){
    global $table_prefix, $wpdb;
    $news_del = "DROP TABLE {$table_prefix}mailchimp_newsletter";
    $wpdb->query($news_del);
}
$hdn_url = site_url().'/wp-admin';
//echo '<input type="hidden" id="hdn_path" val="'.$hdn_url.'">';
function mailchimp_menus() {
    add_menu_page('Mailchimp Campaign', 'Mailchimp Campaign', 8, 'mailchimp_page', 'mailchimp_settings_home',plugins_url( 'newsletter-campaign/images/icon.png' ));

    add_submenu_page('mailchimp_page',
    'Create new', 'Create new', 8,
    'mailchimp_new', 'mailchimp_new');

    add_submenu_page('mailchimp_page',
    'Edit Item', 'Edit Item', 8,
    'mailchimp_edit', 'mailchimp_edit');

    add_submenu_page('mailchimp_page',
    'Configuration', 'Configuration', 8,
    'mailchimp_configuration', 'mailchimp_configuration');

}
add_action("admin_menu", "mailchimp_menus");

//Include Required Files
/**
* Register style sheet.
*/

wp_register_style( 'newsletter-campaign', plugins_url( 'newsletter-campaign/includes/newsletter-campaign.css' ) );
wp_enqueue_style( 'newsletter-campaign' );

wp_enqueue_script( 'newsletter-campaign', plugins_url( 'newsletter-campaign/includes/newsletter-campaign.js' ) );
//END Include Required Files

//For Status Updated
function showAdminMessages()
{
    if($_GET['s'] == '1'){ //Campaign Create Page
        echo '<div class="updated settings-error" id="setting-error-settings_updated">
        <p><strong>Campaign Create successfully.</strong></p>
        </div>';  
    }
    if($_GET['del'] == '1'){ //Campaign Delete Page
        echo '<div class="updated settings-error" id="setting-error-settings_updated"> 
        <p><strong>Campaign Delete successfully.</strong></p>
        </div>';  
    }
    if($_GET['conf'] == '1'){ //Configuration Page
        echo '<div class="updated settings-error" id="setting-error-settings_updated"> 
        <p><strong>Save successfully.</strong></p>
        </div>';  
    }
}
add_action('admin_notices', 'showAdminMessages');
//END Status Updated

$plugin_path = plugin_dir_path( __FILE__ );
//$plugin_path = plugins_url( 'newsletter-campaign', __FILE__ );
//$plugin_path = ABSPATH . 'wp-content/plugins/newsletter-campaign';
function mailchimp_settings_home(){
$plugin_path = plugin_dir_path( __FILE__ );
echo '<input type="hidden" id="hdn_path" val="'.$hdn_url.'">';
    require_once ($plugin_path.'/campaign-main.php');//Main Page
}

function mailchimp_new(){
echo '<input type="hidden" id="hdn_path" val="'.$hdn_url.'">';
$plugin_path = ABSPATH . 'wp-content/plugins/newsletter-campaign';
    require_once($plugin_path.'/campaign-new.php'); //New Page / Create Campaign
}

function mailchimp_edit(){
echo '<input type="hidden" id="hdn_path" val="'.$hdn_url.'">';
$plugin_path = plugin_dir_path( __FILE__ );
    require_once ( $plugin_path.'/campaign-edit.php' ); //Edit Campaign Page
}

function mailchimp_configuration(){
$plugin_path = plugin_dir_path( __FILE__ );
    require_once ( $plugin_path.'/campaign-configuration.php' );//Configuration Page
}

if($_REQUEST['action'] == 'delete_camp'){
echo '<input type="hidden" id="hdn_path" val="'.$hdn_url.'">';
$plugin_path = plugin_dir_path( __FILE__ );
    require_once ( $plugin_path.'/campaign-delete.php' );//Delete Campaign Page
}

//Post Ajax Request
function post_data() {
    $post_cont = get_post($_REQUEST['id'], ARRAY_A);
    echo $post_cont['post_content'];            
    die;
}
add_action( 'wp_ajax_post_data', 'post_data' );
add_action( 'wp_ajax_nopriv_post_data', 'post_data' );
//Post Ajax Request

//Send Test Email
function post_test_email() { 
    $camp_id = $_REQUEST['camp_id'];
    $email_ids = $_REQUEST['email_ids'];
    $plugin_path = plugin_dir_path( __FILE__ );
    require_once ( $plugin_path.'/campaign-test-email.php' );//Delete Campaign Page        
    die;
}
    add_action( 'wp_ajax_post_test_email', 'post_test_email' );
    add_action( 'wp_ajax_nopriv_post_test_email', 'post_test_email' );
//Send Test Email

//Send Now Email
function post_send_now() {
    $camp_id = $_REQUEST['camp_id'];
    $id = $_REQUEST['id'];
    $plugin_path = plugin_dir_path( __FILE__ );
    require_once ( $plugin_path.'/campaign-send-now.php' );//Delete Campaign Page        
    die;
}
    add_action( 'wp_ajax_post_send_now', 'post_send_now' );
    add_action( 'wp_ajax_nopriv_post_send_now', 'post_send_now' );
//Send Now Email