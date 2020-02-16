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


require_once "config.php";

if(isset($link)) {
	$id = (int) strip_tags( trim($_POST['id']));
	$bez = strip_tags( trim($_POST['name']));
	$des = strip_tags( trim($_POST['desc']));
	$cols = $_POST['col'];
	
	$upd = "UPDATE archive_views SET name ='" . $bez . "', description = '" . $des . "', dat = '" . serialize($cols) . "' WHERE id = '" . $id . "';";
	

//	echo $upd;
//$upd = "UPDATE columns SET name = '" .  $_POST['value'] . "' WHERE `id` = '" .  $_POST['pk'] . "'";   
//$upd = "UPDATE data SET `2` = 'Gideonqsqq' WHERE `1` = '1'";
mysqli_query($link, $upd);
	
header("location: ../archive_show_views.php");
    exit;	
}

?>

?>