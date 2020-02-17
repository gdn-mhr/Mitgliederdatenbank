<?php
	
	/**
		* @package    Mitgliederdatenbank
		*
		* @copyright  Copyright (C) 2020 Gideon Mohr. All rights reserved.
	*/
	
	/**
		This file deletes an entry.
	*/
	//Check if user is logged in
	include 'template_session.php';
	
	//validate input
	if (!(isset($_POST['str']))) {
		header("location: ../data_show.php");
		exit;
	}
	
	$index = strip_tags(trim($_POST['str']));
	if (!(is_numeric($index))) {
		header("location: ../data_show.php");
		exit;
	}
	
	if(isset($link)) {
		
		$sql = "DELETE FROM data WHERE `1`='" . $index . "'" ;
		mysqli_query($link, $sql);
		
	}
	
?>