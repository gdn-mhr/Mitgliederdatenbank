<!-- This shows a table with views. Expects $name, $ns ID -> Name, $ds ID -> Description, $open, $edit, $delete, $new -->
<div style="max-width: 850px; display: block; margin-left: auto; margin-right: auto;">
	<h2><?php echo $name; ?></h2>
	<p></p>
	<div class="table-responsive"><table class="table table-bordered" id="dynamic_field_c"> <col width="auto"><col width="auto"><col width="350px">
		<?php 
			foreach($ns as $i => $n) {
				//Show each view with three buttons
				echo '<tr>';
				echo '<td><h2>'.$i.'</h2></td>';
				echo ("<td><h3>". $n . "</h3>");
				echo ("<p>" . $ds[$i] . "</p></td>");
				echo '<td style=" text-align: center;"><div style="display:inline-block;">';
				echo ('<form style="display:inline-block; padding:5px;" action="' . $open . '" method="post"><button type="submit" class="btn btn-outline-success" name="open" id="' . $i . '" value="' . $i . '">Öffnen</button></form>');
				echo ('<form style="display:inline-block; padding:5px;" action="' . $edit . '" method="post"><button type="submit" class="btn btn-outline-info" name="edit" id="e' . $i . '" value="' . $i . '">Bearbeiten</button></form>');
				echo ('<form style="display:inline-block; padding:5px;" action="' . $delete . '" method="post"><button type="submit" class="btn btn-outline-danger" name="delete" id="d' . $i . '" value="' . $i . '">Löschen</button></form>');
				echo '</div></td></tr>';
			}
			
		?>
		
	</table>
	</div>
</div>


<hr>


<!-- Option to create a new view -->
<div style="max-width: 400px; display: block; margin-left: auto; margin-right: auto;">
	<h2>Neuen Auszug hinzufügen</h2>
	<form action="<?php echo $new ?>" method="POST" role="form" class="form-horizontal">
		<button type="submit" class="btn btn-outline-success">Neu</button>
	</form>
</div>

<?php 
	include 'includes/template_footer.php';
?>