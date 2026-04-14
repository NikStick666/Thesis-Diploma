<?php
require_once 'login/db.php';

header('Content-Type: application/json');
$response = array('success' => false, 'message' => '');

// Отримуємо ID машини, яку треба видалити
$input = json_decode(file_get_contents('php://input'), true);
$id = isset($input['id']) ? intval($input['id']) : 0;

if ($id > 0) {
    // 1. Спочатку дізнаємось імена файлів, щоб видалити їх
    $sql = "SELECT image_path, page_filename FROM cars WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        $imagePath = $row['image_path']; // Наприклад: uploads/car.jpg
        $htmlFile = 'pages/' . $row['page_filename']; // Наприклад: pages/volvo.html
        // Визначаємо ім'я CSS файлу (замінюємо .html на .css)
        $cssFile = str_replace('.html', '.css', $htmlFile); 

        // 2. Видаляємо фізичні файли
        if (file_exists($imagePath)) unlink($imagePath);
        if (file_exists($htmlFile)) unlink($htmlFile);
        if (file_exists($cssFile)) unlink($cssFile);

        // 3. Видаляємо запис з БД
        $delStmt = $conn->prepare("DELETE FROM cars WHERE id = ?");
        $delStmt->bind_param("i", $id);
        
        if ($delStmt->execute()) {
            $response['success'] = true;
        } else {
            $response['message'] = 'Помилка видалення з БД';
        }
    } else {
        $response['message'] = 'Машину не знайдено';
    }
} else {
    $response['message'] = 'Невірний ID';
}

echo json_encode($response);
?>