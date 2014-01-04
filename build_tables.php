<?php
include("mysql.info.php");
$con=mysql_connect($mysql_server,$mysql_user,$mysql_password);
if(!$con){
	die('Could not connect: ' . mysql_error());
}
$sql="CREATE DATABASE $mysql_db_xzdr";
if (mysql_query($sql, $con)) {
	echo "Database $mysql_db_xzdr created successfully</br>";
} else {
	echo 'Error creating database: ' . mysql_error() . "</br>";
}
mysql_select_db($mysql_db_xzdr,$con);
$sql = "CREATE TABLE ".$mysql_tb_recoveries."
		(PID INT NOT NULL AUTO_INCREMENT,
		PRIMARY KEY(PID),
		device CHAR(25),
		title CHAR(255),
		release_version CHAR(25),
		compile_version INT,
		status CHAR(25),
		changelog TEXT)";
		
if (mysql_query($sql, $con)) {
	echo "Table $mysql_tb_recoveries created successfully</br>";
} else {
	echo 'Error creating database: ' . mysql_error() . "</br>";
}

mysql_select_db($mysql_db_xzdr,$con);
$sql = "CREATE TABLE ".$mysql_tb_files."
		(PID INT NOT NULL AUTO_INCREMENT,
		PRIMARY KEY(PID),
		release_id INT,
		filename CHAR(255),
		fileurl CHAR(25),
		instpath CHAR(25),
		chmod INT)";
if (mysql_query($sql, $con)) {
	echo "Table $mysql_tb_files created successfully</br>";
} else {
	echo 'Error creating database: ' . mysql_error() . "</br>";
}

$sql = "CREATE TABLE ".$mysql_tb_accounts."
		(PID INT NOT NULL AUTO_INCREMENT,
		PRIMARY KEY(PID),
		account CHAR(255)
		 )";
if (mysql_query($sql, $con)) {
	echo "Table $mysql_tb_accounts created successfully</br>";
} else {
	echo 'Error creating database: ' . mysql_error() . "</br>";
}
?>