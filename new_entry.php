<?php
	
	/**
		* @package    Mitgliederdatenbank
		*
		* @copyright  Copyright (C) 2020 Gideon Mohr. All rights reserved.
	*/
	
	/**
		This file enables the user to eadd an entry.
	*/
	//Check if user is logged in
	include 'includes/session.php';
	
	//Start the HTML document with the header
	include 'includes/header.php';
	
?>

<?php
	//retrieve column data
	$sql = "SELECT id, name, access_level FROM columns";
	$colresult = $link->query($sql);
	
	while($row = mysqli_fetch_array($colresult))
	{
		$cols[$row['id']] =  $row['name'];
	}
	
?>

<div style="max-width: 500px; display: block; margin-left: auto; margin-right: auto;">
	
	<h2>Neuen Eintrag hinzufügen</h2>
	<br>
	<form action="includes/save_entry.php" method="POST" role="form" class="form-horizontal">
		
		<?php
			//add one input for each column
			foreach ($cols as $i => $cname) {
				if ($i > 1) {
					echo "<div class=\"form-group\">";
					echo "<label for=\"" . $i . "\">" . $cname . "</label>";
					echo "<input type=\"text\" class=\"form-control\" id=\"" . $i . "\"  name=\"" . $i . "\">";
					echo "</div>";
				}
			}
			unset($cname);
			unset($i);
		?>
		
		<button type="submit" class="btn btn-outline-success">Speichern</button>
	</form>
</div>


<?php 
	include 'includes/footer.php';
?>