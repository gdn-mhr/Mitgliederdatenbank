<?php
	
	/**
		* @package    Mitgliederdatenbank
		*
		* @copyright  Copyright (C) 2020 Gideon Mohr. All rights reserved.
	*/
	
	/**
		This file saves changes to an entry in archive.
	*/
	//Check if user is logged in
	include 'session.php';
	
	//validate input
	if (!(isset($_POST['name']))) {
		exit;
	}
	
	$name = strip_tags(trim($_POST['name']));
	if (!(is_numeric($name))) {
		exit;
	}
	
	
	if (!(isset($_POST['value']))) {
		exit;
	}
	
	$value = strip_tags(trim($_POST['value']));
	
	
	if (!(isset($_POST['pk']))) {
		exit;
	}
	
	$pk = strip_tags(trim($_POST['pk']));
	if (!(is_numeric($pk))) {
		exit;
	}
	
	
	
	if(isset($link)) {
		$value = mysqli_real_escape_string($link, $value);
		$upd = "UPDATE archive_data SET `" . $name . "` = '" .  $value . "' WHERE `1` = '" .  $pk . "'";   
		
		mysqli_query($link, $upd);
		
	}
	
?>