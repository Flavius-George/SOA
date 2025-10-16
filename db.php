<?php
$servername = "mysql"; // sau "localhost" dacă e același container
$username = "root";
$password = "root_password"; // parola ta
$dbname = "fs177";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexiunea a eșuat: " . $conn->connect_error);
}
?>
