<?php
// Initialize the session
session_start();

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
	header("location: login.php");
    exit;
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
 
<?php
	include 'includes/header.php';
?>
    <div class="page-header">
        <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Willkomen.</h1>
    </div>
    <h4>Bitte nutze eine der folgenden Optionen, um die Datenbank anzusehen oder zu ändern!</h4>
	<br>
	<h5>Schaue dir Auszüge an</h5>
	<p><a style="display:inline-block; padding:5px; " href="show_views.php" class="btn btn-outline-info">Alle Auszüge anzeigen</a>
		<a style="display:inline-block; padding:5px;" href="new_view.php" class="btn btn-outline-success">Neuen Auszug erstellen</a></p>
	
	<h5>Neuen Eintrag erstellen</h5>
	<p><a style="display:inline-block; padding:5px;" href="new_entry.php" class="btn btn-outline-success">Neuer Eintrag</a></p>
		
	<h5>Schaue dir alle Daten an</h5>
	<p><a style="display:inline-block; padding:5px;" href="show_entries.php" class="btn btn-outline-info">Alle Daten anzeigen</a></p>
	
	<h5>Verändere die Spalten</h5>
	<p><a style="display:inline-block; padding:5px;" href="edit_columns.php" class="btn btn-outline-info">Spalten anzeigen</a></p>
	
	<hr>
	
	<h5>Geh ins Archiv</h5>
	<p><a style="display:inline-block; padding:5px;" href="archive.php" class="btn btn-outline-info">Archiv anzeigen</a>
	   <a style="display:inline-block; padding:5px;" href="archive_views.php" class="btn btn-outline-info">Auszüge im Archiv anzeigen</a>
	   <a style="display:inline-block; padding:5px;" href="archive_new_view.php" class="btn btn-outline-success">Neuen Auszug erstellen</a>
	   <a style="display:inline-block; padding:5px;" href="archive_columns.php" class="btn btn-outline-info">Spalten anzeigen</a></p>
	
	<hr>
	
	<h3>Benutzerverwaltung</h3>
	<br>
        <p><a style="display:inline-block; padding:5px;" href="reset-password.php" class="btn btn-outline-warning">Passwort ändern</a>
        <a style="display:inline-block; padding:5px;" href="logout.php" class="btn btn-outline-danger">Abmelden</a>
        <?php if ( $_SESSION["access_level"] > 4) {echo '<a style="display:inline-block; padding:5px;" href="register.php" class="btn btn-outline-success">Neuen Benutzer erstellen</a>';} ?>
		<?php if ( $_SESSION["access_level"] > 4) {echo '<a style="display:inline-block; padding:5px;" href="list_users.php" class="btn btn-outline-info">Benutzer anzeigen</a>';} ?>
		</p>
<?php
	include 'includes/footer.php';
?>