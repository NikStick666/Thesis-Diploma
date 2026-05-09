<?php
// Підключення до БД
require_once 'login/db.php'; 

header('Content-Type: application/json');

// Отримання шляху до всіх зображень перед видаленням запису
$sql = "SELECT image_path FROM cars";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Видалення кожного файлу зображення з сервера
    while($row = $result->fetch_assoc()) {
        $file = $row['image_path'];
        if (file_exists($file)) {
            unlink($file); // Фізичне видалення файлу з диску
        }
    }
}

// Очищення таблиці та скидання лічильника AUTO_INCREMENT у БД
$sqlDelete = "TRUNCATE TABLE cars"; 

if ($conn->query($sqlDelete) === TRUE) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => $conn->error]);
}

$conn->close();
?>