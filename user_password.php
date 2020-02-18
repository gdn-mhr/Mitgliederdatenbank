<?php
	
	/**
		* @package    Mitgliederdatenbank
		*
		* @copyright  Copyright (C) 2020 Gideon Mohr. All rights reserved.
	*/
	
	/**
		This file lets a user update his password.
	*/
	// Initialize the session
	session_start();
	
	if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
		// last request was more than 30 minutes ago
		session_unset();     // unset $_SESSION variable for the run-time 
		session_destroy();   // destroy session data in storage
		header("location: user_login.php");
		exit;
	}
	$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
	
	// Check if the user is logged in, if not then redirect to login page
	if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
		header("location: user_login.php");
		exit;
	}
	
	// Include config file
	require_once "includes/template_config.php";
	
	// Define variables and initialize with empty values
	$new_password = $confirm_password = "";
	$new_password_err = $confirm_password_err = "";
	
	// Processing form data when form is submitted
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$pw = strip_tags( trim($_POST["new_password"]));
		$cpw = strip_tags( trim($_POST["confirm_password"]));
		$id = (int) $_SESSION["user"];
		
		// Validate new password
		if(empty($pw)){
			$new_password_err = "Please enter the new password.";     
			} elseif(strlen($pw) < 6){
			$new_password_err = "Password must have atleast 6 characters.";
			} else{
			$new_password = $pw;
		}
		
		// Validate confirm password
		if(empty($cpw)){
			$confirm_password_err = "Please confirm the password.";
			} else{
			$confirm_password = $cpw;
			if(empty($new_password_err) && ($new_password != $confirm_password)){
				$confirm_password_err = "Password did not match.";
			}
		}
        
		// Check input errors before updating the database
		if(empty($new_password_err) && empty($confirm_password_err)){
			// Prepare an update statement
			$sql = "UPDATE users SET password = ? WHERE id = ?";
			
			if($stmt = mysqli_prepare($link, $sql)){
				// Bind variables to the prepared statement as parameters
				mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);
				
				// Set parameters
				$param_password = password_hash($new_password, PASSWORD_DEFAULT);
				$param_id = $id;
				// Attempt to execute the prepared statement
				if(mysqli_stmt_execute($stmt)){
				// Password updated successfully. Destroy the session, and redirect to login page
					session_destroy();
					header("location: user_login.php");
					exit();
					} else{
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

<?php
	include 'includes/template_header.php';
?>

<div class="wrapper">
	<h2>Reset Password</h2>
	<p>Please fill out this form to reset your password.</p>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
		<div class="form-group <?php echo (!empty($new_password_err)) ? 'has-error' : ''; ?>">
			<label>New Password</label>
			<input type="password" name="new_password" class="form-control" value="<?php echo $new_password; ?>">
			<span class="help-block"><?php echo $new_password_err; ?></span>
		</div>
		<div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
			<label>Confirm Password</label>
			<input type="password" name="confirm_password" class="form-control">
			<span class="help-block"><?php echo $confirm_password_err; ?></span>
		</div>
		<div class="form-group">
			<input type="submit" class="btn btn-primary" value="Submit">
			<a class="btn btn-link" href="index.php">Cancel</a>
		</div>
	</form>
</div>    
<?php
	include 'includes/template_footer.php';
?>	