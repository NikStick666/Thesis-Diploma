<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');
// Підключення до БД
require_once 'login/db.php';

// Читання JSON-тіла запиту та декодування його в масив 
$data = json_decode(file_get_contents('php://input'), true);
$response = array('success' => false, 'message' => '');

// Перевірка наявності обов'язкових полів
if (isset($data['username']) && isset($data['car_id']) && isset($data['date']) && isset($data['time'])) {
    $username = trim($data['username']);
    $car_id = intval($data['car_id']);
    $drive_date = $data['date'];
    $drive_time = $data['time'];

    // Запит на додавання нового запису на тест-драйв із статусом Pending
    $sql = "INSERT INTO `test_drives` (username, car_id, drive_date, drive_time, status) VALUES (?, ?, ?, ?, 'Pending')";
    
    if ($stmt = $conn->prepare($sql)) {
        // Прив'язка параметрів, тобто s - string, i - int
        $stmt->bind_param("siss", $username, $car_id, $drive_date, $drive_time);
        
        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = "Booking confirmed!";
        } else {
            $response['message'] = "Saving error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $response['message'] = "Database error: " . $conn->error;
    }
} else {
    // Якщо хоча б одне обов'язкове поле відсутнє - повертається помилка
    $response['message'] = "Not all data were transfered!";
}

echo json_encode($response);
?>