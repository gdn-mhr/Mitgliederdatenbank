<?php
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
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: user_login.php");
    exit;
}
?>
 
<?php
	include 'includes/template_header.php';
?>
    <div class="page-header">
        <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Willkomen.</h1>
    </div>
    <h4>Bitte nutze eine der folgenden Optionen, um die Datenbank anzusehen oder zu ändern!</h4>
	<br>
	<h5>Schaue dir Auszüge an</h5>
	<p><a style="display:inline-block; padding:5px; " href="data_view_list.php" class="btn btn-outline-info">Alle Auszüge anzeigen</a>
		<a style="display:inline-block; padding:5px;" href="data_view_new.php" class="btn btn-outline-success">Neuen Auszug erstellen</a></p>
	
	<h5>Neuen Eintrag erstellen</h5>
	<p><a style="display:inline-block; padding:5px;" href="data_new.php" class="btn btn-outline-success">Neuer Eintrag</a></p>
		
	<h5>Schaue dir alle Daten an</h5>
	<p><a style="display:inline-block; padding:5px;" href="data_show.php" class="btn btn-outline-info">Alle Daten anzeigen</a></p>
	
	<h5>Verändere die Spalten</h5>
	<p><a style="display:inline-block; padding:5px;" href="data_column.php" class="btn btn-outline-info">Spalten anzeigen</a></p>
	
	<hr>
	
	<h5>Geh ins Archiv</h5>
	<p><a style="display:inline-block; padding:5px;" href="archive_show.php" class="btn btn-outline-info">Archiv anzeigen</a>
	   <a style="display:inline-block; padding:5px;" href="archive_views.php" class="btn btn-outline-info">Auszüge im Archiv anzeigen</a>
	   <a style="display:inline-block; padding:5px;" href="archive_view_new.php" class="btn btn-outline-success">Neuen Auszug erstellen</a>
	   <a style="display:inline-block; padding:5px;" href="archive_column.php" class="btn btn-outline-info">Spalten anzeigen</a></p>
	
	<hr>
	
	<h3>Benutzerverwaltung</h3>
	<br>
        <p><a style="display:inline-block; padding:5px;" href="user_password.php" class="btn btn-outline-warning">Passwort ändern</a>
        <a style="display:inline-block; padding:5px;" href="user_logout.php" class="btn btn-outline-danger">Abmelden</a>
        <?php if ( $_SESSION["access_level"] > 4) {echo '<a style="display:inline-block; padding:5px;" href="user_register.php" class="btn btn-outline-success">Neuen Benutzer erstellen</a>';} ?>
		<?php if ( $_SESSION["access_level"] > 4) {echo '<a style="display:inline-block; padding:5px;" href="user_list.php" class="btn btn-outline-info">Benutzer anzeigen</a>';} ?>
		</p>
<?php
	include 'includes/template_footer.php';
?>