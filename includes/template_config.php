<?php
//some MYSQL-Servers have an offset when retrieving entries, if you archive an entry and the first column disappears, try changing this value to 1
$OFFSET_DATABASE = 0;
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'phpapp');
define('DB_PASSWORD', 'sicheresPasswort');
define('DB_NAME', 'php');

 
/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>