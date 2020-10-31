<?php
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

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
  header("location: user_login.php");
  exit();
}

// Check if the user has valid access_level
if ($_SESSION["access_level"] < 5) {
  header("location: index.php");
  exit();
}
?>

<?php
// Include config file
require_once "includes/template_config.php";

// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $pw = strip_tags(trim($_POST["password"]));
  $cpw = strip_tags(trim($_POST["confirm_password"]));
  $usr = strip_tags(trim($_POST["username"]));

  // Validate username
  if (empty($usr)) {
    $username_err = "Please enter a username.";
  } else {
    // Prepare a select statement
    $sql = "SELECT id FROM users WHERE username = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
      // Bind variables to the prepared statement as parameters
      mysqli_stmt_bind_param($stmt, "s", $param_username);

      // Set parameters
      $param_username = $usr;

      // Attempt to execute the prepared statement
      if (mysqli_stmt_execute($stmt)) {
        /* store result */
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) == 1) {
          $username_err = "This username is already taken.";
        } else {
          $username = $usr;
        }
      } else {
        echo "Oops! Something went wrong. Please try again later.";
      }
    }

    // Close statement
    mysqli_stmt_close($stmt);
  }

  // Validate password
  if (empty($pw)) {
    $password_err = "Please enter a password.";
  } elseif (strlen($pw) < 6) {
    $password_err = "Password must have atleast 6 characters.";
  } else {
    $password = $pw;
  }

  // Validate confirm password
  if (empty($cpw)) {
    $confirm_password_err = "Please confirm password.";
  } else {
    $confirm_password = $cpw;
    if (empty($password_err) && $password != $confirm_password) {
      $confirm_password_err = "Password did not match.";
    }
  }

  // Check input errors before inserting in database
  if (
    empty($username_err) &&
    empty($password_err) &&
    empty($confirm_password_err)
  ) {
    $param_password = password_hash($password, PASSWORD_DEFAULT);
    // Prepare an insert statement
    $sql =
      "INSERT INTO users (username, password, access_level) VALUES ('" .
      $username .
      "', '" .
      $param_password .
      "', 1)";
    if (mysqli_query($link, $sql)) {
      // Redirect to login page
      header("location: user_list.php");
    } else {
      echo "Something went wrong. Please try again later.";
    }
  }

  // Close connection
  mysqli_close($link);
}
?>
 
<?php include 'includes/template_header.php'; ?>
    <div class="wrapper">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars(
          $_SERVER["PHP_SELF"]
        ); ?>" method="post">
            <div class="form-group <?php echo !empty($username_err)
              ? 'has-error'
              : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo !empty($password_err)
              ? 'has-error'
              : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo !empty($confirm_password_err)
              ? 'has-error'
              : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
        </form>
    </div>    
<?php include 'includes/template_footer.php';
?>
