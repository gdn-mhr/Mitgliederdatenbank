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
	
	$a = "SELECT id, name, description, dat, cond FROM views WHERE id='". $index . "';";
	$viewresult = $link->query($a);
	while($row = mysqli_fetch_array($viewresult))
	{
		$id = $row['id'];
		$name =  $row['name'];
		$des =  $row['description'];
		$dat = unserialize($row['dat']);
		$conds = unserialize($row['cond']);
	}
	
	
	
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
	unset($n);;
	$opts = array();
	foreach($cols as $x => $in) {
		if ($x > 1) {
			$opts[$x] = "";
			foreach($cols as $i => $n) {
				if ($i > 1) {
					if ($i == $x) {
						$opts[$x] .= "<option value='" . $i . "' selected>" . $n . "</option>";
						} else {
						$opts[$x] .= "<option value='" . $i . "'>" . $n . "</option>";
					}
				}
				
			}
		}
		unset($i);
		unset($n);
	}
	unset($in);
	unset($x);
	$opss = array();
	$opss[1] = "<option value='1' selected>=</option><option value='2'>></option><option value='3'><</option><option value='4'>>=</option><option value='5'><=</option><option value='6'><></option><option value='7'>LIKE</option><option value='8'>NOT LIKE</option>";
	$opss[2] = "<option value='1'>=</option><option value='2' selected>></option><option value='3'><</option><option value='4'>>=</option><option value='5'><=</option><option value='6'><></option><option value='7'>LIKE</option><option value='8'>NOT LIKE</option>";
	$opss[3] = "<option value='1'>=</option><option value='2'>></option><option value='3' selected><</option><option value='4'>>=</option><option value='5'><=</option><option value='6'><></option><option value='7'>LIKE</option><option value='8'>NOT LIKE</option>";
	$opss[4] = "<option value='1'>=</option><option value='2'>></option><option value='3'><</option><option value='4' selected>>=</option><option value='5'><=</option><option value='6'><></option><option value='7'>LIKE</option><option value='8'>NOT LIKE</option>";
	$opss[5] = "<option value='1'>=</option><option value='2'>></option><option value='3'><</option><option value='4'>>=</option><option value='5' selected><=</option><option value='6'><></option><option value='7'>LIKE</option><option value='8'>NOT LIKE</option>";
	$opss[6] = "<option value='1'>=</option><option value='2'>></option><option value='3'><</option><option value='4'>>=</option><option value='5'><=</option><option value='6' selected><></option><option value='7'>LIKE</option><option value='8'>NOT LIKE</option>";
	$opss[7] = "<option value='1'>=</option><option value='2'>></option><option value='3'><</option><option value='4'>>=</option><option value='5'><=</option><option value='6'><></option><option value='7' selected>LIKE</option><option value='8'>NOT LIKE</option>";
	$opss[8] = "<option value='1'>=</option><option value='2'>></option><option value='3'><</option><option value='4'>>=</option><option value='5'><=</option><option value='6'><></option><option value='7'>LIKE</option><option value='8' selected>NOT LIKE</option>";
	$opos = array();
	$opos[1] = "<option value='1' selected>AND</option><option value='2'>OR</option>";
	$opos[2] = "<option value='1'>AND</option><option value='2' selected>OR</option>";
	
	
	function reconstructConditions($cond, $idx) {
		global $opts, $opos, $opss;
		if (is_array($cond[0])) {
			echo '<tr id="lpar-' . $idx . '"><td>(</td><td></td><td></td><td></td><td></td></tr>';
			reconstructConditions($cond[0], $idx . '-1');
			echo '<tr id="op-' . $idx . '"><td></td><td></td><td><fieldset><label>Operator</label><select id="c_o-' . $idx . '" name="c_o-' . $idx . '" class="form-control" >' .  $opos[$cond[1]] . '</select></fieldset></td><td></td><td></td></tr>';
			reconstructConditions($cond[2], $idx . '-2');
			echo '<tr id="rpar-' . $idx . '"><td></td><td></td><td></td><td></td><td>)</td></tr>';
			} else {
			echo '<tr id="lpar-' . $idx . '"><td>(</td><td></td><td></td><td></td><td></td></tr>';
			echo '<tr id="cond-' . $idx . '"><td></td><td><fieldset><label>Spalte auswählen</label><select id="c_c-' . $idx . '" name="c_c-' . $idx . '" class="form-control" >' . $opts[$cond[0]] . '</select></fieldset></td><td><fieldset><label>Operator auswählen</label><select id="c_t-' . $idx . '" name="c_t-' . $idx . '" class="form-control" >' . $opss[$cond[1]] . '</select></fieldset></td><td><fieldset><label>Bedingung</label><input type="text" id="c_i-' . $idx . '" name="c_i-' . $idx . '" class="form-control" value="' . $cond[2] . '" /></fieldset></td><td><button type="button" name="remove" id="r-' . $idx . '" class="btn btn-danger btn_remove btn_remove_cond" style="display: block; margin-left: auto; margin-right: auto;">X</button></td></tr>';
			echo '<tr id="b-' . $idx . '"><td></td><td></td><td><button type="button" name="add_cond" id="' . $idx . '" class="btn btn-outline-info btn_cond" style="display: block; margin-left: auto; margin-right: auto;">Weitere Bedingung</button></td><td></td><td></td></tr>';
			echo '<tr id="rpar-' . $idx . '"><td></td><td></td><td></td><td></td><td>)</td></tr>';
		}
		
	}
	
