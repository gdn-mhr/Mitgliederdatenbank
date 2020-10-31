<?php

/**
 * @package    Mitgliederdatenbank
 *
 * @copyright  Copyright (C) 2020 Gideon Mohr. All rights reserved.
 */

/**
 *	This file enables the user to show all entries.
 */
//Check if user is logged in
include 'includes/template_session.php';

//Start the HTML document with the header
include 'includes/template_header.php';
?>

<?php
//prepare statements to retrieve all columns
$sql = "SELECT id, name, access_level FROM columns";
$colresult = $link->query($sql);

$cols = [];
while ($row = mysqli_fetch_array($colresult)) {
  $cols[$row['id']] = $row['name'];
}
unset($row);

//prepare statement to retrieve data
$a = "SELECT ";

foreach ($cols as $i => $n) {
  $a .= "`" . $i . "` ,";
}

$a = rtrim($a, ",");
$a .= " FROM data";

$dataresult = $link->query($a);

//retrieve data
$data = [];
$i = 0;
while ($row = mysqli_fetch_array($dataresult, MYSQLI_NUM)) {
  $tmp = [];
  $j = 0;

  foreach ($row as $d) {
    $tmp[$j] = $d;
    $j = $j + 1;
  }

  unset($d);
  unset($j);
  $data[$i] = $tmp;
  $i = $i + 1;
}
unset($i);
unset($row);

//Title
$name = 'Alle Daten';

$post = 'includes/data_update.php';

//Declare a button to add more data
$buttons =
  '<button id="add" class="btn btn-outline-success" onclick="window.location=\'data_new.php\';"><i class="glyphicon glyphicon-remove"></i> Eintrag hinzufügen</button>';

//column to delete & archivate
$additional_c =
  '<th data-field="Delete" data-editable="false" data-formatter="operateFormatter" data-events="operateEvents" data-width="30px">Archivieren & <br>  Löschen</th>';

//additional data for last column
$additional_d = [];
$i = 0;
foreach ($data as $row) {
  $additional_d[$i] =
    "<td data-record-id=\"" .
    $row['1'] .
    "\" data-record-title=\"" .
    $row['1'] .
    "\" ></td>";
  $i = $i + 1;
}
unset($row);
?>


<script>
	
	//add icons to archivate & delete
	function operateFormatter(value, row, index) {
		return '<div> <a class="archive" href="javascript:void(0)" title="Archivieren" data-toggle=\"modal\" data-target=\"#confirm-archive\"><i class="fa fa-folder-open" style="padding-right: 10px; color: orange;"></i></a> <a class="remove" href="javascript:void(0)" title="Löschen" data-toggle=\"modal\" data-target=\"#confirm-delete\"><i class="fa fa-trash" style="color: red;" ></i></a></div>';		
	}
	
	//event handler
	var operateEvents = {
		'click .remove': function (e, value, row, index) {
			$('#confirm-delete').on('click', '.btn-ok', function(e) {
				var $modalDiv = $(e.delegateTarget);
				var id = row['ID'];
				$.post('includes/data_delete.php', { str: id }).then(function() {
					$modalDiv.modal('hide');
					location.reload();
				})});
				
				$('#confirm-delete').on('show.bs.modal', function(e) {
					var data = $(e.relatedTarget).data();
					$('.title', this).text(row['Name']);
					$('.btn-ok', this).data('recordId', data.recordId);
				});
		},
		'click .archive': function (e, value, row, index) {
			$('#confirm-archive').on('click', '.btn-ok', function(e) {
				var $modalDiv = $(e.delegateTarget);
				var id = row['ID'];
				$.post('includes/data_archive.php', { str: id }).then(function() {
					$modalDiv.modal('hide');
					location.reload();
				})});
				
				$('#confirm-archive').on('show.bs.modal', function(e) {
					var data = $(e.relatedTarget).data();
					$('.title', this).text(row['Name']);
					$('.btn-ok', this).data('recordId', data.recordId);
				});
		}
	}
</script>

<!-- Modal to confirm delete -->
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
	
	<!-- Modal to confirm archive -->
	<div class="modal fade" id="confirm-archive" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
					<h4 class="modal-title" id="myModalLabel">Archivieren?</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    
				</div>
                <div class="modal-body">
                    <p>Willst Du diesen Eintrag wirklich ins Archiv verschieben?</p>
				</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
                    <button type="button" class="btn btn-warning btn-ok">Archivieren</button>
				</div>
			</div>
		</div>
	</div>
	
	
	<?php include 'includes/template_table.php'; ?>
	
	
		