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

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Check if the user has valid access_level
if($_SESSION["access_level"]<=1){
    header("location: welcome.php");
    exit;
}

// Include config file
require_once "includes/config.php";

$index = 0;

foreach ($_POST as $i => $n) {
	$index = $i;
}

$a = "SELECT id, name, description, dat FROM archive_views WHERE id='". $index . "';";
$viewresult = $link->query($a);
while($row = mysqli_fetch_array($viewresult))
{
	$id = $row['id'];
	$name =  $row['name'];
	$des =  $row['description'];
	$dat = unserialize($row['dat']);
}



$sql = "SELECT id, name FROM archive_columns";

$colresult = $link->query($sql);

//prepare statement to retrieve real data


$cols = array();
while($row = mysqli_fetch_array($colresult))
{
	$cols[$row['id']] =  $row['name'];
}
$opt = "";
foreach($cols as $i => $n) {
	if ($i > 1) {
		$opt .= "<option value='" . $i . "'>" . $n . "</option>";
	}
}
unset($i);
unset($n);;
$opts = array();
foreach($dat as $in => $x) {
	$opts[$in] = "";
	foreach($cols as $i => $n) {
		if ($i > 1) {
			if ($i == $x) {
				$opts[$in] .= "<option value='" . $i . "' selected>" . $n . "</option>";
			} else {
				$opts[$in] .= "<option value='" . $i . "'>" . $n . "</option>";
			}
		}

	}
	unset($i);
	unset($n);
	//echo $opts[$in];
}
unset($in);
unset($x);
?>


<?php include 'includes/header.php' ?>  
           <div class="container">  
                <br />  
                <br />  
                <h2 align="center">Welche Spalten sollen ausgewählt werden?</h2>  
                <div class="form-group">  
                     <form name="add_column" id="add_column" action="includes/archive_alter_view.php" method="POST">  
					 <input type="hidden" name="id" value="<?php echo isset($index) ? $index : ''; ?>" />
					 <fieldset>
						<label>Namen für den Auszug</label>
						<input type="text" name="name" placeholder="Name" class="form-control" value="<?php echo $name ?>"/>
					 </fieldset>
					 <p></p>
					 <fieldset>
						<label>Eine kurze Beschreibung</label>
						<input type="textarea" name="desc" class="form-control" value="<?php echo $des ?>"/>
					 </fieldset>
					 <hr>
                          <div class="table-responsive">  
                               <table class="table table-bordered" id="dynamic_field_c">  
                                    <?php
										foreach ($dat as $in => $x) {
											echo '<tr id="row' . $in . '">';  
											echo '<td><fieldset>';
											echo '<label>Spalte auswählen</label>';
											echo ' <select name="col[]" class="form-control" >';
											echo  $opts[$in];
											echo '</select>';
											echo '</fieldset>';
											echo '</td>';
											if ($in == 0) {
												echo '<td></td>';  
											} else {
												echo '<td><button type="button" name="remove" id="'. $in .'" class="btn btn-danger btn_remove">X</button></td>';  
											}
											echo '</tr>';  
										}
										?>
                               </table>  
							   <button type="button" name="addc" id="addc" class="btn btn-outline-info">Add More</button>
                               <input type="submit" name="submit" id="submit" class="btn btn-outline-success" value="Submit" />  
                          </div>  
                     </form>  
                </div>  
<?php include 'includes/footer.php' ?>   
 <script>  
 $(document).ready(function(){  
      var i=<?php echo $in; ?>;  
      $('#addc').click(function(){  
           i++;
		   var opt =  <?php echo json_encode($opt); ?>;		   
           $('#dynamic_field_c').append('<tr id="row'+i+'"><td><fieldset><label>Spalte auswählen</label><select name="col[]" class="form-control" >'+opt+'</select></fieldset></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
      });  
      $(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");   
           $('#row'+button_id+'').remove();  
      });  
 });  
 </script>