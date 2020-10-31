<?php

/**
 * @package    Mitgliederdatenbank
 *
 * @copyright  Copyright (C) 2020 Gideon Mohr. All rights reserved.
 */

/**
 *	This file enables the user to show all views in Archive.
 */
//Check if user is logged in
include 'includes/template_session.php';

//Check if open was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (!isset($_POST['open'])) {
    header("location: archive_view_list.php");
    exit();
  }

  $index = strip_tags(trim($_POST['open']));

  if (!is_numeric($index)) {
    header("location: archive_view_list.php");
    exit();
  }

  $_SESSION['selected_view'] = $index;
  header("location: archive_view_show.php");
  exit();
}

//Start the HTML document with the header
include 'includes/template_header.php';
?>	


<?php
//Get all views
$sql = "SELECT id, name, description FROM archive_views";
$colresult = $link->query($sql);
$ns = [];
$ds = [];
while ($row = mysqli_fetch_array($colresult)) {
  $ns[$row['id']] = $row['name'];
  $ds[$row['id']] = $row['description'];
}

$name = 'Auszüge im Archiv verwalten';

$open = htmlspecialchars($_SERVER["PHP_SELF"]);

$edit = 'archive_view_edit.php';

$delete = 'includes/archive_view_delete.php';

$new = 'archive_view_new.php';

include 'includes/template_view.php';

?>
