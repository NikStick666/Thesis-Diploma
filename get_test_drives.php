<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');
require_once 'login/db.php';

$response = array('success' => false, 'drives' => array(), 'message' => '');

if (isset($_GET['username'])) {
    $username = trim($_GET['username']);

    $sql = "SELECT td.id, td.drive_date, td.drive_time, td.status, c.title 
            FROM `test_drives` td 
            JOIN `cars` c ON td.car_id = c.id 
            WHERE td.username = ? 
            ORDER BY td.drive_date ASC, td.drive_time ASC";
            
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $drive_date, $drive_time, $status, $car_title);
            
            $drives = array();
            while ($stmt->fetch()) {
                $drives[] = array(
                    'id' => $id,
                    'date' => $drive_date,
                    'time' => $drive_time,
                    'status' => $status,
                    'car_title' => $car_title
                );
            }
            $response['success'] = true;
            $response['drives'] = $drives;
        } else {
            $response['success'] = true; 
            $response['drives'] = array();
        }
        $stmt->close();
    } else {
        $response['message'] = "SQL error: " . $conn->error;
    }
} else {
    $response['message'] = "Username not entered";
}

echo json_encode($response);
?>