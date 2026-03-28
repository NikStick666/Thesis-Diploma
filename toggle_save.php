<?php
header('Content-Type: application/json');
require_once 'login/db.php'; 

$data = json_decode(file_get_contents('php://input'), true);
$response = array('success' => false, 'action' => '', 'message' => '');

if (isset($data['username']) && isset($data['car_id'])) {
    $username = $data['username'];
    $car_id = $data['car_id'];

    $checkSql = "SELECT id FROM `saved_cars` WHERE username = ? AND car_id = ?";
    if ($stmt = $conn->prepare($checkSql)) {
        $stmt->bind_param("si", $username, $car_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->close();
            $delSql = "DELETE FROM `saved_cars` WHERE username = ? AND car_id = ?";
            $delStmt = $conn->prepare($delSql);
            $delStmt->bind_param("si", $username, $car_id);
            $delStmt->execute();
            $delStmt->close();
            
            $response['success'] = true;
            $response['action'] = 'removed';
        } else {
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

echo json_encode($response);
?>