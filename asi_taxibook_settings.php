<?php
/*
 Plugin Name: ASI Taxi Booking
 Description: This plugin calculates fare, distance and duration. Use [asi-booktaxi] shortcode to display fare calculator.
 Version: 1.1
 Plugin URI: http://www.adaptivesolutionsinc.com/
 Author: Adaptive Solutions Inc
 Author URI: http://www.adaptivesolutionsinc.com/
 License: GNU General Public License v3 or later
*/
define('ABSPATH', dirname(__FILE__) . '/');
function asi_book_scripts() {	
	if(!is_admin())
	{ 
        $google_map_api = 'https://maps.google.com/maps/api/js?sensor=true&libraries=places&language=en-AU';
        wp_enqueue_script('google-places', $google_map_api);
        wp_register_style('asi_taxi_style', plugins_url('css/asi_taxibook_style.css',__FILE__));
        wp_enqueue_style('asi_taxi_style');
       	wp_register_script('asi_taxi_script', plugins_url('js/asi_taxibook_script.js', __FILE__ ),array('jquery'));
        wp_enqueue_script('asi_taxi_script');    
	}
}
add_action('wp_enqueue_scripts', 'asi_book_scripts');
// Create Tables 
register_activation_hook(__FILE__,'asi_book_create_table');
function asi_book_create_table()
{
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $tablename=$wpdb->prefix.'fare';
    if($wpdb->get_var("SHOW TABLES LIKE '$tablename'")!=$tablename)
    {
            $sql = "CREATE TABLE $tablename (
                    fare_id INT(11) NOT NULL AUTO_INCREMENT,
                    mile TEXT, 
                    stop TEXT, 
                    seat TEXT,
                    adul TEXT,
                    inf TEXT,
                    lugg TEXT, 
                    minute TEXT,
                    curr TEXT, 
                    color TEXT,
                    PRIMARY  KEY (fare_id)
                    )$charset_collate;";  
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
    $wpdb->query($wpdb->prepare("INSERT INTO $tablename(mile,stop,seat,adul,inf,lugg,minute,curr,color) VALUES(%s,%s,%s,%s,%s,%s,%s,%s,%s)", array(0,0,0,0,0,0,0,'$','#B4D568')));                
    }    
    $tablecar=$wpdb->prefix.'cartypes';
    if($wpdb->get_var("SHOW TABLES LIKE '$tablecar'")!=$tablecar)
    {
            $sql = "CREATE TABLE $tablecar (
                    c_id INT(11) NOT NULL AUTO_INCREMENT,
                    name VARCHAR(50) NOT NULL, 
                    fare TEXT,
                    adul TEXT,
                    baby TEXT,
                    inf TEXT,
                    lugg TEXT,                 
                    PRIMARY  KEY (c_id)
                    )$charset_collate;";  
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);            
    } 
}
function asi_deletetaxi_process()
{
    global $wpdb;
    $id=sanitize_text_field($_POST['id']);
    $table_name = $wpdb->prefix."cartypes";
    $wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE c_id=%d",$id));
}
function asi_taxibooking_process()
{
     $admin_email = get_option('admin_email');
     $name=sanitize_text_field($_POST['name']);
     $email=sanitize_text_field($_POST['email']);
     $cell=sanitize_text_field($_POST['cell']);
     $pick=sanitize_text_field($_POST['pick']);
     $drop=sanitize_text_field($_POST['drop']);
     $date=sanitize_text_field($_POST['date']);
     $headers = 'From:'.$email."\r\n";
     $message = 'Name '.$name. '</br>';
     $message.='\n'.'Cell Number '.$cell.'\n';
     $message.='PickUp Address '.$pick.'\n';
     $message.='DropOff Address '.$drop.'\n';
     $message.='Date and Time '.$date.'\n';
     $subject='Taxi Booking';
    mail($admin_email,$subject,$message,$headers);
    if(mail)
    {
        echo "sent";
    }
    else
    {
        echo "not sent";
    }
    exit;
}
add_action('wp_ajax_asi_deletetaxi','asi_deletetaxi_process');
add_action('wp_ajax_asi_taxibooking','asi_taxibooking_process');
add_action( 'wp_ajax_nopriv_asi_taxibooking', 'asi_taxibooking_process');
include 'asi_taxibook_main.php';
include 'asi_taxibook_admin.php';
?>