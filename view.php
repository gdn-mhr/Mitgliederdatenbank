<?php
	
	/**
		* @package    Mitgliederdatenbank
		*
		* @copyright  Copyright (C) 2020 Gideon Mohr. All rights reserved.
	*/
	
	/**
		This file enables the user to show a specific view, previously saved to SESSION.
	*/
	//Check if user is logged in
	include 'includes/session.php';
	
	//Check input
	if(!isset($_SESSION["selected_view"])){
		header("location: show_views.php");
		exit;
	}
	
	$index = $_SESSION["selected_view"];
	
	//Start the HTML document with the header
	include 'includes/header.php';
	
?>

<?php
	//This function helps to convert a condition array into a SQL statement
	function arrayToWhere ($cond) {
		if (is_array($cond[0])) {
			if ($cond[1] == 1) {
				$op = 'AND';
				} else {
				$op = 'OR';
			}
			return '(' . (arrayToWhere($cond[0])) . ' ' . $op . ' ' . (arrayToWhere($cond[2])) . ')';
			} else {
			switch ($cond[1]) {
				case 1:
				$t = ' = ';
				break;
				case 2:
				$t = ' > ';
				break;
				case 3:
				$t = ' < ';
				break;
				case 4:
				$t = ' >= ';
				break;
				case 5:
				$t = ' <= ';
				break;
				case 6:
				$t = ' <> ';
				break;
				case 7:
				$t = ' LIKE ';
				break;
				case 8:
				$t = ' NOT LIKE ';
				break;
			}
			
			return '( `' . $cond[0] . '`' . $t . "'" . $cond[2] . "' )" ;
		}
	}
	
	//First get the requested view
	$sql = "SELECT id, name, description, dat, cond FROM views WHERE id='" . $index . "';";
	$result = $link->query($sql);
	
	while($row = mysqli_fetch_array($result))
	{
		//There should only be one, however we take the last one
		$id = $row['id'];
		$name = $row['name'];
		$desc = $row['description'];
		$dat = unserialize($row['dat']);
		$conds = unserialize($row['cond']);
	}
	unset($row);
	
	//prepare statements to retrieve columns, UNION in order to get them ordered right
	$c = "SELECT id, name, access_level FROM columns WHERE id='1' UNION ";
	foreach($dat as $idx) {
		$c .= "SELECT id, name, access_level FROM columns WHERE id='".$idx."' UNION ";
	}
	$c = rtrim($c, "UNION ");
	$c .= ";"; 
	
	$colresult = $link->query($c);
	
	
	$cols = array();
	while($row = mysqli_fetch_array($colresult))
	{
		$cols[$row['id']] =  $row['name'];
	}
	unset($row);
	
	//prepare statement to retrieve data
	$a = "SELECT ";
	
	foreach ($cols as $i => $n) {
		$a .= ("`" . $i . "` ,");
	}
	
	$a = rtrim($a, ",");
	$a .= " FROM data"; 
	
	//any conditions? If yes construct them
	if ($conds != []) {
		$a = $a ." WHERE " . arrayToWhere($conds);
	}	

	$dataresult = $link->query($a);
	
	//retrieve data
	$data = array();
	$i = 0;
	while($row = mysqli_fetch_array($dataresult, MYSQLI_NUM))
	{
		$tmp = array();
		$j = 0;
		
		foreach ($row as $d) {
			$tmp[$j] = $d;
			$j = $j + 1;
			
		}
		
		unset($d);
		unset($j);
		$data[$i] = $tmp;
		$i = $i + 1;
	}
	unset($i);
	unset($row);
	
	//Title
	$name = 'Auszug: ' . $name;
	
	$post = 'includes/post.php';
	
	//Declare a button to add more data
	$buttons = '<button id="add" class="btn btn-outline-success" onclick="window.location=\'new_entry.php\';"><i class="glyphicon glyphicon-remove"></i> Eintrag hinzufügen</button>';

	include 'includes/viewer.php';
?>

