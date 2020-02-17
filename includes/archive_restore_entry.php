<?php
	
	/**
		* @package    Mitgliederdatenbank
		*
		* @copyright  Copyright (C) 2020 Gideon Mohr. All rights reserved.
	*/
	
	/**
		This file restores an entry.
	*/
	//Check if user is logged in
	include 'session.php';
	
	//validate input
	if (!(isset($_POST['str']))) {
		header("location: ../show_entries.php");
		exit;
	}
	
	$index = strip_tags(trim($_POST['str']));
	if (!(is_numeric($index))) {
		header("location: show_entries.php");
		exit;
	}
	
	if(isset($link)) {
		
		$a = "SELECT * FROM `archive_data` WHERE `1`='" . $index . "'"	;
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
			$c = $c . "'" . $dat[$val] . "' ,";
		}
		unset($val);
		$b = rtrim($b, ",");
		$c = rtrim($c, ",");
		
		
		$ins = "INSERT INTO data " . $b . ") VALUES " . $c . ");";	
		
		mysqli_query($link, $ins);
		
		$sql = "DELETE FROM archive_data WHERE `1`='" . $index . "'" ;
		
		mysqli_query($link, $sql);
		
	}
	
?>