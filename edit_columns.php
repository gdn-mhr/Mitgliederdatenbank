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
	
	//retrieve data from database
	$sql = "SELECT id, name, access_level FROM columns";
	$colresult = $link->query($sql);
	
	
	//build two arrays with the data, one points each existing columnID to its name, the other one to its access_level
	$cols = array();
	$al = array();
	while($row = mysqli_fetch_array($colresult))
	{
		$cols[$row['id']] =  $row['name'];
		$al[$row['id']] =  ($row['access_level'] <= $_SESSION['access_level'] );
	}	
	
	
	$name = 'Spalten verwalten';
	
	$post = 'includes/post_column_data.php';
	
	$action = '<form action="includes/new_column.php" method="POST" role="form" class="form-horizontal"><div class="form-group"><label for="Name">Name</label><input type="text" class="form-control" id="Name"  name="Name"></div><button type="submit" class="btn btn-outline-success">Speichern</button></form>';
	
	$delete = 'includes/delete_column.php';
?>

<?php
	include 'includes/columneditor.php';
?>
