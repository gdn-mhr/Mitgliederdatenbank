<?php
	
	/**
		* @package    Mitgliederdatenbank
		*
		* @copyright  Copyright (C) 2020 Gideon Mohr. All rights reserved.
	*/
	
	/**
		This file deletes a column in archive.
	*/
	//Check if user is logged in
	include 'template_session.php';
	
	//validate input
	if (!(isset($_POST['str']))) {
		header("location: ../archive_edit_columns.php");
		exit;
	}
	
	$index = strip_tags(trim($_POST['str']));
	if (!(is_numeric($index))) {
		header("location: archive_edit_columns.php");
		exit;
	}

	//a function to delete an ID from every condition.
	function deleteColumnFromConditions($id, $conditions) {
		if (is_array($conditions[0])) {
			[$n_cond, $b] = deleteColumnFromConditions($id, $conditions[0]);
			[$n_cond2, $b2] = deleteColumnFromConditions($id, $conditions[2]);
			if ($n_cond == 'null' && $n_cond2 == 'null') {
				$c = 'null';
			} elseif ($n_cond == 'null') {
				$c = $n_cond2;
			} elseif ($n_cond2 == 'null') {
				$c = $n_cond;
			} else {
				$c = [$n_cond, $conditions[1], $n_cond2];
			}
			return [$c, ($b || $b2)];
		} else {
			if ($conditions[0] == $id) {
				return ['null', true];
			} else {
				return [$conditions, false];
			}	
		}
		
	}

	
	if(isset($link)) {
		//check if user has the proper access_level
		$val = "SELECT access_level FROM archive_columns WHERE `id`='" . $index . "'";
		$valresult = $link->query($val);
		while($row = mysqli_fetch_array($valresult))
		{
			$al = $row['access_level'];
		}
		
		if($_SESSION["access_level"] < $al){
			header("location: archive_edit_columns.php");
			exit;
		}
		
		//check if column exists in main table
		$query = mysqli_query($link, "SELECT * FROM columns WHERE `id`='" . $index . "'");

		if(mysqli_num_rows($query) > 0){
			header("location: archive_edit_columns.php");
			exit;
		}
		
		//delete column in data
		$upd = "ALTER TABLE archive_data DROP COLUMN `" . $index . "`";  
		
		mysqli_query($link, $upd);
		
		//delete in table columns
		$sql = "DELETE FROM archive_columns WHERE `id`='" . $index . "'";
		
		mysqli_query($link, $sql);
		
		//check in views
		$a = "SELECT id, description, dat, cond FROM archive_views";
		
		$viewresult = $link->query($a);
		
		while($row = mysqli_fetch_array($viewresult))
		{
			
			$i = $row['id'];
			$d = $row['description'];
			$d = str_replace("<p>Achtung: Dieser Auszug wurde aufgrund einer gelöschten Spalte geändert.</p>","",$d);
			$d .= "<p>Achtung: Dieser Auszug wurde aufgrund einer gelöschten Spalte geändert.</p>";
			$c = unserialize($row['dat']);
			
			$new = array();
			$ix = 0;
			$work = false;
			//columns
			foreach($c as $id => $s) {
				if (!($s == $index)) {
					$new[$ix] = $s;
					$ix = ($ix + 1);
					} else {
					$work = true;
				}
			}
			
			//conditions
			$conds = unserialize($row['cond']);
			if ($conds == []) {
				$n_conds = [];
			} else {
				[$n_conds, $c_work] = deleteColumnFromConditions( $index, $conds);
				if ($n_conds == "null") {$n_conds = [];}
				$work = ($work || $c_work);
			}
			
			//if anything has changed, update the views
			if ($work) {
				$b = "UPDATE archive_views SET dat = '" .  (serialize($new)) . "' WHERE id = '" .  $i . "';";
				$e = "UPDATE archive_views SET description = '" .  ($d) . "' WHERE id = '" .  $i . "';";
				$f = "UPDATE archive_views SET cond = '" . (serialize($n_conds)) . "' WHERE id = '" . $i . "';";
				mysqli_query($link, $b);
				mysqli_query($link, $e);
				mysqli_query($link, $f);
			}
		}
		
		
	}
	
?>