<?php
	
	/**
		* @package    Mitgliederdatenbank
		*
		* @copyright  Copyright (C) 2020 Gideon Mohr. All rights reserved.
	*/
	
	/**
		This file enables the user to show all views.
	*/
	//Check if user is logged in
	include 'includes/session.php';
	
	//Check if open was submitted
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		
		if (!(isset($_POST['open']))) {
			header("location: show_views.php");
			exit;
		}
		
		$index = strip_tags(trim($_POST['open']));
		
		if (!(is_numeric($index))) {
			header("location: show_views.php");
			exit;
		}
		
		$_SESSION['selected_view'] = $index;
		header("location: view.php");
		exit;	
		
	}
	
	//Start the HTML document with the header
	include 'includes/header.php';
	
?>	


<?php
	//Get all views
	$sql = "SELECT id, name, description FROM views";
	$colresult = $link->query($sql);
	
	$ns = array();
	$ds = array();
	while($row = mysqli_fetch_array($colresult))
	{
		$ns[$row['id']] =  $row['name'];
		$ds[$row['id']] =  $row['description'];
	}
	
	$name = 'Auszüge verwalten';
	
	$open = htmlspecialchars($_SERVER["PHP_SELF"]);
	
	$edit = 'edit_view.php';
	
	$delete = 'includes/delete_view.php';
	
	$new = 'new_view.php';
	
	include 'includes/view_template.php';
?>