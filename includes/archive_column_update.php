<?php

/**
 * @package    Mitgliederdatenbank
 *
 * @copyright  Copyright (C) 2020 Gideon Mohr. All rights reserved.
 */

/**
 *		This file saves changes to a columnin archive.
 */
//Check if user is logged in
include 'template_session.php';

//validate input
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
  //get name
  $query = mysqli_query(
    $link,
    "SELECT name FROM archive_columns WHERE `id`='" . $pk . "'"
  );

  $old = $query->fetch_row()[0];
  log_column_update_archive($old, $value);

  $value = mysqli_real_escape_string($link, $value);
  $upd =
    "UPDATE archive_columns SET name = '" .
    $value .
    "' WHERE `id` = '" .
    $pk .
    "'";

  mysqli_query($link, $upd);
}
