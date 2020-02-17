<?php
	
	/**
		* @package    Mitgliederdatenbank
		*
		* @copyright  Copyright (C) 2020 Gideon Mohr. All rights reserved.
	*/
	
	/**
		This file enables the user to edit an existing view in archive. Therefor the new_view.php is being prefilled.
	*/
	//Check if user is logged in
	include 'includes/session.php';
	
	//Validate input
	if (!(isset($_POST['edit']))) {
		header("location: archive_show_views.php");
		exit;
	}
	$index = strip_tags(trim($_POST['edit']));
	if (!(is_numeric($index))) {
		header("location: archive_show_views.php");
		exit;
	}
	
	//Start the HTML document with the header
	include 'includes/header.php';
	
?>

<?php
	
	//As the index was posted by the user, we need to escape it (even though we already know it is numeric)
	$index = mysqli_real_escape_string($link, $index);
	//Prepare to get the view's details from the database
	$a = "SELECT id, name, description, dat, cond FROM archive_views WHERE id='". $index . "';";
	$viewresult = $link->query($a);
	while($row = mysqli_fetch_array($viewresult))
	{
		//There should only be one result, but in any case we will take the last one
		$id = $row['id'];
		$name =  $row['name'];
		$des =  $row['description'];
		$dat = unserialize($row['dat']);
		$conds = unserialize($row['cond']);
	}
	
	
	//Now we need the column names
	$sql = "SELECT id, name FROM archive_columns";
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
	
	//Now we create an Array with one preselected item, one for each column
	$opts = array();
	foreach($cols as $x => $in) {
		if ($x > 1) {
			$opts[$x] = "";
			foreach($cols as $i => $n) {
				if ($i > 1) {
					if ($i == $x) {
						$opts[$x] .= "<option value='" . $i . "' selected>" . $n . "</option>";
						} else {
						$opts[$x] .= "<option value='" . $i . "'>" . $n . "</option>";
					}
				}
				
			}
		}
		unset($i);
		unset($n);
	}
	$in = $x;
	unset($x);
	
	//Now we store select options of the operators (=,>,<,>=,<=,<>,LIKE,NOT LIKE)
	$ops = "<option value='1'>=</option><option value='2'>></option><option value='3'><</option><option value='4'>>=</option><option value='5'><=</option><option value='6'><></option><option value='7'>LIKE</option><option value='8'>NOT LIKE</option>";
	
	//Now we store preselected options of the operators (=,>,<,>=,<=,<>,LIKE,NOT LIKE)
	$opss = array();
	$opss[1] = "<option value='1' selected>=</option><option value='2'>></option><option value='3'><</option><option value='4'>>=</option><option value='5'><=</option><option value='6'><></option><option value='7'>LIKE</option><option value='8'>NOT LIKE</option>";
	$opss[2] = "<option value='1'>=</option><option value='2' selected>></option><option value='3'><</option><option value='4'>>=</option><option value='5'><=</option><option value='6'><></option><option value='7'>LIKE</option><option value='8'>NOT LIKE</option>";
	$opss[3] = "<option value='1'>=</option><option value='2'>></option><option value='3' selected><</option><option value='4'>>=</option><option value='5'><=</option><option value='6'><></option><option value='7'>LIKE</option><option value='8'>NOT LIKE</option>";
	$opss[4] = "<option value='1'>=</option><option value='2'>></option><option value='3'><</option><option value='4' selected>>=</option><option value='5'><=</option><option value='6'><></option><option value='7'>LIKE</option><option value='8'>NOT LIKE</option>";
	$opss[5] = "<option value='1'>=</option><option value='2'>></option><option value='3'><</option><option value='4'>>=</option><option value='5' selected><=</option><option value='6'><></option><option value='7'>LIKE</option><option value='8'>NOT LIKE</option>";
	$opss[6] = "<option value='1'>=</option><option value='2'>></option><option value='3'><</option><option value='4'>>=</option><option value='5'><=</option><option value='6' selected><></option><option value='7'>LIKE</option><option value='8'>NOT LIKE</option>";
	$opss[7] = "<option value='1'>=</option><option value='2'>></option><option value='3'><</option><option value='4'>>=</option><option value='5'><=</option><option value='6'><></option><option value='7' selected>LIKE</option><option value='8'>NOT LIKE</option>";
	$opss[8] = "<option value='1'>=</option><option value='2'>></option><option value='3'><</option><option value='4'>>=</option><option value='5'><=</option><option value='6'><></option><option value='7'>LIKE</option><option value='8' selected>NOT LIKE</option>";
	
	//And finally also AND and OR
	$opo = "<option value='1'>AND</option><option value='2'>OR</option>";
	
	//And the preselected ones
	$opos = array();
	$opos[1] = "<option value='1' selected>AND</option><option value='2'>OR</option>";
	$opos[2] = "<option value='1'>AND</option><option value='2' selected>OR</option>";
	
	
	//here we reconstruct the former saved conditions. When the first Element (and therefor also the last one is an array, we need to append -1 and -2 to the index and check the subcondition. This function should be called with '1'
	function reconstructConditions($cond, $idx) {
		global $opts, $opos, $opss;
		if (is_array($cond[0])) {
			echo '<tr id="lpar-' . $idx . '"><td>(</td><td></td><td></td><td></td><td></td></tr>';
			reconstructConditions($cond[0], $idx . '-1');
			echo '<tr id="op-' . $idx . '"><td></td><td></td><td><fieldset><label>Operator</label><select id="c_o-' . $idx . '" name="c_o-' . $idx . '" class="form-control" >' .  $opos[$cond[1]] . '</select></fieldset></td><td></td><td></td></tr>';
			reconstructConditions($cond[2], $idx . '-2');
			echo '<tr id="rpar-' . $idx . '"><td></td><td></td><td></td><td></td><td>)</td></tr>';
			} else {
			echo '<tr id="lpar-' . $idx . '"><td>(</td><td></td><td></td><td></td><td></td></tr>';
			echo '<tr id="cond-' . $idx . '"><td></td><td><fieldset><label>Spalte auswählen</label><select id="c_c-' . $idx . '" name="c_c-' . $idx . '" class="form-control" >' . $opts[$cond[0]] . '</select></fieldset></td><td><fieldset><label>Operator auswählen</label><select id="c_t-' . $idx . '" name="c_t-' . $idx . '" class="form-control" >' . $opss[$cond[1]] . '</select></fieldset></td><td><fieldset><label>Bedingung</label><input type="text" id="c_i-' . $idx . '" name="c_i-' . $idx . '" class="form-control" value="' . $cond[2] . '" /></fieldset></td><td><button type="button" name="remove" id="r-' . $idx . '" class="btn btn-danger btn_remove btn_remove_cond" style="display: block; margin-left: auto; margin-right: auto;">X</button></td></tr>';
			echo '<tr id="b-' . $idx . '"><td></td><td></td><td><button type="button" name="add_cond" id="' . $idx . '" class="btn btn-outline-info btn_cond" style="display: block; margin-left: auto; margin-right: auto;">Weitere Bedingung</button></td><td></td><td></td></tr>';
			echo '<tr id="rpar-' . $idx . '"><td></td><td></td><td></td><td></td><td>)</td></tr>';
		}
		
	}
	
	function Cols() {
		//reconstruct the previously selected columns, except the 1st one, each can be removed
		global $dat, $opts;
						foreach ($dat as $in => $x) {
							echo '<tr id="row' . $in . '">';  
							echo '<td><fieldset>';
							echo '<label>Spalte auswählen</label>';
							echo ' <select name="col[]" class="form-control" >';
							echo  $opts[$x];
							echo '</select>';
							echo '</fieldset>';
							echo '</td>';
							if ($in == 0) 
							{
								echo '<td></td>';  
							} 
							else 
							{
								echo '<td><button type="button" name="remove" id="'. $in .'" class="btn btn-danger btn_remove">X</button></td>';  
							}
							echo '</tr>';  
						}
	}
	
	function Conds() {
		//if we do have conditions, call the function defined above to print them to the table
		global $conds;
						if ($conds == []) {
							echo '<tr id="row_0"><td></td><td></td><td><button type="button" name="0" id="0" class="btn btn-outline-info btn_cond_0" style="display: block; margin-left: auto; margin-right: auto;" >Bedingungen hinzufügen</button></td><td></td><td></td></tr>';
							} else {								
							
							reconstructConditions($conds, '1');
						}
	}
	
	function Poster() {
		echo 'includes/archive_alter_view.php';
	}
	
	function Name() {
		global $name;
		echo $name;
	}
	
	function Description() {
		global $des;
		echo $des;
	}
	
	include 'includes/vieweditor.php';
	
?>