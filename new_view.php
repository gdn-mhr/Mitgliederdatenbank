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

$sql = "SELECT id, name FROM columns";

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
unset($n);

?>

<?php include 'includes/header.php' ?>  
           <div class="container">  
                <br />  
                <br />  
                <h2 align="center">Welche Spalten sollen ausgewählt werden?</h2>  
                <div class="form-group">  
                     <form name="add_column" id="add_column" action="includes/save_view.php" method="POST">  
					 <fieldset>
						<label>Namen für den Auszug</label>
						<input type="text" name="name" placeholder="Name" class="form-control" />
					 </fieldset>
					 <p></p>
					 <fieldset>
						<label>Eine kurze Beschreibung</label>
						<input type="textarea" name="desc" class="form-control" />
					 </fieldset>
					 <hr>
                          <div class="table-responsive">  
                               <table class="table table-bordered" id="dynamic_field_c">  
                                    <tr>  
										<td><fieldset>
										<label>Spalte auswählen</label>
										<select name="col[]" class="form-control" >
											<?php echo $opt ?>
										</select>
										</fieldset>
										</td>
                                         <td></td>  
                                    </tr>  
                               </table>  
							   <button type="button" name="addc" id="addc" class="btn btn-outline-info">Add More</button>
                               <input type="submit" name="submit" id="submit" class="btn btn-outline-success" value="Submit" />  
                          </div>  
                     </form>  
                </div>  
<?php include 'includes/footer.php' ?>   
 <script>  
 $(document).ready(function(){  
      var i=1;  
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