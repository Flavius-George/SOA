<?php
$servername = "db";
$username = "user";
$password = "pass";
$dbname = "fs177";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexiune esuata: " . $conn->connect_error);
}
?>


