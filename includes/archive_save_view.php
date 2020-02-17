<?php
	
	/**
		* @package    Mitgliederdatenbank
		*
		* @copyright  Copyright (C) 2020 Gideon Mohr. All rights reserved.
	*/
	
	/**
		This file creates a view.
	*/
	//Check if user is logged in
	include 'session.php';
	
	//validate input
	if (!(isset($_POST['col'])) || !(isset($_POST['has_cond']))) {
		header("location: ../archive_show_views.php");
		exit;
	}
	
	
	
	//a function which packs conditions into an array
	function packConds($str) {
		global $link;
		if(isset($_POST['c_c-' . $str])) {
			return [(int) mysqli_real_escape_string($link, strip_tags( trim($_POST['c_c-' . $str]))), (int) mysqli_real_escape_string($link, strip_tags( trim ($_POST['c_t-' . $str]))), mysqli_real_escape_string($link, strip_tags( trim ($_POST['c_i-' . $str])))];
			} else {
			return [(packConds($str . '-1')), (int) mysqli_real_escape_string($link, strip_tags( trim ($_POST['c_o-' . $str]))), (packConds($str . '-2'))];
		}
	}
	
	
	//remove any suspicious data and update the view
	if(isset($link)) {
		$bez = mysqli_real_escape_string($link, strip_tags( trim($_POST['name'])));
		$des = mysqli_real_escape_string($link, strip_tags( trim($_POST['desc']), '<p><b><i><u><strike><li><ol><ul><br>'));
		$cols = array();
		
		foreach ($_POST['col'] as $i => $v) {
			$cols[(int) mysqli_real_escape_string($link, strip_tags( trim($i)))] = (int) mysqli_real_escape_string($link, strip_tags( trim($v)));
		}
		
		if (strip_tags( trim($_POST['has_cond'])) == 'true') {
			$conds = packConds('1');
			} else {
			$conds = [];
		}
		
		$upd = "INSERT INTO archive_views VALUES (NULL, '" . $bez . "', '" . $des . "', '" . serialize($cols) . "', '" . serialize($conds) . "');";
		
		
		mysqli_query($link, $upd);
		
		header("location: ../archive_show_views.php");
		exit;	
	}
	
?>