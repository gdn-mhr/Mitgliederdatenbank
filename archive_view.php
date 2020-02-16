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
require_once "includes/config.php";

if(!isset($_SESSION["selected_view"])){
    header("location: show_views.php");
    exit;
}

$index = $_SESSION["selected_view"];

?>

<?php 
	include 'includes/header.php';
?>	
	<script>
		var isset = false
		function toggleFilter () {
			if (isset == true) { 
				 $('#table').bootstrapTable('refreshOptions', {
					filterControl: false,
				});
				isset = false;
			} else {
				 $('#table').bootstrapTable('refreshOptions', {
					filterControl: true,
				});
				isset = true;
      }
		}
	</script>

	<div id="toolbar">
		<button id="filter-toggle" class="btn btn-outline-info" onClick="toggleFilter();" >
		<i class="glyphicon glyphicon-remove"></i> Erweiterte Filter
		</button>
	</div>


<?php

function arrayToWhere ($cond) {
	if (is_array($cond[0])) {
		if ($cond[1] == 1) {
			$op = 'AND';
		} else {
			$op = 'OR';
		}
		return '(' . (arrayToWhere($cond[0])) . ' ' . $op . ' ' . (arrayToWhere($cond[2])) . ')';
	} else {
		switch ($cond[1]) {
			case 1:
				$t = ' = ';
				break;
			case 2:
				$t = ' > ';
				break;
			case 3:
				$t = ' < ';
				break;
			case 4:
				$t = ' >= ';
				break;
			case 5:
				$t = ' <= ';
				break;
			case 6:
				$t = ' <> ';
				break;
			case 7:
				$t = ' LIKE ';
				break;
			case 8:
				$t = ' NOT LIKE ';
				break;
		}
		
		return '( `' . $cond[0] . '`' . $t . "'" . $cond[2] . "' )" ;
	}
}

$sql = "SELECT id, name, description, dat, cond FROM archive_views WHERE id='" . $index . "';";
$result = $link->query($sql);

while($row = mysqli_fetch_array($result))
{
$id = $row['id'];
$name = $row['name'];
$desc = $row['description'];
$dat = unserialize($row['dat']);
$conds = unserialize($row['cond']);
}
//prepare statements
$c = "SELECT id, name, access_level FROM archive_columns WHERE id='1' UNION ";
foreach($dat as $idx) {
	$c .= "SELECT id, name, access_level FROM archive_columns WHERE id='".$idx."' UNION ";
}
$c = rtrim($c, "UNION ");
$c .= ";"; 

$colresult = $link->query($c);

//prepare statement to retrieve real data
$a = "SELECT ";

unset($row);

$cols = array();
while($row = mysqli_fetch_array($colresult))
{
	$a .= ("`" . $row['id'] . "` ,");
	$cols[$row['id']] =  $row['name'];
}
$a = rtrim($a, ",");
$a .= " FROM archive_data"; 

if ($conds != []) {
	$a = $a ." WHERE " . arrayToWhere($conds);
}	


$dataresult = $link->query($a);

echo "<div><table  
	id=\"table\"
	data-toggle=\"table\"
	data-locale=\"de-DE\"
	data-toggle=\"table\"
	data-search=\"true\"
	data-show-columns=\"true\"
	data-editable=\"true\"
	data-editable-url=\"includes/archive_post.php\"
	data-toolbar=\"#toolbar\"
	data-search=\"true\"
	data-show-columns-toggle-all=\"true\"
	data-show-export=\"true\"
	data-click-to-select=\"true\"
	data-pagination=\"true\"
	data-id-field=\"ID\"
	data-page-list=\"[10, 25, 50, 100, all]\"
	data-filter-control=\"false\"
	data-show-search-clear-button=\"true\">
	<thead>
<tr>";

foreach ($cols as $i => $cname) {
	if ($i > 1) {
    echo "<th data-field=\"". $i ."\" data-filter-control=\"input\" data-sortable=\"true\" data-editable=\"true\">" . $cname . "</th>";
	} else {
	echo "<th data-field=\"ID\" data-sortable=\"true\" data-editable=\"false\">" . $cname . "</th>";
	}
}
echo "</tr>";
echo "</thead>";
unset($cname);
unset($i);

//print_r ($dataresult);

while($row = mysqli_fetch_array($dataresult, MYSQLI_NUM))
{
	
echo "<tr>";

//print_r ($row);


foreach ($row as $data) {
		echo "<td>" . $data . "</td>";

}
unset($data);
unset($i);
echo "</tr>";
}
echo "</table> </div>";







?>

<script>
$.fn.editable.defaults.mode = 'inline';

  $(function() {
    $('#table').bootstrapTable();
  })
</script>


<?php 
	include 'includes/footer.php';
?>