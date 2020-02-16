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

// Check if the user has valid access_level
if($_SESSION["access_level"]<=1){
    header("location: welcome.php");
    exit;
}

// Include config file
require_once "includes/config.php";

$sql = "SELECT id, vorname, nachname, ort, land FROM data";
$result = $link->query($sql);

echo "<table border='1'>
<tr>
<th>Vorname</th>
<th>Nachname</th>
<th>Ort</th>
<th>Land</th>
</tr>";

while($row = mysqli_fetch_array($result))
{
echo "<tr>";
echo "<td>" . $row['vorname'] . "</td>";
echo "<td>" . $row['nachname'] . "</td>";
echo "<td>" . $row['ort'] . "</td>";
echo "<td>" . $row['land'] . "</td>";
echo "</tr>";
}
echo "</table>";



?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Hello, Bootstrap Table!</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <link rel="stylesheet" href="dist/bootstrap-table.min.css">
	<link href="x-editable/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet">
  </head>
  <body> 

<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="dist/bootstrap-table.min.js"></script>
	
	
<script src="x-editable/bootstrap3-editable/js/bootstrap-editable.js"></script>
	
	<script src="dist/extensions/editable/bootstrap-table-editable.js"></script>
	<script src="https://unpkg.com/tableexport.jquery.plugin/tableExport.min.js"></script>
	<script src="dist/bootstrap-table.min.js"></script>
	<script src="dist/extensions/export/bootstrap-table-export.min.js"></script>
	
	
<div id="toolbar">
  <button id="remove" class="btn btn-danger" disabled>
    <i class="glyphicon glyphicon-remove"></i> Delete
  </button>
</div>
	
	<?php

// Include config file
require_once "includes/config.php";

$sql = "SELECT id, vorname, nachname, ort, land FROM data";
$result = $link->query($sql);

echo "<table
  data-toggle=\"table\"
  data-search=\"true\"
  data-show-columns=\"true\"
  data-editable=\"true\"
  data-toolbar=\"#toolbar\"
  data-search=\"true\"
  data-show-refresh=\"true\"
  data-show-columns=\"true\"
  data-show-columns-toggle-all=\"true\"
  data-show-export=\"true\"
  data-click-to-select=\"true\"
  data-show-pagination-switch=\"true\"
  data-pagination=\"true\"
  data-id-field=\"id\"
  data-page-list=\"[10, 25, 50, 100, all]\"
  data-show-footer=\"true\">
  <thead>
<tr>
<th data-field=\"id\">ID</th>
<th data-field=\"vorname\" data-editable=\"true\">Vorname</th>
<th data-field=\"nachname\">Nachname</th>
<th data-field=\"ort\">Ort</th>
<th data-field=\"land\">Land</th>
</tr>
</thead>

 <tbody>";

while($row = mysqli_fetch_array($result))
{
echo "<tr>";
echo "<td data-editable-type=\"text\" data-editable-pk=\"1\" data-editable-url=\"/post\" data-editable-title=\"Enter username\">" . $row['id'] . "</td>";
echo "<td>" . $row['vorname'] . "</td>";
echo "<td>" . $row['nachname'] . "</td>";
echo "<td>" . $row['ort'] . "</td>";
echo "<td>" . $row['land'] . "</td>";
echo "</tr>";
}
echo "  </tbody>
</table>";

	?>

<script>
  var $table = $('#table')
  var $remove = $('#remove')
  var selections = []

  function getIdSelections() {
    return $.map($table.bootstrapTable('getSelections'), function (row) {
      return row.id
    })
  }

  function responseHandler(res) {
    $.each(res.rows, function (i, row) {
      row.state = $.inArray(row.id, selections) !== -1
    })
    return res
  }

  function detailFormatter(index, row) {
    var html = []
    $.each(row, function (key, value) {
      html.push('<p><b>' + key + ':</b> ' + value + '</p>')
    })
    return html.join('')
  }

  function operateFormatter(value, row, index) {
    return [
      '<a class="like" href="javascript:void(0)" title="Like">',
      '<i class="fa fa-heart"></i>',
      '</a>  ',
      '<a class="remove" href="javascript:void(0)" title="Remove">',
      '<i class="fa fa-trash"></i>',
      '</a>'
    ].join('')
  }

  window.operateEvents = {
    'click .like': function (e, value, row, index) {
      alert('You click like action, row: ' + JSON.stringify(row))
    },
    'click .remove': function (e, value, row, index) {
      $table.bootstrapTable('remove', {
        field: 'id',
        values: [row.id]
      })
    }
  }

  function totalTextFormatter(data) {
    return 'Total'
  }

  function totalNameFormatter(data) {
    return data.length
  }

  function totalPriceFormatter(data) {
    var field = this.field
    return '$' + data.map(function (row) {
      return +row[field].substring(1)
    }).reduce(function (sum, i) {
      return sum + i
    }, 0)
  }

  function initTable() {
    $table.bootstrapTable('destroy').bootstrapTable({
      height: 550,
      locale: $('#locale').val(),
      columns: [
        [{
          field: 'state',
          checkbox: true,
          rowspan: 2,
          align: 'center',
          valign: 'middle'
        }, {
          title: 'Item ID',
          field: 'id',
          rowspan: 2,
          align: 'center',
          valign: 'middle',
          sortable: true,
          footerFormatter: totalTextFormatter
        }, {
          title: 'Item Detail',
          colspan: 3,
          align: 'center'
        }],
        [{
          field: 'name',
          title: 'Item Name',
          sortable: true,
          footerFormatter: totalNameFormatter,
          align: 'center'
        }, {
          field: 'price',
          title: 'Item Price',
          sortable: true,
          align: 'center',
          footerFormatter: totalPriceFormatter
        }, {
          field: 'operate',
          title: 'Item Operate',
          align: 'center',
          clickToSelect: false,
          events: window.operateEvents,
          formatter: operateFormatter
        }]
      ]
    })
    $table.on('check.bs.table uncheck.bs.table ' +
      'check-all.bs.table uncheck-all.bs.table',
    function () {
      $remove.prop('disabled', !$table.bootstrapTable('getSelections').length)

      // save your data, here just save the current page
      selections = getIdSelections()
      // push or splice the selections if you want to save all data selections
    })
    $table.on('all.bs.table', function (e, name, args) {
      console.log(name, args)
    })
    $remove.click(function () {
      var ids = getIdSelections()
      $table.bootstrapTable('remove', {
        field: 'id',
        values: ids
      })
      $remove.prop('disabled', true)
    })
  }

  $(function() {
    initTable()

    $('#locale').change(initTable)
  })
</script>
	
	
	<script>
  $(function() {
    $('#table').bootstrapTable()
  })
</script>

    
  </body>
</html>