<?php
$servername = "10.13.11.6";
$username = "fs177";
$password = "wa6ohrgq";
$dbname = "fs177";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexiune esuata: " . $conn->connect_error);
}
?>
