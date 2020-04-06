<?php
	
	/**
		* @package    Mitgliederdatenbank
		*
		* @copyright  Copyright (C) 2020 Gideon Mohr. All rights reserved.
	*/
	
	/**
		This file archives an entry.
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
		
		$a = "SELECT * FROM `data` WHERE `1`='" . $index . "'"	;
		$dates = $link->query($a);
		
		$dat = array();
		
		while($row = mysqli_fetch_array($dates))
		{
			foreach ($row as $id => $val) {
				$dat[$id] = $val;
			}
			unset($id);
			unset($val);
			
		}	
		
		
		$a = "SELECT id FROM columns";
		$mcolresult = $link->query($a);
		
		
		
		
		while($row = mysqli_fetch_array($mcolresult))
		{
			
			$lock_cols[] =  $row['id'];
		}
		
		$b = "(";
		$c = "(";
		foreach ($lock_cols as $val) {
			
			$b = $b . "`" . $val . "` ,";
			$c = $c . "'" . $dat[($val- $OFFSET_DATABASE)] . "' ,";
		}
		unset($val);
		$b = rtrim($b, ",");
		$c = rtrim($c, ",");
		
		
		$ins = "INSERT INTO archive_data " . $b . ") VALUES " . $c . ");";	
		error_log($ins);
		mysqli_query($link, $ins);
		
		$sql = "DELETE FROM data WHERE `1`='" . $index . "'" ;
		
		mysqli_query($link, $sql);
		
	}
	
?>