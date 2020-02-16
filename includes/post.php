  
<?php
// Initialize the session
session_start();

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
	header("location: login.php");
    exit;
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp


 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Check if the user has valid access_level
if($_SESSION["access_level"]<=1){
    header("location: welcome.php");
    exit;
}

// Include config file
require_once "config.php";

if(isset($link)) {
	
	file_put_contents("post.log", print_r($_POST, true));

$upd = "UPDATE data SET `" . (strip_tags( trim($_POST['name']))) . "` = '" .  (strip_tags( trim($_POST['value']))) . "' WHERE `1` = '" .  (strip_tags( trim($_POST['pk']))) . "'";   

mysqli_query($link, $upd);
			
}

?>