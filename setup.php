<?php
// Include config file
require_once "includes/config.php";
if($link) {
	//table for users
	$usr = "CREATE TABLE users (
		id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
		username VARCHAR(50) NOT NULL UNIQUE,
		password VARCHAR(255) NOT NULL,
		access_level VARCHAR(1) NOT NULL,
		created_at DATETIME DEFAULT CURRENT_TIMESTAMP
	);";
	mysqli_query($link, $usr);
	
	//default user
	$param_password = password_hash("12345678", PASSWORD_DEFAULT);
	$def_usr = "INSERT INTO users (username, password, access_level) VALUES ('root', '" . $param_password . "', 5);";
	mysqli_query($link, $def_usr);
	
	//table for columns
	$col = "CREATE TABLE columns (
		id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
		name VARCHAR(255) NOT NULL,
		access_level INT NOT NULL DEFAULT 0
	);";
	mysqli_query($link, $col);
	
	//init with ID column
	$def_col = "INSERT INTO columns VALUES (NULL, 'ID', 5);";
	mysqli_query($link, $def_col);
	
	//table for data with just ID column
	$dat = "CREATE TABLE data (
		`1` INT NOT NULL PRIMARY KEY AUTO_INCREMENT
	);";
	mysqli_query($link, $dat);
	
	//table forviews
	$vie = "CREATE TABLE views (
		id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
		name VARCHAR(255) NOT NULL,
		description VARCHAR(4096) NOT NULL,
		dat VARCHAR(4096) NOT NULL,
		cond VARCHAR(4096) NOT NULL
	);";
	mysqli_query($link, $vie);
	
	//table for columns in archive
	$a_col = "CREATE TABLE archive_columns (
		id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
		name VARCHAR(255) NOT NULL,
		access_level INT NOT NULL DEFAULT 0
	);";
	mysqli_query($link, $a_col);
	
	//default column, see above
	$def_a_col = "INSERT INTO archive_columns VALUES (NULL, 'ID', 5);";
	mysqli_query($link, $def_a_col);
	
	//table for data in the archive
	$a_dat = "CREATE TABLE archive_data (
		`1` INT NOT NULL PRIMARY KEY AUTO_INCREMENT
	);";
	mysqli_query($link, $a_dat);
	
	//table for views in the archive
	$a_vie = "CREATE TABLE archive_views (
		id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
		name VARCHAR(255) NOT NULL,
		description VARCHAR(4096) NOT NULL,
		dat VARCHAR(4096) NOT NULL
		cond VARCHAR(4096) NOT NULL
	);";
	mysqli_query($link, $a_vie);
	
	echo "Success! ";
	echo "You should now delete this file!";
	
} else {
	echo "Error";
}
?>