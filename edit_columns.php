<?php
	
	/**
		* @package    Mitgliederdatenbank
		*
		* @copyright  Copyright (C) 2020 Gideon Mohr. All rights reserved.
	*/
	
	/**
		This file enables the user to show all columns, edit their names, delete them or create a new column.
	*/
	//Check if user is logged in
	include 'includes/session.php';
	
	//Start the HTML document with the header
	include 'includes/header.php';
	
?>

<?php
	
	//Loading the Columns given a database connection and print them in a table
	function loadColumns() {
		
		//retrieve data from database
		global $link;
		$sql = "SELECT id, name, access_level FROM columns";
		$colresult = $link->query($sql);
		
		
		//build two arrays with the data, one points each existing columnID to its name, the other one to its access_level
		$cols = array();
		$al = array();
		while($row = mysqli_fetch_array($colresult))
		{
			$cols[$row['id']] =  $row['name'];
			$al[$row['id']] =  $row['access_level'];
		}
		
		
		//print the table options, espacially the POST php
		echo "<div><table  
		data-locale=\"de-DE\"
		data-toggle=\"table\"
		data-search=\"true\"
		data-editable=\"true\"
		data-editable-url=\"includes/post_column_data.php\"
		data-search=\"true\"
		data-pagination=\"true\"
		data-id-field=\"ID\"
		data-page-list=\"[10, 25, 50, 100, all]\">";
		
		//print the table header
		echo "<thead>";
		echo "<tr>";
		echo "<th data-field=\"ID\" data-editable=\"false\" data-width=\"20px\">ID</th>";
		echo "<th data-field=\"Name\" data-editable=\"true\" >Name</th>";
		echo '<th data-field="Delete" data-editable="false" data-width="20px" data-formatter="operateFormatter" data-events="operateEvents" >Löschen</th>';
		echo "</tr>";
		echo "</thead>";		
		
		//Fill the table with the data from the database while putting access_level into the last column
		foreach ($cols as $i => $cname) {
			echo "<tr>";
			echo "<td>" . $i . "</td>";
			echo "<td>" . $cname . "</td>";
			echo "<td data-record-id=\"" . $i . "\" data-record-title=\"" . $cname . "\" >" . $al[$i] . "</td>";
			echo "</tr>";
		}
		unset($cname);
		unset($i);
		echo "</table> </div>";		
	}
	
	
	
?>

<div style="max-width: 850px; display: block; margin-left: auto; margin-right: auto;">
	<h2>Spalten verwalten</h2>
	
	<?php 
		//load the table
		loadColumns();
	?>
	
	<hr>
	
	<!-- Enable the user to add new columns -->
	<div style="max-width: 400px; display: block; margin-left: auto; margin-right: auto;">
		<h2>Neue Spalte hinzufügen</h2>
		<form action="includes/new_column.php" method="POST" role="form" class="form-horizontal">
			<div class="form-group">
				<label for="Name">Name</label>
				<input type="text" class="form-control" id="Name"  name="Name">
			</div>
			<button type="submit" class="btn btn-outline-success">Speichern</button>
		</form>
	</div> 
	
</div> 

<?php 
	include 'includes/footer.php';
?>

<!-- This modal is hidden by default and prompts if the column really should be deleted -->
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

<script>
	//enable inline editing
	$.fn.editable.defaults.mode = 'inline';
	
	//convert the table into a Bootstrap table
	$(function() {
		$('#table').bootstrapTable();
	})
	
	//transform access_level into a delete or a locked icon
	function operateFormatter(value, row, index) {
		if (<?php echo $_SESSION["access_level"] ?> >= value) {
			return '<div style="display: table; margin-left: auto; margin-right: auto;" > <a style="display: block; margin-left: auto; margin-right: auto;" class="remove" href="javascript:void(0)" title="Löschen" data-toggle=\"modal\" data-target=\"#confirm-delete\"><i class="fa fa-trash" style="color: red;" ></i></a> </div>'
			} else {
			return '<div style="display: table; margin-left: auto; margin-right: auto;" > <a class="locked" href="javascript:void(0)" title="Diese Spalte ist gesperrt und kann derzeit nicht gelöscht werden."><i class="fa fa-lock" style="color: orange;" ></i></a> </div>'
		}
	}
	
	//click events
	var operateEvents = {
		'click .remove': function (e, value, row, index) {
			//when clicking delete first show a modal, then delete the column
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
		},
		'click .locked': function (e, value, row, index) {
			//do nothing when the item is locked
		}
	}
</script>