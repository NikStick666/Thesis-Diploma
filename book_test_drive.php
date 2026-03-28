<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');
require_once 'login/db.php';

$data = json_decode(file_get_contents('php://input'), true);
$response = array('success' => false, 'message' => '');

if (isset($data['username']) && isset($data['car_id']) && isset($data['date']) && isset($data['time'])) {
    $username = trim($data['username']);
    $car_id = intval($data['car_id']);
    $drive_date = $data['date'];
    $drive_time = $data['time'];

    $sql = "INSERT INTO `test_drives` (username, car_id, drive_date, drive_time, status) VALUES (?, ?, ?, ?, 'Pending')";
    
    if ($stmt = $conn->prepare($sql)) {
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
    $response['message'] = "Not all data were transfered!";
}

echo json_encode($response);
?>