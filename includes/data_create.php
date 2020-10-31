<?php

/**
 * @package    Mitgliederdatenbank
 *
 * @copyright  Copyright (C) 2020 Gideon Mohr. All rights reserved.
 */

/**
 *	This file creates a new entry.
 */
//Check if user is logged in
include 'template_session.php';

if (isset($link)) {
  $upd = "INSERT INTO data VALUES (NULL";
  foreach ($_POST as $data) {
    $data = mysqli_real_escape_string($link, strip_tags(trim($data)));
    $upd .= " , '" . $data . "'";
  }

  $upd .= ");";
  mysqli_query($link, $upd);

  //get id
  $query = mysqli_query($link, "SELECT `1` FROM data ORDER BY `1` DESC LIMIT 1");

  $value = $query->fetch_row()[0];


  $display = "";
	foreach (NAME_SCHEME as $key => $id) {
		$query2 = mysqli_query(
			$link,
			"SELECT name FROM columns WHERE `id`='" . $id . "'"
		  );
		
		  if (mysqli_num_rows($query2) > 0) {
			$queryName = mysqli_query(
				$link,
				"SELECT `" . $id . "` FROM data WHERE `1`='" . $value . "'"
			  );
			  if (mysqli_num_rows($queryName) > 0) {
				$display = ($display == "" ? $queryName->fetch_row()[0] : ($display . " " . $queryName->fetch_row()[0]));
			  }
		  }
  }
  $display = $value . ": " . $display;

  log_create($display);

  header("location: ../data_show.php");
  exit();
}

?>
