<?php
header('Content-Type: application/json');
// Підключення до БД
require_once 'login/db.php'; 

// Читання JSON-тіла запиту
$data = json_decode(file_get_contents('php://input'), true);
$response = array('success' => false, 'action' => '', 'message' => '');

if (isset($data['username']) && isset($data['car_id'])) {
    $username = $data['username'];
    $car_id = $data['car_id'];

    // Перевірка того чи збережене певне авто у користувача
    $checkSql = "SELECT id FROM `saved_cars` WHERE username = ? AND car_id = ?";
    if ($stmt = $conn->prepare($checkSql)) {
        $stmt->bind_param("si", $username, $car_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Якщо авто вже збережено - видаляємо його із списку збережених
            $stmt->close();
            $delSql = "DELETE FROM `saved_cars` WHERE username = ? AND car_id = ?";
            $delStmt = $conn->prepare($delSql);
            $delStmt->bind_param("si", $username, $car_id);
            $delStmt->execute();
            $delStmt->close();
            
            $response['success'] = true;
            $response['action'] = 'removed';
        } else {
            // Якщо авто не збережено - зберігаємо його
            $stmt->close();
            $insSql = "INSERT INTO `saved_cars` (username, car_id) VALUES (?, ?)";
            $insStmt = $conn->prepare($insSql);
            $insStmt->bind_param("si", $username, $car_id);
            $insStmt->execute();
            $insStmt->close();

            $response['success'] = true;
            $response['action'] = 'added';
        }
    }
} else {
    $response['message'] = 'Incorrect data';
}

// Повернення результату дії (added або removed) у JSON
echo json_encode($response);
?>