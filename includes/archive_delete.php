<?php

/**
 * @package    Mitgliederdatenbank
 *
 * @copyright  Copyright (C) 2020 Gideon Mohr. All rights reserved.
 */

/**
 *	This file deletes an entry in archive.
 */
//Check if user is logged in
include 'template_session.php';

//validate input
if (!isset($_POST['str'])) {
  header("location: ../archive_show.php");
  exit();
}

$index = strip_tags(trim($_POST['str']));
if (!is_numeric($index)) {
  header("location: ../archive_show.php");
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
          "SELECT `" . $id . "` FROM archive_data WHERE `1`='" . $index . "'"
          );
          if (mysqli_num_rows($queryName) > 0) {
          $display = ($display == "" ? $queryName->fetch_row()[0] : ($display . " " . $queryName->fetch_row()[0]));
          }
        }
    }
    $display = $index . ": " . $display;
    log_delete_archive($display);

  $sql = "DELETE FROM archive_data WHERE `1`='" . $index . "'";
  mysqli_query($link, $sql);
}

?>
