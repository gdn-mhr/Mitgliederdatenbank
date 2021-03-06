<?php

/**
 * @package    Mitgliederdatenbank
 *
 * @copyright  Copyright (C) 2020 Gideon Mohr. All rights reserved.
 */

/**
 *	This file deletes a view in archive.
 */
//Check if user is logged in
include 'template_session.php';

//validate input
if (!isset($_POST['delete'])) {
  header("location: ../archive_view_list.php");
  exit();
}

$index = strip_tags(trim($_POST['delete']));
if (!is_numeric($index)) {
  header("location: ../archive_view_list.php");
  exit();
}

if (isset($link)) {
  //get name
  $query = mysqli_query(
    $link,
    "SELECT name FROM archive_views WHERE `id`='" . $index . "'"
  );

  $old = $query->fetch_row()[0];
  log_view_delete_archive($old);

  $sql = "DELETE FROM archive_views WHERE `id`='" . $index . "'";

  mysqli_query($link, $sql);
}

header("location: ../archive_view_list.php");
exit();
?>
