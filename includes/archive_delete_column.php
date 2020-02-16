  
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

function deleteColumnFromConditions($id, $conditions) {
	if (is_array($conditions[0])) {
		[$n_cond, $b] = deleteColumnFromConditions($id, $conditions[0]);
		[$n_cond2, $b2] = deleteColumnFromConditions($id, $conditions[2]);
		if ($n_cond == 'null' && $n_cond2 == 'null') {
			$c = 'null';
		} elseif ($n_cond == 'null') {
			$c = $n_cond2;
		} elseif ($n_cond2 == 'null') {
			$c = $n_cond;
		} else {
			$c = [$n_cond, $conditions[1], $n_cond2];
		}
		return [$c, ($b || $b2)];
	} else {
		if ($conditions[0] == $id) {
			return ['null', true];
		} else {
			return [$conditions, false];
		}	
	}
	
}
// Include config file
require_once "config.php";

if(isset($link)) {
	
		$upd = "ALTER TABLE archive_data DROP COLUMN `" . ((int) strip_tags( trim($_POST['str']))) . "`";  

mysqli_query($link, $upd);

$sql = "DELETE FROM archive_columns WHERE `id`='" . ((int) strip_tags( trim($_POST['str']))) . "'" ;
$colresult = $link->query($sql);

mysqli_query($link, $sql);


$a = "SELECT id, description, dat, cond FROM archive_views";

$viewresult = $link->query($a);

while($row = mysqli_fetch_array($viewresult))
{
	
	$i = $row['id'];
	$d = $row['description'];
	$d = str_replace("<p>Achtung: Diese View wurde aufgrund einer gelöschten Spalte geändert.</p>","",$d);
	$d .= "<p>Achtung: Diese View wurde aufgrund einer gelöschten Spalte geändert.</p>";
	$c = unserialize($row['dat']);

	$new = array();
	$ix = 0;
	$work = false;
	foreach($c as $id => $s) {
		if (!($s == $_POST['str'])) {
			$new[$ix] = $s;
			$ix = ($ix + 1);
		} else {
			$work = true;
		}
	}


	$conds = unserialize($row['cond']);
	if ($conds == []) {
		$n_conds = [];
	} else {
		[$n_conds, $c_work] = deleteColumnFromConditions( $_POST['str'], $conds);
		$work = ($work || $c_work);
	}

	if ($work) {
		$b = "UPDATE archive_views SET dat = '" .  (serialize($new)) . "' WHERE id = '" .  $i . "';";
		$e = "UPDATE archive_views SET description = '" .  ($d) . "' WHERE id = '" .  $i . "';";
		$f = "UPDATE views SET cond = '" . (serialize($n_conds)) . "' WHERE id = '" . $i . "';";
		
		mysqli_query($link, $b);
		mysqli_query($link, $e);
		mysqli_query($link, $f);
	}
}

			
}

?>