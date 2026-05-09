<?php
header('Content-Type: application/json');
// Підключення до БД
require_once 'login/db.php';

$response = array('success' => false, 'message' => '', 'new_avatar' => '');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $full_name = trim($_POST['full_name']);
    $avatar_query_part = ""; // Додаткова частина SQL, яка заповнюється тільки якщо завантажено нове фото профілю
    
    // Обробка завантаження аватару у випадку якщо файл був переданий
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
        $uploadDir = 'uploads/avatars/';
        // Створення окремої директорії під аватари, якщо її ще немає
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        // Генерація унікального ім'я файлу через timestamp аби уникнути перезапису 
        $fileName = time() . '_' . basename($_FILES['avatar']['name']);
        $targetFilePath = $uploadDir . $fileName;
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

        // Перевірка формату файлу 
        $allowTypes = array('jpg', 'png', 'jpeg', 'gif', 'webp');
        if (in_array($fileType, $allowTypes)) {
            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $targetFilePath)) {
                // Додавання оновлення аватару до SQL-запиту 
                $avatar_query_part = ", profile_picture = '" . $fileName . "'";
                $response['new_avatar'] = $fileName;
            }
        } else {
            $response['message'] = "Only JPG, JPEG, PNG, GIF, WEBP image formats allowed.";
            echo json_encode($response);
            exit; // Зупинка виконання, так як нема сенсу оновлювати профіль з некоректним файлом
        }
    }

    // Формування SQL динамічно, з оновленим аватаром та без
    $sql = "UPDATE `volvo-logins-table` SET full_name = ? " . $avatar_query_part . " WHERE username = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ss", $full_name, $username);
        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = "Profile succesfully updated!";
        } else {
            $response['message'] = "Updating error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $response['message'] = "Database error!";
    }
}
echo json_encode($response);
?>