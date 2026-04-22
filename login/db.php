<?php
/*$servername = "fdb1034.awardspace.net";
$username = "4752423_volvothesis";       
$password = "Qwerty123.";           
$dbname = "4752423_volvothesis"; */
$servername = "localhost";
$username = "root";       
$password = "";           
$dbname = "volvo-logins";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Помилка підключення: " . $conn->connect_error);
}
?>