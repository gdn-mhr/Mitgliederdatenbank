<!-- This file is the raw template for any view editor, to be filled with adequat information via -->
<!-- Poster(), Cols(), Conds(), Name(), Description() $index is the ViewId if set, $in count of columns, opt, opo, ops need to be defined-->
<div class="container">  
	<br />  
	<br />  
	<h2 align="center">Welche Spalten sollen ausgewählt werden?</h2>  
	<div class="form-group">  
		<form name="add_column" id="add_column" action="<?php Poster(); ?>" method="POST">
			<!-- We need to add the index to the form to be able to update this exact index later on -->
			<input type="hidden" name="id" value="<?php echo isset($index) ? $index : ''; ?>" />
			<fieldset>
				<label>Namen für den Auszug</label>
				<input type="text" name="name" placeholder="Name" class="form-control" value="<?php Name(); ?>"/>
			</fieldset>
			<p>
			</p>
			<fieldset>
				<label>Eine kurze Beschreibung</label>
				<input type="textarea" name="desc" class="form-control" value="<?php Description(); ?>"/>
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
						Cols();
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
						Conds();
					?>								
					
				</table>  
			</div>
			<hr>
			<!-- We also need to know later on if we have any condition, so here another hidden input for this information -->
			<input type="hidden" id="has_cond" name="has_cond" value="false" />
			
			<input type="submit" name="submit" id="submit" class="btn btn-outline-success" value="Submit" style="display: block; margin-left: auto; margin-right: auto;" />  
			
		</form>  
	</div>  
	<?php include 'includes/footer.php' ?>   
	<script>  
		//If we have a button r-1, this means we have exactly one condition, so the button class needs to be changed.
		var r1 = document.getElementById('r-1');
		if(typeof(r1) != 'undefined' && r1 != null){
			document.getElementById('r-1').classList.add('btn_remove_cond_0');
			document.getElementById('r-1').classList.remove('btn_remove_cond');
		}
		
		//If we have an element lpar-1 we have conditions, so the hidden input needs to be set to true
		var lp1 = document.getElementById('lpar-1');
		if(typeof(lp1) != 'undefined' && lp1 != null){
			document.getElementById('has_cond').setAttribute('value','true');
		}
		
		//This function helps to remove a condition by recrusively renaming all the child conditions. ID is the beginning of our conditionID, RE is to be removed and EX is recrusively becoming longer (should be empty in the beginning)
		function removeCR(id,re,ex) {
			//Check if we have the condition cond-id-reex, if yes we need to rename it, if not we need to rename the "wrapper" and all its childs
			var c = document.getElementById('cond-'+id+'-'+re+ex+'');
			if(typeof(c) != 'undefined' && c != null)
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
				//Remove RE from the childs
				removeCR(id,re,(ex+'-1'));
				removeCR(id,re,(ex+'-2'));
			}
			
			
			
		}
		
		
		
		$(document).ready(function(){  
			var i=<?php echo $in; ?>;  
			//function to add a new selected column
			$('#addc').click(function(){  
				i++;
				var opt =  <?php echo json_encode($opt); ?>;		   
				$('#dynamic_field_c').append('<tr id="row'+i+'"><td><fieldset><label>Spalte auswählen</label><select name="col[]" class="form-control" >'+opt+'</select></fieldset></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
			});  
			//Remove a condition
			$(document).on('click', '.btn_remove', function(){  
				var button_id = $(this).attr("id");   
				$('#row'+button_id+'').remove();  
			}); 
			
			
			//If we don't have conditions, we add the first one here & remove the initial row 6 set our hidden input to true
			$(document).on('click', '.btn_cond_0', function(){  
				
				var button_id = $(this).attr("id"); 
				var opt = <?php echo json_encode($opt); ?>;
				var ops = <?php echo json_encode($ops); ?>;
				
				$('#dynamic_field_conds').append('<tr id="lpar-1"><td>(</td><td></td><td></td><td></td><td></td></tr>');
				$('#dynamic_field_conds').append('<tr id="cond-1"><td></td><td><fieldset><label>Spalte auswählen</label><select id="c_c-1" name="c_c-1" class="form-control" >'+opt+'</select></fieldset></td><td><fieldset><label>Operator auswählen</label><select id="c_t-1" name="c_t-1" class="form-control" >'+ops+'</select></fieldset></td><td><fieldset><label>Bedingung</label><input type="text" id="c_i-1" name="c_i-1" class="form-control" /></fieldset></td><td><button type="button" name="remove" id="r-1" class="btn btn-danger btn_remove btn_remove_cond_0" style="display: block; margin-left: auto; margin-right: auto;">X</button></td></tr>');
				$('#dynamic_field_conds').append('<tr id="b-1"><td></td><td></td><td><button type="button" name="add_cond" id="1" class="btn btn-outline-info btn_cond" style="display: block; margin-left: auto; margin-right: auto;">Weitere Bedingung</button></td><td></td><td></td></tr>');
				$('#dynamic_field_conds').append('<tr id="rpar-1"><td></td><td></td><td></td><td></td><td>)</td></tr>');		
				document.getElementById('has_cond').setAttribute('value','true');
				$('#row_'+button_id+'').remove();		
			});
			
			
			//We want to add one more condition
			$(document).on('click', '.btn_cond', function(){  
				
				//Change the class of the remove button if there was only one condition before
				var r1 = document.getElementById('r-1');
				if(typeof(r1) != 'undefined' && r1 != null){
					document.getElementById('r-1').classList.add('btn_remove_cond');
					document.getElementById('r-1').classList.remove('btn_remove_cond_0');
				}
				
				//Prepare all the new rows
				var button_id = $(this).attr("id");
				var opt = <?php echo json_encode($opt); ?>;
				var ops =  <?php echo json_encode($ops); ?>;
				var opao =  <?php echo json_encode($opo); ?>;
				
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
				
				//And insert the before the RPAR
				var orpar = document.getElementById('rpar-'+button_id+'');
				orpar.parentNode.insertBefore(nrpar1, orpar);
				orpar.parentNode.insertBefore(nop, orpar);		   
				orpar.parentNode.insertBefore(nlpar2, orpar);
				orpar.parentNode.insertBefore(ncond, orpar);
				orpar.parentNode.insertBefore(nb, orpar);
				orpar.parentNode.insertBefore(nrpar2, orpar);
				
				//Just the additional LPAR needs to be added before the old condition
				var ocond = document.getElementById('cond-'+button_id+'');
				ocond.parentNode.insertBefore(nlpar1, ocond);
				
				//Now we need to rename the existing rows and their buttons
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
			
			//The last condition is being removed, so delete everything, append the initial row and set our hidden input to false
			$(document).on('click', '.btn_remove_cond_0', function(){  
				
				$('#lpar-1').remove();	
				$('#cond-1').remove();	
				$('#b-1').remove();	
				$('#rpar-1').remove();	
				$('#dynamic_field_conds').append('<tr id="row_0"><td></td><td></td><td><button type="button" name="0" id="0" class="btn btn-outline-info btn_cond_0" style="display: block; margin-left: auto; margin-right: auto;">Bedingungen hinzufügen</button></td><td></td><td></td></tr>');	
				document.getElementById('has_cond').setAttribute('value','false');
			});
			
			//One of many conditions will be removed, so let's go!
			$(document).on('click', '.btn_remove_cond', function(){  
				
				//First we just remove all the rows that need to be removed - easy so far!
				var button_id = $(this).attr("id");
				var id = button_id.substring(2);
				$('#lpar-'+id+'').remove();	
				$('#cond-'+id+'').remove();	
				$('#b-'+id+'').remove();	
				$('#rpar-'+id+'').remove();	
				
				//x represents the prefix of our condition, y the last digit (either 1 or 2)
				var x = button_id.substring(2, button_id.length - 2);
				var y = button_id.substring(button_id.length - 1);
				if (y=='1') {
					var z = '2';
					} else {
					var z = '1';
				}
				
				//We also need to remove the operator and the condition wrapping the removed one
				$('#op-'+x+'').remove();
				$('#lpar-'+x+'').remove();
				$('#rpar-'+x+'').remove();
				
				//And finally we need to start renaming all the existing childs
				removeCR(x,z,'');
				
				
				//If there is only one condition left, we need to change classes again
				var r1 = document.getElementById('r-1');
				if(typeof(r1) != 'undefined' && r1 != null){
					document.getElementById('r-1').classList.add('btn_remove_cond_0');
					document.getElementById('r-1').classList.remove('btn_remove_cond');
				}
			});
			
		});  
	</script>		