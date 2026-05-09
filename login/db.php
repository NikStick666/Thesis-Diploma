<?php
// Параметри підключення до БД
$servername = "fdb1034.awardspace.net";
$username = "4752423_volvothesis";       
$password = "Qwerty123.";           
$dbname = "4752423_volvothesis"; 

// Створення підключення до БД
$conn = new mysqli($servername, $username, $password, $dbname);

// Зупинка виконання в разі якщо підключяення неуспішне 
if ($conn->connect_error) {
    die("Connection error: " . $conn->connect_error);
}
?>