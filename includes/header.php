<!doctype html>
<html lang="de">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Mitgliederdatenbank</title>

	<!-- Include Bootstrap, Bootstrap Table and Extensions -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.1/css/all.css" crossorigin="anonymous">
    <link rel="stylesheet" href="includes/bootstrap-table/dist/bootstrap-table.min.css">
	<link href="includes/x-editable/dist/bootstrap4-editable/css/bootstrap-editable.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="includes/bootstrap-table/dist/extensions/filter-control/bootstrap-table-filter-control.css">

	<link rel="stylesheet" href="includes/style.css">
  </head>
  <body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-md navbar-dark bg-dark">
  <!-- Brand -->
  <a class="navbar-brand" href="welcome.php"><img src="includes/Logo.png" alt="Logo" style="width:100px;"></a>

  <!-- Toggler/collapsibe Button -->
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>

  <!-- Navbar links -->
  <div class="collapse navbar-collapse" id="collapsibleNavbar">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="show_views.php">Views</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="show_entries.php">Alle Daten</a>
      </li>
	  <li class="nav-item">
        <a class="nav-link" href="edit_columns.php">Spalten verwalten</a>
      </li>
	  <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Archiv
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="archive.php">Gesamtes Archiv</a>
          <a class="dropdown-item" href="archive_show_views.php">Archiv-Views</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="archive_columns.php">Spalten im Archiv verwalten</a>
        </div>
      </li>
    </ul>
	<ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Abmelden</a>
            </li>
        </ul>
  </div>
</nav>

<div  style="padding:50px">

	<!-- Include Bootstrap, Bootstrap Table and Extensions -->
	<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="includes/bootstrap-table/dist/bootstrap-table.min.js"></script>
	<script src="includes/bootstrap-table/dist/bootstrap-table-locale-all.min.js"></script>
	<script src="includes/x-editable/dist/bootstrap4-editable/js/bootstrap-editable.js"></script>
	<script src="includes/bootstrap-table/dist/extensions/editable/bootstrap-table-editable.js"></script>	
	<script src="includes/bootstrap-table/dist/extensions/export/bootstrap-table-export.min.js"></script>
	<script src="includes/bootstrap-table/dist/extensions/filter-control/bootstrap-table-filter-control.min.js"></script>


	
	<script src="https://unpkg.com/tableexport.jquery.plugin/tableExport.min.js"></script>
	<script src="https://unpkg.com/tableexport.jquery.plugin/libs/jsPDF/jspdf.min.js"></script>
	<script src="https://unpkg.com/tableexport.jquery.plugin/libs/jsPDF-AutoTable/jspdf.plugin.autotable.js"></script>


	<?php
		function console_log( $data ){
		echo '<script>';
		echo 'console.log('. json_encode( $data ) .')';
		echo '</script>';
		}
	?>