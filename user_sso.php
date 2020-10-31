<?php
/**
 * @package    Mitgliederdatenbank
 *
 * @copyright  Copyright (C) 2020 Gideon Mohr. All rights reserved.
 */

/**
 *	This file creates a SSO session.
 */

// Initialize the session
session_start();

if (
  isset($_SESSION['LAST_ACTIVITY']) &&
  time() - $_SESSION['LAST_ACTIVITY'] > 1800
) {
  // last request was more than 30 minutes ago
  session_unset(); // unset $_SESSION variable for the run-time
  session_destroy(); // destroy session data in storage
  header("location: user_login.php");
  exit();
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
  header("location: index.php");
  exit();
}

// Include config file
require_once "includes/template_config.php";

require_once $SIMPLESAMLPHP_PATH . '/lib/_autoload.php';

$ssp = new \SimpleSAML\Auth\Simple($SAML_AUTHSOURCE);

$ssp->requireAuth();

if ($ssp->isAuthenticated()) {
  $attributes = $ssp->getAttributes();
  if (!in_array("Verwaltung", $attributes['groups'])) {
    session_destroy();
    $ssp->logout($LOGIN_URL);
  }

  $session = \SimpleSAML\Session::getSessionFromRequest();
  $session->cleanup();
  $username = $attributes['user'][0];
  $_SESSION["loggedin"] = true;
  $_SESSION["sso"] = true;
  $_SESSION["id"] = password_hash($username, PASSWORD_DEFAULT);
  $_SESSION["user"] = -1;
  $_SESSION["username"] = $username;
  if (!in_array("Admin", $attributes['groups'])) {
    $_SESSION["access_level"] = 2;
  } else {
    $_SESSION["access_level"] = 5;
  }
  // Redirect user to welcome page
  header("location: index.php");
}

?>
