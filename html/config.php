<?php
$servername = "localhost";
$username = "ServiceAccount";
$password = "S€rv!ce4ccount";
$dbname = "DBBUSYBOT";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}
?>