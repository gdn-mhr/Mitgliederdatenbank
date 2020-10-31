<?php

/**
 * @package    Mitgliederdatenbank
 *
 * @copyright  Copyright (C) 2020 Gideon Mohr. All rights reserved.
 */

/**
 *	This file destroys a session.
 */
//Initialize the session
session_start();

if (isset($_SESSION["sso"]) && $_SESSION["sso"] == 1) {
  // Unset all of the session variables
  $_SESSION = [];
  // Destroy the session.
  session_destroy();
  
  // Include config file
  require_once "includes/template_config.php";

  require_once $SIMPLESAMLPHP_PATH . '/lib/_autoload.php';

  $ssp = new \SimpleSAML\Auth\Simple($SAML_AUTHSOURCE);

  $ssp->logout($LOGIN_URL);
}

// Unset all of the session variables
$_SESSION = [];

// Destroy the session.
session_destroy();

// Redirect to login page
header("location: user_login.php");
exit();
?>
