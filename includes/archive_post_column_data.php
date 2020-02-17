<?php
	
	/**
		* @package    Mitgliederdatenbank
		*
		* @copyright  Copyright (C) 2020 Gideon Mohr. All rights reserved.
	*/
	
	/**
		This file saves changes to a columnin archive.
	*/
	//Check if user is logged in
	include 'session.php';
	
	//validate input
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
		$upd = "UPDATE archive_columns SET name = '" .  $value . "' WHERE `id` = '" .  $pk . "'";   
		
		mysqli_query($link, $upd);
		
	}
	
?>