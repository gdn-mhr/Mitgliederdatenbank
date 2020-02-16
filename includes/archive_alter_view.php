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


function packConds($str) {
	if(isset($_POST['c_c-' . $str])) {
		return [$_POST['c_c-' . $str], $_POST['c_t-' . $str], $_POST['c_i-' . $str]];
	} else {
		return [(packConds($str . '-1')), $_POST['c_o-' . $str], (packConds($str . '-2'))];
	}

}



if(isset($link)) {
	$id = (int) strip_tags( trim($_POST['id']));
	$bez = strip_tags( trim($_POST['name']));
	$des = strip_tags( trim($_POST['desc']));
	$cols = $_POST['col'];
	
	if (strip_tags( trim($_POST['has_cond'])) == 'true') {
		$conds = packConds('1');
	} else {
		$conds = [];
	}
	
	$upd = "UPDATE archive_views SET name ='" . $bez . "', description = '" . $des . "', dat = '" . serialize($cols) . "', cond = '" . serialize($conds) . "' WHERE id = '" . $id . "';";
	

mysqli_query($link, $upd);
	
header("location: ../archive_show_views.php");
    exit;	
}

?>

?>