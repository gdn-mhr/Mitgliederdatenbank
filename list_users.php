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
if($_SESSION["access_level"] < 5){
    header("location: welcome.php");
    exit;
}

// Include config file
require_once "includes/config.php";

?>

<?php 
	include 'includes/header.php';
?>	
<h2>Benutzer verwalten</h2>

<?php

function loadData($link) {

$sql = "SELECT id, username, access_level FROM users;";
$result = $link->query($sql);

//prepare statement to retrieve real data


$cols = array();
while($row = mysqli_fetch_array($result))
{
	$cols[$row['id']] =  $row['username'];
	$al[$row['id']] =  $row['access_level'];
}


echo "<div><table  
	data-locale=\"de-DE\"
	data-toggle=\"table\"
	data-search=\"true\"
	data-show-export=\"true\"
	data-pagination=\"true\"
	data-id-field=\"ID\"
	data-page-list=\"[10, 25, 50, 100, all]\"
	data-editable=\"true\"
	data-editable-url=\"includes/post_user.php\">
	<thead>
<tr>";
//print_r ($cols);

    echo "<th data-field=\"ID\" data-editable=\"false\">ID</th>";
	echo "<th data-field=\"username\" data-editable=\"true\">Name</th>";
	echo "<th data-field=\"access_level\" data-editable=\"true\">Access Level</th>";
	echo '<th data-field="Delete" data-editable="false" data-formatter="operateFormatter" data-events="operateEvents">Delete</th>';
echo "</tr>";
echo "</thead>";
	


//print_r ($row);


foreach ($cols as $i => $cname) {
	echo "<tr>";
    echo "<td>" . $i . "</td>";
	echo "<td>" . $cname . "</td>";
	echo "<td>" . $al[$i] . "</td>";
	if ($_SESSION["id"] == $i) {
		echo "<td data-record-id=\"" . $i . "\" data-record-title=\"" . $cname . "\" >1</td>";
	} else {
		echo "<td data-record-id=\"" . $i . "\" data-record-title=\"" . $cname . "\" > </td>";
	}
	echo "</tr>";
}
unset($cname);
unset($i);
echo "</table> </div>";



}



?>

<?php 
	loadData($link);
?>

<script>
$.fn.editable.defaults.mode = 'inline';

  $(function() {
    $('#table').bootstrapTable();
  })
  
    function operateFormatter(value, row, index) {
		if (1 > value) {
			return '<a class="remove" href="javascript:void(0)" title="Remove" data-toggle=\"modal\" data-target=\"#confirm-delete\"><i class="fa fa-trash"></i></a>'
		} else {
			return '<a class="locked" href="javascript:void(0)" title="This item has been locked."><i class="fa fa-lock"></i></a>'
		}
  }
  
    var operateEvents = {
    'click .remove': function (e, value, row, index) {
		$('#confirm-delete').on('click', '.btn-ok', function(e) {
            var $modalDiv = $(e.delegateTarget);
            var id = row['ID'];
			$.post('includes/delete_column.php', { str: id }).then(function() {
				$modalDiv.modal('hide');
				location.reload();
		})});
     
        $('#confirm-delete').on('show.bs.modal', function(e) {
            var data = $(e.relatedTarget).data();
            $('.title', this).text(row['Name']);
            $('.btn-ok', this).data('recordId', data.recordId);
        });
		//var id = row['ID'];
		//$.post('includes/delete_column.php', { str: id });
    },
	'click .locked': function (e, value, row, index) {
		alert('You are not allowed to delete this item!');
    }
	}
</script>

    <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Löschen bestätigen</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    
                </div>
                <div class="modal-body">
                    <p>Willst Du <b><i class="title"></i></b> wirklich löschen?</p>
                    <p>Diese Aktion kann nicht rückgängig gemacht werden!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
                    <button type="button" class="btn btn-danger btn-ok">Löschen</button>
                </div>
            </div>
        </div>
    </div>
	   
<?php 
	include 'includes/footer.php';
?>