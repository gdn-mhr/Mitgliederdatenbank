<!doctype html>
<html lang="de">
	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="robots" content="noindex">
		<title>Mitgliederdatenbank</title>
		
		<!-- Include Bootstrap, Bootstrap Table and Extensions -->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.1/css/all.css" crossorigin="anonymous">
		<link rel="stylesheet" href="includes/bootstrap-table/dist/bootstrap-table.min.css">
		<link href="includes/x-editable/dist/bootstrap4-editable/css/bootstrap-editable.css" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="includes/bootstrap-table/dist/extensions/filter-control/bootstrap-table-filter-control.css">
		
		<link rel="stylesheet" href="includes/template_style.css">
		
		<link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
		
		<link rel="shortcut icon" type="image/x-icon" href="includes/favicon.ico"/>
		
	</head>
	<body>
		<!-- Navbar -->
		<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
			<!-- Brand -->
			<a class="navbar-brand" href="index.php"><img src="includes/template_logo.png" alt="Logo" style="width:100px;"></a>
			
			<!-- Toggler/collapsibe Button -->
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
				<span class="navbar-toggler-icon"></span>
			</button>
			
			<!-- Navbar links -->
			<div class="collapse navbar-collapse" id="collapsibleNavbar">
				<ul class="navbar-nav mr-auto">
					<li id="index.php" class="nav-item">
						<a class="nav-link" href="index.php">Home</a>
					</li>
					<li id="data_view_list.php" class="nav-item">
						<a class="nav-link" href="data_view_list.php">Auszüge</a>
					</li>
					<li id="data_show.php" class="nav-item">
						<a class="nav-link" href="data_show.php">Alle Daten</a>
					</li>
					<li id="data_new.php" class="nav-item">
						<a class="nav-link" href="data_new.php">Neuer Eintrag</a>
					</li>
					<li id="data_column.php" class="nav-item">
						<a class="nav-link" href="data_column.php">Spalten verwalten</a>
					</li>
					<li id="archive.php" class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							Archiv
						</a>
						<div class="dropdown-menu" aria-labelledby="navbarDropdown">
							<a class="dropdown-item" href="archive_show.php">Gesamtes Archiv</a>
							<a class="dropdown-item" href="archive_view_list.php">Archiv-Auszüge</a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="archive_column.php">Spalten im Archiv verwalten</a>
						</div>
					</li>
				</ul>
				<ul class="navbar-nav ml-auto">
				<li id="user.php" class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown2" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							Benutzer
						</a>
						<div class="dropdown-menu right_dd" aria-labelledby="navbarDropdown2">
							<a class="dropdown-item" href="user_password.php">Passwort ändern</a>
							<?php 
								if ($_SESSION['access_level']>=5) {
									echo '<a class="dropdown-item" href="user_list.php">Benutzer verwalten</a>';
									echo '<a class="dropdown-item" href="user_register.php">Benutzer erstellen</a>';
								}
							?>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="user_logout.php">Abmelden</a>
						</div>
					</li>
				</ul>
			</div>
		</nav>
		<div id="container">
			<div  id="body">
				
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
				
				<script>
					var self = '<?php echo htmlspecialchars(basename($_SERVER['PHP_SELF'])); ?>';
					if (self.substr(0, 7) == 'archive') { self = 'archive_show';} else if (self.includes("view")) { self = 'data_view_list.php'; } else if (self.includes("user")) { self = 'user.php'; }
					document.getElementById(self).classList.add('active');
				</script>				