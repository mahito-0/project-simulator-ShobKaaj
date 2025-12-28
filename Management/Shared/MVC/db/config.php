<?php

/*
  Database Connection Wrapper
  Handles MySQLi connection to the ShobKaaj database.
*/

$host = "localhost";
$user = "root";
$pass = "123456";
$dbname = "shobkaaj_db";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connect Error: " . $conn->connect_error);
}

// Set charset to utf8mb4
$conn->set_charset("utf8mb4");
