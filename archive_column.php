<?php
	
	/**
		* @package    Mitgliederdatenbank
		*
		* @copyright  Copyright (C) 2020 Gideon Mohr. All rights reserved.
	*/
	
	/**
		This file enables the user to show all columns, edit their names, delete them or create a new column in archive.
	*/
	//Check if user is logged in
	include 'includes/template_session.php';
	
	//Start the HTML document with the header
	include 'includes/template_header.php';
	
?>

<?php
	
	$lock_cols = array();
	
	$a = "SELECT id FROM columns";
	$mcolresult = $link->query($a);

	while($row = mysqli_fetch_array($mcolresult)) {
		$lock_cols[] =  $row['id'];
	}
	
	//retrieve data from database
	$sql = "SELECT id, name, access_level FROM archive_columns";
	$colresult = $link->query($sql);
	
	
	//build two arrays with the data, one points each existing columnID to its name, the other one to its access_level
	$cols = array();
	$al = array();
	while($row = mysqli_fetch_array($colresult))
	{
		$cols[$row['id']] =  $row['name'];
		$al[$row['id']] =  (($row['access_level'] <= $_SESSION['access_level']) && !(in_array ( $row['id'], $GLOBALS['lock_cols'] )));
	}	

	$name = 'Spalten im Archiv verwalten';
	
	$post = 'includes/archive_column_update.php';
	
	$action = '<p>Neue Spalten können im Archiv nicht erstellt werden.</p>';
	
	$delete = 'includes/archive_column_delete.php';
?>

<?php
	include 'includes/template_column.php';
?>