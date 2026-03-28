<?php
require_once 'login/db.php'; 
header('Content-Type: application/json');

$category = isset($_GET['category']) ? $_GET['category'] : 'electric';

$sql = "SELECT id, title, image_path, page_filename FROM cars WHERE category = ? ORDER BY id DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result();

$cars = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $cars[] = $row;
    }
}
echo json_encode($cars);
?>