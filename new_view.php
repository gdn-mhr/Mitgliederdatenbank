<?php
	
	/**
		* @package    Mitgliederdatenbank
		*
		* @copyright  Copyright (C) 2020 Gideon Mohr. All rights reserved.
	*/
	
	/**
		This file enables the user to create a new view.
	*/
	//Check if user is logged in
	include 'includes/session.php';
	
	//Start the HTML document with the header
	include 'includes/header.php';
	
?>

<?php	
	
	//Now we need the column names
	$sql = "SELECT id, name FROM columns";
	$colresult = $link->query($sql);
	
	//We will save the column name in an array: columnID -> Name
	$cols = array();
	while($row = mysqli_fetch_array($colresult))
	{
		$cols[$row['id']] =  $row['name'];
	}
	
	//Build an string which contains all the columns as select-options. The value is their ID
	$opt = "";
	foreach($cols as $i => $n) {
		if ($i > 1) {
			$opt .= "<option value='" . $i . "'>" . $n . "</option>";
		}
	}
	unset($i);
	unset($n);
	
	//Now we store select options of the operators (=,>,<,>=,<=,<>,LIKE,NOT LIKE)
	$ops = "<option value='1'>=</option><option value='2'>></option><option value='3'><</option><option value='4'>>=</option><option value='5'><=</option><option value='6'><></option><option value='7'>LIKE</option><option value='8'>NOT LIKE</option>";
	
	//And finally also AND and OR
	$opo = "<option value='1'>AND</option><option value='2'>OR</option>";
	
	//Column count will be initalized with 1
	$in = 1;
	
	function Cols() {
		global $opt;
		echo '<tr id="row0"><td><fieldset><label>Spalte auswählen</label><select name="col[]" class="form-control" >' . $opt . '</select></fieldset></td><td></td></tr>';  
	}
	
	function Conds() {
		//if we do have conditions, call the function defined above to print them to the table
		global $conds;
		echo '<tr id="row_0"><td></td><td></td><td><button type="button" name="0" id="0" class="btn btn-outline-info btn_cond_0" style="display: block; margin-left: auto; margin-right: auto;" >Bedingungen hinzufügen</button></td><td></td><td></td></tr>';
	}
	
	function Poster() {
		echo 'includes/save_view.php';
	}
	
	function Name() {
		echo '';
	}
	
	function Description() {
		echo '';
	}
	
	include 'includes/vieweditor.php';
	
?>