<!-- This is a basic viewer, expects $name, $post, $cols as ID -> Name, $data as array of rows as arrays, and may include additional buttons in the tolbar with $buttons -->

<h2><?php echo $name ?></h2>

<div>
	<table id="table" data-toggle="table" data-locale="de-DE" data-toggle="table" data-search="true" data-show-columns="true" data-editable="true" data-editable-url="<?php echo $post; ?>" data-toolbar="#toolbar" data-search="true" data-show-columns-toggle-all="true" data-show-export="true" data-click-to-select="true" data-pagination="true" data-id-field="ID" data-page-list="[10, 25, 50, 100, all]" data-filter-control="false" data-show-search-clear-button="true">
		<thead>
			<tr>
				<?php
					//Print columns, each ne editable except 0 aka ID
					foreach ($cols as $i => $cname) {
						if ($i > 1) {
							echo "<th data-field=\"". $i ."\" data-filter-control=\"input\" data-sortable=\"true\" data-editable=\"true\">" . $cname . "</th>";
							} else {
							echo "<th data-field=\"ID\" data-sortable=\"true\" data-editable=\"false\">" . $cname . "</th>";
						}
					}
					unset($cname);
					unset($i);
					//eventually print another column
					echo isset($additional_c) ? $additional_c : '';
				?>
			</tr>
		</thead>
		<?php 
			//Print data
			$i = 0;
			foreach ($data as $values) {
				
				echo "<tr>";
				
				foreach ($values as $value) {
					echo "<td>" . $value . "</td>";
				}
				unset($value);
				
				//fill another column, if set
				echo isset($additional_d[$i]) ? $additional_d[$i] : '';
				$i = $i + 1;
				echo "</tr>";
				
			}
			unset($values);
			unset($i);
		?>
	</table> 
</div>

<div id="toolbar">
	<?php echo isset($buttons) ? $buttons : ''; ?>
	<button id="filter-toggle" class="btn btn-outline-info" onClick="toggleFilter();" >
		<i class="glyphicon glyphicon-remove"></i> Erweiterte Filter
	</button>
</div>

<?php
	include 'includes/footer.php';
?>

<script>
	$.fn.editable.defaults.mode = 'inline';
	
	$(function() {
		$('#table').bootstrapTable();
	})
	
	var isset = false
	function toggleFilter () {
		if (isset == true) { 
			$('#table').bootstrapTable('refreshOptions', {
				filterControl: false,
			});
			isset = false;
			} else {
			$('#table').bootstrapTable('refreshOptions', {
				filterControl: true,
			});
			isset = true;
		}
	}
</script>	