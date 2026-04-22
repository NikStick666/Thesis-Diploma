<?php
$servername = "fdb1034.awardspace.net";
$username = "4752423_volvothesis";       
$password = "Qwerty123.";           
$dbname = "4752423_volvothesis"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection error: " . $conn->connect_error);
}
?>