?>


<?php include 'includes/header.php' ?>  
<div class="container">  
	<br />  
	<br />  
	<h2 align="center">Welche Spalten sollen ausgewählt werden?</h2>  
	<div class="form-group">  
		<form name="add_column" id="add_column" action="includes/alter_view.php" method="POST">  
			<input type="hidden" name="id" value="<?php echo isset($index) ? $index : ''; ?>" />
			<fieldset>
				<label>Namen für den Auszug</label>
				<input type="text" name="name" placeholder="Name" class="form-control" value="<?php echo $name ?>"/>
			</fieldset>
			<p>
			</p>
			<fieldset>
				<label>Eine kurze Beschreibung</label>
				<input type="textarea" name="desc" class="form-control" value="<?php echo $des ?>"/>
			</fieldset>
			<hr>
			<h3 align="center">Spalten</h3>
			<div class="table-responsive">  
				<table class="table table-bordered" id="dynamic_field_c">  
					<colgroup>
						<col style="width: 90%;">
						<col style="width: 10%;">
					</colgroup>
					<?php
						foreach ($dat as $in => $x) {
							echo '<tr id="row' . $in . '">';  
							echo '<td><fieldset>';
							echo '<label>Spalte auswählen</label>';
							echo ' <select name="col[]" class="form-control" >';
							echo  $opts[$x];
							echo '</select>';
							echo '</fieldset>';
							echo '</td>';
							if ($in == 0) 
							{
								echo '<td></td>';  
							} 
							else 
							{
								echo '<td><button type="button" name="remove" id="'. $in .'" class="btn btn-danger btn_remove">X</button></td>';  
							}
							echo '</tr>';  
						}
					?>
				</table>  
				<button type="button" name="addc" id="addc" class="btn btn-outline-info" style="display: block; margin-left: auto; margin-right: auto;">Mehr Spalten</button>
			</div> 
			<hr>
			<h3 align="center">Bedingungen</h3>
			<div class="table-responsive">  
				<table class="table table-bordered" id="dynamic_field_conds">
					<colgroup>
						<col style="width: 8%;">
						<col style="width: 32%;">
						<col style="width: 20%;">
						<col style="width: 32%;">
						<col style="width: 8%;">
					</colgroup>
					
					<?php
						if ($conds == []) {
							echo '<tr id="row_0"><td></td><td></td><td><button type="button" name="0" id="0" class="btn btn-outline-info btn_cond_0" style="display: block; margin-left: auto; margin-right: auto;" >Bedingungen hinzufügen</button></td><td></td><td></td></tr>';
							} else {								
							
							reconstructConditions($conds, '1');
						}
					?>								
					
				</table>  
				
			</div>
			<hr>
			<input type="hidden" id="has_cond" name="has_cond" value="false" />
			
			<input type="submit" name="submit" id="submit" class="btn btn-outline-success" value="Submit" style="display: block; margin-left: auto; margin-right: auto;" />  
			
		</form>  
	</div>  
	<?php include 'includes/footer.php' ?>   
	<script>  
		//Attempt to get the element using document.getElementById
		var element = document.getElementById('r-1');
		//If it isn't "undefined" and it isn't "null", then it exists.
		if(typeof(element) != 'undefined' && element != null){
			document.getElementById('r-1').classList.add('btn_remove_cond_0');
			document.getElementById('r-1').classList.remove('btn_remove_cond');
		}
		
		//Attempt to get the element using document.getElementById
		var element2 = document.getElementById('lpar-1');
		//If it isn't "undefined" and it isn't "null", then it exists.
		if(typeof(element2) != 'undefined' && element2 != null){
			document.getElementById('has_cond').setAttribute('value','true');
		}
		
		
		function removeCR(id,re,ex) {
			//Attempt to get the element using document.getElementById
			var element = document.getElementById('cond-'+id+'-'+re+ex+'');
			//If it isn't "undefined" and it isn't "null", then it exists.
			if(typeof(element) != 'undefined' && element != null)
			{
				document.getElementById('cond-'+id+'-'+re+ex+'').id = ('cond-'+id+ex+'');
				document.getElementById('c_c-'+id+'-'+re+ex+'').setAttribute('name','c_c-'+id+ex+'');
				document.getElementById('c_c-'+id+'-'+re+ex+'').id = ('c_c-'+id+ex+'');
				document.getElementById('c_t-'+id+'-'+re+ex+'').setAttribute('name','c_t-'+id+ex+'');
				document.getElementById('c_t-'+id+'-'+re+ex+'').id = ('c_t-'+id+ex+'');
				document.getElementById('c_i-'+id+'-'+re+ex+'').setAttribute('name','c_i-'+id+ex+'');
				document.getElementById('c_i-'+id+'-'+re+ex+'').id = ('c_i-'+id+ex+'');
				document.getElementById('b-'+id+'-'+re+ex+'').id = ('b-'+id+ex+'');
				document.getElementById(''+id+'-'+re+ex+'').id = (''+id+ex+'');
				document.getElementById('r-'+id+'-'+re+ex+'').id = ('r-'+id+ex+'');
				document.getElementById('lpar-'+id+'-'+re+ex+'').id = ('lpar-'+id+ex+'');
				document.getElementById('rpar-'+id+'-'+re+ex+'').id = 'rpar-'+id+ex+'';
			} else
			{
				document.getElementById('lpar-'+id+'-'+re+ex+'').id = ('lpar-'+id+ex+'');
				document.getElementById('rpar-'+id+'-'+re+ex+'').id = 'rpar-'+id+ex+'';
				document.getElementById('op-'+id+'-'+re+ex+'').id = 'op-'+id+ex+'';
				document.getElementById('c_o-'+id+'-'+re+ex+'').setAttribute('name','c_o-'+id+ex+'');
				document.getElementById('c_o-'+id+'-'+re+ex+'').id = ('c_o-'+id+ex+'');
				
				removeCR(id,re,(ex+'-1'));
				removeCR(id,re,(ex+'-2'));
			}
			
			
			
		}
		
		
		
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
			
			
			
			$(document).on('click', '.btn_cond_0', function(){  
				
				var button_id = $(this).attr("id");   
				
				var opt = <?php echo json_encode($opt); ?>;
				var ops = "<option value='1'>=<\/option><option value='2'>\><\/option><option value='3'>\<<\/option><option value='4'>\>=<\/option><option value='5'>\<=<\/option><option value='6'>\<\><\/option><option value='7'>LIKE<\/option><option value='8'>NOT LIKE<\/option>";
				
				$('#dynamic_field_conds').append('<tr id="lpar-1"><td>(</td><td></td><td></td><td></td><td></td></tr>');
				$('#dynamic_field_conds').append('<tr id="cond-1"><td></td><td><fieldset><label>Spalte auswählen</label><select id="c_c-1" name="c_c-1" class="form-control" >'+opt+'</select></fieldset></td><td><fieldset><label>Operator auswählen</label><select id="c_t-1" name="c_t-1" class="form-control" >'+ops+'</select></fieldset></td><td><fieldset><label>Bedingung</label><input type="text" id="c_i-1" name="c_i-1" class="form-control" /></fieldset></td><td><button type="button" name="remove" id="r-1" class="btn btn-danger btn_remove btn_remove_cond_0" style="display: block; margin-left: auto; margin-right: auto;">X</button></td></tr>');
				$('#dynamic_field_conds').append('<tr id="b-1"><td></td><td></td><td><button type="button" name="add_cond" id="1" class="btn btn-outline-info btn_cond" style="display: block; margin-left: auto; margin-right: auto;">Weitere Bedingung</button></td><td></td><td></td></tr>');
				$('#dynamic_field_conds').append('<tr id="rpar-1"><td></td><td></td><td></td><td></td><td>)</td></tr>');		
				document.getElementById('has_cond').setAttribute('value','true');
				$('#row_'+button_id+'').remove();		
			});
			
			$(document).on('click', '.btn_cond', function(){  
				
				//Attempt to get the element using document.getElementById
				var element = document.getElementById('r-1');
				//If it isn't "undefined" and it isn't "null", then it exists.
				if(typeof(element) != 'undefined' && element != null){
					document.getElementById('r-1').classList.add('btn_remove_cond');
					document.getElementById('r-1').classList.remove('btn_remove_cond_0');
				}
				
				
				var button_id = $(this).attr("id");
				var opt = <?php echo json_encode($opt); ?>;
				var ops = "<option value='1'>=<\/option><option value='2'>\><\/option><option value='3'>\<<\/option><option value='4'>\>=<\/option><option value='5'>\<=<\/option><option value='6'>\<\><\/option><option value='7'>LIKE<\/option><option value='8'>NOT LIKE<\/option>";
				var opao = "<option value='1'>AND<\/option><option value='2'>OR<\/option>";
				
				var nlpar1 = document.createElement('tr');
				nlpar1.id = 'lpar-'+button_id+'-1';
				nlpar1.innerHTML = '<td>(</td><td></td><td></td><td></td><td></td>';
				
				var nrpar1 = document.createElement('tr');
				nrpar1.id = 'rpar-'+button_id+'-1';
				nrpar1.innerHTML = '<td></td><td></td><td></td><td></td><td>)</td>';
				
				var nop = document.createElement('tr');
				nop.id = 'op-'+button_id+'';
				nop.innerHTML = '<td></td><td></td><td><fieldset><label>Operator</label><select id="c_o-'+button_id+'" name="c_o-'+button_id+'" class="form-control" >'+opao+'</select></fieldset></td><td></td><td></td>';
				
				var nlpar2 = document.createElement('tr');
				nlpar2.id = 'lpar-'+button_id+'-2';
				nlpar2.innerHTML = '<td>(</td><td></td><td></td><td></td><td></td>';
				
				var ncond = document.createElement('tr');
				ncond.id = 'cond-'+button_id+'-2';
				ncond.innerHTML = '<td></td><td><fieldset><label>Spalte auswählen</label><select id="c_c-'+button_id+'-2" name="c_c-'+button_id+'-2" class="form-control" >'+opt+'</select></fieldset></td><td><fieldset><label>Typ auswählen</label><select id="c_t-'+button_id+'-2" name="c_t-'+button_id+'-2" class="form-control" >'+ops+'</select></fieldset></td><td><fieldset><label>Bedingung</label><input type="text" id="c_i-'+button_id+'-2" name="c_i-'+button_id+'-2" class="form-control" /></fieldset></td><td><button type="button" name="remove" id="r-'+button_id+'-2" class="btn btn-danger btn_remove btn_remove_cond" style="display: block; margin-left: auto; margin-right: auto;">X</button></td>';
				
				var nb = document.createElement('tr');
				nb.id = 'b-'+button_id+'-2';
				nb.innerHTML = '<td></td><td></td><td><button type="button" name="add_cond" id="'+button_id+'-2" class="btn btn-outline-info btn_cond" style="display: block; margin-left: auto; margin-right: auto;">Weitere Bedingung</button></td><td></td><td></td>';
				
				var nrpar2 = document.createElement('tr');
				nrpar2.id = 'rpar-'+button_id+'-2';
				nrpar2.innerHTML = '<td></td><td></td><td></td><td></td><td>)</td>';
				
				var orpar = document.getElementById('rpar-'+button_id+'');
				orpar.parentNode.insertBefore(nrpar1, orpar);
				orpar.parentNode.insertBefore(nop, orpar);		   
				orpar.parentNode.insertBefore(nlpar2, orpar);
				orpar.parentNode.insertBefore(ncond, orpar);
				orpar.parentNode.insertBefore(nb, orpar);
				orpar.parentNode.insertBefore(nrpar2, orpar);
				
				var ocond = document.getElementById('cond-'+button_id+'');
				ocond.parentNode.insertBefore(nlpar1, ocond);
				
				//$('#lpar-'+button_id+'').insertAfter('<tr id="lpar-'+button_id+'-1"><td>(</td><td></td><td></td><td></td><td></td></tr>');
				//$('#b-'+button_id+'').insertAfter('<tr id="op-'+button_id+'"><td></td><td></td><td>OP</td><td></td><td></td></tr>');
				//$('#b-'+button_id+'').insertAfter('<tr id="rpar-'+button_id+'-1"><td>(</td><td></td><td></td><td></td><td></td></tr>');
				//$('#op-'+button_id+'').insertAfter('<tr id="rpar-'+button_id+'-2"><td></td><td></td><td></td><td></td><td>)</td></tr>');
				//$('#op-'+button_id+'').insertAfter('<tr id="b-'+button_id+'-2"><td></td><td></td><td><button type="button" name="add_cond" id="'+button_id+'-2" class="btn btn-outline-info btn_cond">Weitere Bedingung</button></td><td></td><td></td></tr>');
				//$('#op-'+button_id+'').insertAfter('<tr id="cond-'+button_id+'-2"><td></td><td>Select</td><td>Type</td><td>Cond</td><td></td></tr>');
				//$('#op-'+button_id+'').insertAfter('<tr id="lpar-'+button_id+'-2"><td>(</td><td></td><td></td><td></td><td></td></tr>');
				document.getElementById('cond-'+button_id+'').id = ('cond-'+button_id+'-1');
				document.getElementById('b-'+button_id+'').id = 'b-'+button_id+'-1';
				document.getElementById(''+button_id+'').id = ''+button_id+'-1';
				document.getElementById('r-'+button_id+'').id = 'r-'+button_id+'-1';
				document.getElementById('c_c-'+button_id+'').setAttribute('name','c_c-'+button_id+'-1');
				document.getElementById('c_c-'+button_id+'').id = 'c_c-'+button_id+'-1';
				document.getElementById('c_t-'+button_id+'').setAttribute('name','c_t-'+button_id+'-1');
				document.getElementById('c_t-'+button_id+'').id = 'c_t-'+button_id+'-1';
				document.getElementById('c_i-'+button_id+'').setAttribute('name','c_i-'+button_id+'-1');
				document.getElementById('c_i-'+button_id+'').id = 'c_i-'+button_id+'-1';
				
			});
			
			
			
			
			$(document).on('click', '.btn_remove_cond_0', function(){  
				
				$('#lpar-1').remove();	
				$('#cond-1').remove();	
				$('#b-1').remove();	
				$('#rpar-1').remove();	
				$('#dynamic_field_conds').append('<tr id="row_0"><td></td><td></td><td><button type="button" name="0" id="0" class="btn btn-outline-info btn_cond_0" style="display: block; margin-left: auto; margin-right: auto;">Bedingungen hinzufügen</button></td><td></td><td></td></tr>');	
				document.getElementById('has_cond').setAttribute('value','false');
			});
			
			
			
			
			$(document).on('click', '.btn_remove_cond', function(){  
				
				var button_id = $(this).attr("id");
				var id = button_id.substring(2);
				$('#lpar-'+id+'').remove();	
				$('#cond-'+id+'').remove();	
				$('#b-'+id+'').remove();	
				$('#rpar-'+id+'').remove();	
				
				var x = button_id.substring(2, button_id.length - 2);
				console.log(x);
				var y = button_id.substring(button_id.length - 1);
				console.log(y);
				
				if (y=='1') {
					var z = '2';
					} else {
					var z = '1';
				}
				
				
				$('#op-'+x+'').remove();
				
				$('#lpar-'+x+'').remove();
				$('#rpar-'+x+'').remove();
				
				removeCR(x,z,'');
				
				
				//Attempt to get the element using document.getElementById
				var element = document.getElementById('r-1');
				//If it isn't "undefined" and it isn't "null", then it exists.
				if(typeof(element) != 'undefined' && element != null){
					document.getElementById('r-1').classList.add('btn_remove_cond_0');
					document.getElementById('r-1').classList.remove('btn_remove_cond');
				}
				
				//document.getElementById('cond-'+x+'-'+z+'').id = ('cond-'+x+'');
				//document.getElementById('b-'+x+'-'+z+'').id = 'b-'+x+'';
				//document.getElementById(''+x+'-'+z+'').id = ''+x+'';
				//document.getElementById('r-'+x+'-'+z+'').id = 'r-'+x+'';
			});
			
		});  
	</script>																																