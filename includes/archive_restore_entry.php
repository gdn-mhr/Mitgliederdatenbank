  
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

function console_log( $data ){
  echo '<script>';
  echo 'console.log('. json_encode( $data ) .')';
  echo '</script>';
}

// Include config file
require_once "config.php";

if(isset($link)) {
	
$a = "SELECT * FROM `archive_data` WHERE `1`='" . (strip_tags( trim($_POST['str']))) . "'"	;
$dates = $link->query($a);

$dat = array();

while($row = mysqli_fetch_array($dates))
{
	foreach ($row as $id => $val) {
		$dat[$id] = $val;
	}
	unset($id);
	unset($val);

}	


	$a = "SELECT id FROM columns";
	$mcolresult = $link->query($a);


	
	
	while($row = mysqli_fetch_array($mcolresult))
	{
	
		$lock_cols[] =  $row['id'];
	}
	
	$b = "(";
	$c = "(";
	foreach ($lock_cols as $val) {
		
		$b = $b . "`" . $val . "` ,";
		$c = $c . "'" . $dat[$val] . "' ,";
	}
	unset($val);
	$b = rtrim($b, ",");
	$c = rtrim($c, ",");

	
$ins = "INSERT INTO data " . $b . ") VALUES " . $c . ");";	
error_log($ins);
mysqli_query($link, $ins);

$sql = "DELETE FROM archive_data WHERE `1`='" . (strip_tags( trim($_POST['str']))) . "'" ;

mysqli_query($link, $sql);
			
}

?>