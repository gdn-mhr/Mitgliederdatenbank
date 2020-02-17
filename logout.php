<?php
	
	/**
		* @package    Mitgliederdatenbank
		*
		* @copyright  Copyright (C) 2020 Gideon Mohr. All rights reserved.
	*/
	
	/**
		This file destroys a session.
	*/
	
	// Initialize the session
	session_start();
	
	// Unset all of the session variables
	$_SESSION = array();
	
	// Destroy the session.
	session_destroy();
	
	// Redirect to login page
	header("location: login.php");
	exit;
?>