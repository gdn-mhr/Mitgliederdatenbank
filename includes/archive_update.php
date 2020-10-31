<?php

/**
 * @package    Mitgliederdatenbank
 *
 * @copyright  Copyright (C) 2020 Gideon Mohr. All rights reserved.
 */

/**
 *	This file saves changes to an entry in archive.
 */
//Check if user is logged in
include 'template_session.php';

//validate input
if (!isset($_POST['name'])) {
  exit();
}

$name = strip_tags(trim($_POST['name']));
if (!is_numeric($name)) {
  exit();
}

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

    $display = "";
    foreach (NAME_SCHEME as $key => $id) {
      $query = mysqli_query(
        $link,
        "SELECT name FROM archive_columns WHERE `id`='" . $id . "'"
        );
      
        if (mysqli_num_rows($query) > 0) {
        $queryName = mysqli_query(
          $link,
          "SELECT `" . $id . "` FROM archive_data WHERE `1`='" . $pk . "'"
          );
          if (mysqli_num_rows($queryName) > 0) {
          $display = ($display == "" ? $queryName->fetch_row()[0] : ($display . " " . $queryName->fetch_row()[0]));
          }
        }
    }
    $display = $pk . ": " . $display;
  log_changed_archive($display);
  $value = mysqli_real_escape_string($link, $value);
  $upd =
    "UPDATE archive_data SET `" .
    $name .
    "` = '" .
    $value .
    "' WHERE `1` = '" .
    $pk .
    "'";

  mysqli_query($link, $upd);
}

?>
