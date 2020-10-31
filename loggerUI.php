<?php

/**
 * @package    Mitgliederdatenbank
 *
 * @copyright  Copyright (C) 2020 Gideon Mohr. All rights reserved.
 */

/**
 *	This file shows the most recent logging entries.
 */
//Check if user is logged in
include 'includes/template_session.php';

//Start the HTML document with the header
include 'includes/template_header.php';
?>

<?php
//retrieve data from database
$sql = "SELECT time, level, user, message FROM log ORDER BY time DESC";
$colresult = $link->query($sql);

$name = 'Logs';
?>

<!-- This shows a table with the logs -->
<div style="max-width: 850px; display: block; margin-left: auto; margin-right: auto;">
	<h2><?php echo $name; ?></h2>
	
	<?php 
		//print the table options, espacially the POST php
		echo "<div><table  
		data-locale=\"de-DE\"
		data-toggle=\"table\"
		data-pagination=\"true\"
		data-id-field=\"time\"
		data-page-list=\"[10, 25, 50, 100, all]\">";
		
		//print the table header
		echo "<thead>";
		echo "<tr>";
		echo "<th data-field=\"time\">Zeit</th>";
		echo "<th data-field=\"level\">Level</th>";
		echo "<th data-field=\"name\">Name</th>";
		echo "<th data-field=\"action\">Aktion</th>";
		echo "</tr>";
        echo "</thead>";	
        
        while ($row = mysqli_fetch_array($colresult)) {
            echo "<tr>";
			echo "<td>" . $row['time'] . "</td>";
			echo "<td>" . $row['level'] . "</td>";
			echo "<td>" . $row['user'] . "</td>";
			echo "<td>" . $row['message'] . "</td>";
			echo "</tr>";
        }
		echo "</table> </div>";	
	?>
	
</div>

<?php 
	include 'includes/template_footer.php';
?>

<script>
	
	//convert the table into a Bootstrap table
	$(function() {
		$('#table').bootstrapTable();
	})

</script>
