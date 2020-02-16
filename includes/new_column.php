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

if(isset($link) && isset($_POST['Name'])) {
	
	
	mysqli_set_charset($link, "utf8");	
	
	$upd = "INSERT INTO `columns` (`id`, `name`, `access_level`) VALUES (NULL, '" . (strip_tags( trim($_POST['Name']))) . "', '0');";   

	mysqli_query($link, $upd);
	
	
	
	
	$sql = "SELECT id, name, access_level FROM columns WHERE `name`='" . (strip_tags( trim($_POST['Name']))) . "'";

$colresult = $link->query($sql);

//prepare statement to retrieve real data


$cols = array();
while($row = mysqli_fetch_array($colresult))
{
	$id = $row['id'];
}
	$a = "ALTER TABLE data ADD `" . $id . "` varchar(1024);"; 

	echo $a;
	mysqli_query($link, $a);

	$upd_a = "INSERT INTO `archive_columns` (`id`, `name`, `access_level`) VALUES (" . $id . ", '" . (strip_tags( trim($_POST['Name']))) . "', '0');";   
	echo $upd_a;
	mysqli_query($link, $upd_a);

	$a_a = "ALTER TABLE archive_data ADD `" . $id . "` varchar(1024);"; 

	echo $a_a;
	mysqli_query($link, $a_a);

	header("Location: ../edit_columns.php");
    exit;

			
}

?>