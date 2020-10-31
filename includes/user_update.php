<?php

/**
 * @package    Mitgliederdatenbank
 *
 * @copyright  Copyright (C) 2020 Gideon Mohr. All rights reserved.
 */

/**
 *	This file updates a username or a access_level.
 */
//Check if user is logged in
include 'template_session.php';

// Check if the user has valid access_level
if ($_SESSION["access_level"] < 5) {
  header("location: index.php");
  exit();
}

//validate input
if (!isset($_POST['name'])) {
  exit();
}

$name = strip_tags(trim($_POST['name']));

if (!isset($_POST['value'])) {
  exit();
}

$value = strip_tags(trim($_POST['value']));

if (!isset($_POST['pk'])) {
  exit();
}

$pk = strip_tags(trim($_POST['pk']));
if (!is_numeric($pk)) {
  exit();
}

if (isset($link)) {
  $name = mysqli_real_escape_string($link, $name);
  $value = mysqli_real_escape_string($link, $value);
  $upd =
    "UPDATE users SET " .
    $name .
    " = '" .
    $value .
    "' WHERE `id` = '" .
    $pk .
    "'";

  mysqli_query($link, $upd);
}

?>
