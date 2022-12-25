<?php

$servername = "vinylproject-db.mysql.database.azure.com";
$username = "vinyladmin";
$password = "4Xm6nDRHcep1";
$db = "vinylproject";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    echo("Failed to establish connection to database");
}

?>