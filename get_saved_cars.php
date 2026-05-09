<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');
// Підключення до БД
require_once 'login/db.php';

$response = array('success' => false, 'cars' => array(), 'message' => '');

if (isset($_GET['username'])) {
    $username = trim($_GET['username']);

    // Отримання автомобілів збережених користувачем за допомогою JOIN з таблицею saved_cars
    $sql = "SELECT c.id, c.title, c.image_path, c.page_filename 
            FROM `cars` c 
            JOIN `saved_cars` sc ON c.id = sc.car_id 
            WHERE sc.username = ?";
            
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($car_id, $car_title, $car_image, $car_filename);
            
            $cars = array();
            // Збірка усіх знайдених авто у спільний масив
            while ($stmt->fetch()) {
                $cars[] = array(
                    'id' => $car_id,
                    'title' => $car_title,
                    'image_path' => $car_image,
                    'page_filename' => $car_filename
                );
            }
            $response['success'] = true;
            $response['cars'] = $cars;
        } else {
            // Якщо збережених авто немає - повертається порожній масив 
            $response['success'] = true; 
            $response['cars'] = array();
        }
        $stmt->close();
    } else {
        $response['message'] = "SQL error: " . $conn->error;
    }
} else {
    $response['message'] = "Username not entered!";
}

// Повернення списку збережених авто у JSON
echo json_encode($response);
?>