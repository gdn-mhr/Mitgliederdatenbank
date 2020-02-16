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

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
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
require_once "includes/config.php";

?>

<?php 
	include 'includes/header.php';
?>


<?php

function loadData($link) {

	$sql = "SELECT id, name, access_level FROM columns";
	$colresult = $link->query($sql);

	while($row = mysqli_fetch_array($colresult))
	{
		$cols[$row['id']] =  $row['name'];
	}
 

echo "<div style=\"max-width: 400px; display: block; margin-left: auto; margin-right: auto;\">
<h2>Neuen Eintrag hinzufügen</h2>
<form action=\"includes/save_entry.php\" method=\"POST\" role=\"form\" class=\"form-horizontal\">";
//print_r ($cols);
foreach ($cols as $i => $cname) {
	if ($i > 1) {
    echo "<div class=\"form-group\">
    <label for=\"" . $i . "\">" . $cname . "</label>
    <input type=\"text\" class=\"form-control\" id=\"" . $i . "\"  name=\"" . $i . "\">
  </div>";
	}
}
echo "<button type=\"submit\" class=\"btn btn-outline-success\">Speichern</button>
</form>
</div> ";
unset($cname);
unset($i);

}



?>

<?php 
	loadData($link);
?>

    
<?php 
	include 'includes/footer.php';
?>