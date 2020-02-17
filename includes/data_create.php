<?php
	
	/**
		* @package    Mitgliederdatenbank
		*
		* @copyright  Copyright (C) 2020 Gideon Mohr. All rights reserved.
	*/
	
	/**
		This file creates a new entry.
	*/
	//Check if user is logged in
	include 'template_session.php';
	
	if(isset($link)) {
		
		$upd = "INSERT INTO data VALUES (NULL";
		foreach ($_POST as $data) {
			$data = mysqli_real_escape_string($link, strip_tags( trim ($data)));
			$upd .=  " , '" . $data . "'";
			
		}
		
		$upd .= ");";
		mysqli_query($link, $upd);
		
		header("location: ../data_show.php");
		exit;	
	}
	
?>