<?php
	
	/**
		* @package    Mitgliederdatenbank
		*
		* @copyright  Copyright (C) 2020 Gideon Mohr. All rights reserved.
	*/
	
	/**
		This file deletes a view.
	*/
	//Check if user is logged in
	include 'session.php';
	
	//validate input
	if (!(isset($_POST['delete']))) {
		header("location: ../show_views.php");
		exit;
	}
	
	$index = strip_tags(trim($_POST['delete']));
	if (!(is_numeric($index))) {
		header("location: show_views.php");
		exit;
	}
	
	
	if(isset($link)) {
		
		$sql = "DELETE FROM views WHERE `id`='" . $index . "'" ;
		
		mysqli_query($link, $sql);
		
	}
	
	header("location: ../show_views.php");
    exit;
?>