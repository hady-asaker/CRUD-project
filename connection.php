<?php
$host = 'localhost';
$dbName = 'test';
$username = 'root';
$password = '';

$database = new PDO("mysql:host=$host; dbname=$dbName; charset=utf8", $username, $password); 

?>