<?php
	
	/**
		* @package    Mitgliederdatenbank
		*
		* @copyright  Copyright (C) 2020 Gideon Mohr. All rights reserved.
	*/
	
	/**
		This file creates a new column.
	*/
	//Check if user is logged in
	include 'session.php';
	
	//validate input
	if (!(isset($_POST['Name']))) {
		header("location: ../edit_columns.php");
		exit;
	}
	
	$name = strip_tags(trim($_POST['Name']));
	
	
	if(isset($link)) {
		$name = mysqli_real_escape_string($link, $name);
		
		//insert row
		$upd = "INSERT INTO `columns` (`id`, `name`, `access_level`) VALUES (NULL, '" . $name . "', '0');";   
		
		mysqli_query($link, $upd);
		
		
		
		//retrieve especially ID
		$sql = "SELECT id, name, access_level FROM columns WHERE `name`='" . $name . "'";
		
		$colresult = $link->query($sql);
		
		while($row = mysqli_fetch_array($colresult))
		{
			$id = $row['id'];
		}
		//Add column in data
		$a = "ALTER TABLE data ADD `" . $id . "` TEXT;"; 
		
		mysqli_query($link, $a);
		
		//Insert into columns in archive
		$upd_a = "INSERT INTO `archive_columns` (`id`, `name`, `access_level`) VALUES (" . $id . ", '" . $name . "', '0');";   
		
		mysqli_query($link, $upd_a);
		
		//Add column in archive
		$a_a = "ALTER TABLE archive_data ADD `" . $id . "` TEXT;"; 
		
		mysqli_query($link, $a_a);
		
		header("Location: ../edit_columns.php");
		exit;
		
		
	}
	
?>