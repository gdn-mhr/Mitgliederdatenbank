<?php

/**
 * @package    Mitgliederdatenbank
 *
 * @copyright  Copyright (C) 2020 Gideon Mohr. All rights reserved.
 */

/**
 *	This file creates a session.
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

// Include config file
require_once "includes/logger.php";

// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $pw = strip_tags(trim($_POST["password"]));
  $usr = strip_tags(trim($_POST["username"]));

  // Check if username is empty
  if (empty($usr)) {
    $username_err = "Please enter username.";
  } else {
    $username = $usr;
  }

  // Check if password is empty
  if (empty($pw)) {
    $password_err = "Please enter your password.";
  } else {
    $password = $pw;
  }

  // Validate credentials
  if (empty($username_err) && empty($password_err)) {
    // Prepare a select statement
    $sql =
      "SELECT id, username, password, access_level FROM users WHERE username = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
      // Bind variables to the prepared statement as parameters
      mysqli_stmt_bind_param($stmt, "s", $param_username);

      // Set parameters
      $param_username = $username;

      // Attempt to execute the prepared statement
      if (mysqli_stmt_execute($stmt)) {
        // Store result
        mysqli_stmt_store_result($stmt);

        // Check if username exists, if yes then verify password
        if (mysqli_stmt_num_rows($stmt) == 1) {
          // Bind result variables
          mysqli_stmt_bind_result(
            $stmt,
            $id,
            $username,
            $hashed_password,
            $access_level
          );
          if (mysqli_stmt_fetch($stmt)) {
            if (password_verify($password, $hashed_password)) {
              session_unset(); // unset $_SESSION variable for the run-time
              session_destroy(); // destroy session data in storage
              // Password is correct, so start a new session
              session_start();

              // Store data in session variables
              $_SESSION["loggedin"] = true;
              $_SESSION["id"] = password_hash($username, PASSWORD_DEFAULT);
              $_SESSION["user"] = $id;
              $_SESSION["username"] = $username;
              $_SESSION["access_level"] = $access_level;

              log_login();

              // Redirect user to welcome page
              header("location: index.php");
            } else {
              log_failed_login($username);
              // Display an error message if password is not valid
              $password_err = "The password you entered was not valid.";
            }
          }
        } else {
          log_failed_login($username);
          // Display an error message if username doesn't exist
          $username_err = "No account found with that username.";
        }
      } else {
        echo "Oops! Something went wrong. Please try again later.";
      }
    }

    // Close statement
    mysqli_stmt_close($stmt);
  }

  // Close connection
  mysqli_close($link);
}
?>

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
				
				<div class="wrapper">
					
					<h2>Login</h2>
					<p>Bitte gib deinen Benutzernamen und dein Passwort ein um fortzufahren.</p>
					<form action="<?php echo htmlspecialchars(
       $_SERVER["PHP_SELF"]
     ); ?>" method="post">
						<div class="form-group <?php echo !empty($username_err) ? 'has-error' : ''; ?>">
							<label>Benutzername</label>
							<input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
							<span class="help-block"><?php echo $username_err; ?></span>
						</div>    
						<div class="form-group <?php echo !empty($password_err) ? 'has-error' : ''; ?>">
							<label>Passwort</label>
							<input type="password" name="password" class="form-control">
							<span class="help-block"><?php echo $password_err; ?></span>
						</div>
						<div class="form-group">
							<input type="submit" class="btn btn-primary" value="Login">
						</div>
					</form>
					
					<p><a style="display:inline-block; padding:5px;" href="user_sso.php" class="btn btn-outline-success">Oder über SSO anmelden</a></p>
				</div>    
				
			<?php include 'includes/template_footer.php'; ?>
