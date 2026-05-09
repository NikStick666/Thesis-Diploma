<?php
// Підключення до БД
require_once 'login/db.php'; 
header('Content-Type: application/json');

// Отримання категорії через параметр GET, стандартне значення - electric
$category = isset($_GET['category']) ? $_GET['category'] : 'electric';

// Обрання авто за категорією, від найновіших 
$sql = "SELECT id, title, image_path, page_filename FROM cars WHERE category = ? ORDER BY id DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result();

$cars = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $cars[] = $row; // Додавання кожного рядка у масив результатів 
    }
}
// Повернення списка авто у JSON
echo json_encode($cars);
?>