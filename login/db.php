<?php
$servername = "localhost";
$username = "root";       
$password = "";           
$dbname = "volvo-logins"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Помилка підключення: " . $conn->connect_error);
}
?>