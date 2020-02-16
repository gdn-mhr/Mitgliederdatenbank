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

function loadData($link) {

$sql = "SELECT id, name, access_level FROM archive_columns";
$colresult = $link->query($sql);

//prepare statement to retrieve real data
$a = "SELECT ";

$cols = array();
while($row = mysqli_fetch_array($colresult))
{
	$a .= ("`" . $row['id'] . "` ,");
	$cols[$row['id']] =  $row['name'];
}
$a = rtrim($a, ",");
$a .= " FROM archive_data"; 

//echo "<p>" . $a . "</p>";

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
	data-single-select=\"true\"
	data-click-to-select=\"true\"
	data-filter-control=\"false\"
	data-show-search-clear-button=\"true\">
	<thead>
<tr>";
//print_r ($cols);
foreach ($cols as $i => $cname) {
	if ($i > 1) {
    echo "<th data-field=\"". $i ."\"   data-filter-control=\"input\" data-sortable=\"true\" data-editable=\"true\">" . $cname . "</th>";
	} else {
	echo "<th data-field=\"ID\"  data-sortable=\"true\" data-editable=\"false\">" . $cname . "</th>";
	}
}
	echo '<th data-field="Delete" data-editable="false" data-formatter="operateFormatter" data-events="operateEvents">Delete</th>';
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

echo "<td data-record-id=\"" . $row['1'] . "\" data-record-title=\"" . $row['1'] . "\" ></td>";
unset($data);
unset($i);
echo "</tr>";
}
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
  });
  
  function operateFormatter(value, row, index) {
			return '<div><a class="remove" href="javascript:void(0)" title="Remove" data-toggle=\"modal\" data-target=\"#confirm-delete\"><i class="fa fa-trash"></i></a><a class="restore" href="javascript:void(0)" title="Restore" data-toggle=\"modal\" data-target=\"#confirm-restore\"><i class="fa fa-trash-restore"></i></a></div>';		
  }
  
    var operateEvents = {
    'click .remove': function (e, value, row, index) {
		$('#confirm-delete').on('click', '.btn-ok', function(e) {
            var $modalDiv = $(e.delegateTarget);
            var id = row['ID'];
			$.post('includes/archive_delete_entry.php', { str: id }).then(function() {
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
	'click .restore': function (e, value, row, index) {
		$('#confirm-restore').on('click', '.btn-ok', function(e) {
            var $modalDiv = $(e.delegateTarget);
            var id = row['ID'];
			$.post('includes/archive_restore_entry.php', { str: id }).then(function() {
				$modalDiv.modal('hide');
				location.reload();
		})});
     
        $('#confirm-restore').on('show.bs.modal', function(e) {
            var data = $(e.relatedTarget).data();
            $('.title', this).text(row['Name']);
            $('.btn-ok', this).data('recordId', data.recordId);
        });
		//var id = row['ID'];
		//$.post('includes/delete_column.php', { str: id });
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
                    <p>Willst Du diesen Eintrag wirklich endgültig löschen?</p>
                    <p>Diese Aktion kann nicht rückgängig gemacht werden!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
                    <button type="button" class="btn btn-danger btn-ok">Löschen</button>
                </div>
            </div>
        </div>
    </div>
	
	<div class="modal fade" id="confirm-restore" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Wiederherstellung bestätigen</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    
                </div>
                <div class="modal-body">
                    <p>Willst Du diesen Eintrag wirklich aus dem Archiv wiederherstellen?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
                    <button type="button" class="btn btn-info btn-ok">Wiederherstellen</button>
                </div>
            </div>
        </div>
    </div>

    
<?php 
	include 'includes/footer.php';
?>