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

if($_SERVER["REQUEST_METHOD"] == "POST"){
	
	$_SESSION['selected_view'] = $_POST['name'];
	header("location: view.php");
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

$sql = "SELECT id, name, description FROM views";
$colresult = $link->query($sql);

$ns = array();
$ds = array();
while($row = mysqli_fetch_array($colresult))
{
	$ns[$row['id']] =  $row['name'];
	$ds[$row['id']] =  $row['description'];
}
echo '<div style="max-width: 850px; padding: 20px; display: block; margin-left: auto; margin-right: auto;">';
echo '<h2>Views verwalten</h2><p></p>';
echo '<div class="table-responsive"><table class="table table-bordered" id="dynamic_field_c"> <col width="auto"><col width="auto"><col width="350px">';
foreach($ns as $i => $n) {
	echo '<tr>';
	echo '<td><h2>'.$i.'</h2></td>';
	echo ("<td><h3>". $n . "</h3>");
	echo ("<p>" . $ds[$i] . "</p></td>");
	echo '<td style=" text-align: center;"><div style="display:inline-block;">';
	echo ('<form style="display:inline-block; padding:5px;" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '" method="post"><button type="submit" class="btn btn-outline-success" name="name" id="' . $i . '" value="' . $i . '">Öffnen</button></form>');
	echo ('<form style="display:inline-block; padding:5px;" action="edit_view.php" method="post"><input type="submit" class="btn btn-outline-info" name="' . $i . '" value="Bearbeiten" /></form>');
	echo ('<form style="display:inline-block; padding:5px;" action="includes/delete_view.php" method="post"><input type="submit" class="btn btn-outline-danger" name="' . $i . '" value="Löschen" /></form>');
	echo '</div></td></tr>';
}
echo '</table></div></div>'; 
}



?>

<?php 
	loadData($link);
?>
	
	
	<hr>
	
	
	
<div style="max-width: 400px; display: block; margin-left: auto; margin-right: auto;">
<h2>Neue View hinzufügen</h2>
<form action="new_view.php" method="POST" role="form" class="form-horizontal">
  <button type="submit" class="btn btn-outline-success">Neu</button>
</form>
</div>    
<?php 
	include 'includes/footer.php';
?>