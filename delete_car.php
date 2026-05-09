<?php
require_once 'login/db.php';

header('Content-Type: application/json');
$response = array('success' => false, 'message' => '');

// Визначення ідентифікатора авто, яке треба видалити
$input = json_decode(file_get_contents('php://input'), true);
$id = isset($input['id']) ? intval($input['id']) : 0;

if ($id > 0) {
    // Визначення ім'я файлів для їх видалення
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

        // Видалення фізичних файлів
        if (file_exists($imagePath)) unlink($imagePath);
        if (file_exists($htmlFile)) unlink($htmlFile);
        if (file_exists($cssFile)) unlink($cssFile);

        // Видалення запису з БД
        $delStmt = $conn->prepare("DELETE FROM cars WHERE id = ?");
        $delStmt->bind_param("i", $id);
        
        if ($delStmt->execute()) {
            $response['success'] = true;
        } else {
            $response['message'] = 'Error deleting from database';
        }
    } else {
        $response['message'] = 'Car is not found';
    }
} else {
    $response['message'] = 'Incorrect ID';
}

echo json_encode($response);
?>