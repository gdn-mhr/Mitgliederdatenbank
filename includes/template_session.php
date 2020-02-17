<?php
	
	/**
		* @package    Mitgliederdatenbank
		*
		* @copyright  Copyright (C) 2020 Gideon Mohr. All rights reserved.
	*/
	
	/**
		Check if the user is signed in and has the access level to view the database (<1).
	*/
	
	// Initialize the session
	session_start();
	
	if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
		// last request was more than 30 minutes ago
		session_unset();     // unset $_SESSION variable for the run-time 
		session_destroy();   // destroy session data in storage
		header("location: user_login.php");
		exit;
	}
	
	$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
	
	// Check if the user is logged in, if not then redirect him to login page
	if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
		header("location: user_login.php");
		exit;
	}
	
	// Check if the user has valid access_level
	if($_SESSION["access_level"]<=1){
		header("location: index.php");
		exit;
	}
	
	// Include config file
	require_once "template_config.php";
?>