<?php
header('Content-Type: application/json');
// Підключення до БД
require_once 'login/db.php';

$response = array('success' => false, 'data' => null, 'message' => '');

if (isset($_GET['username'])) {
    $username = $_GET['username'];

    // Отримання даних користувача за його username
    $sql = "SELECT email, full_name, profile_picture FROM `volvo-logins-table` WHERE username = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        
        $stmt->store_result(); // Завантаження результату для можливості перевірки num_rows

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($db_email, $db_full_name, $db_profile_picture);
            $stmt->fetch();

            // Якщо користувач не має особистого фото профілю - використовується фото за замовчуванням
            if (empty($db_profile_picture)) {
                $db_profile_picture = 'default-avatar.png';
            }

            $response['success'] = true;
            $response['data'] = array(
                'email' => $db_email,
                'full_name' => $db_full_name,
                'profile_picture' => $db_profile_picture
            );
        } else {
            $response['message'] = "User not found";
        }
        $stmt->close();
    } else {
        $response['message'] = "Database error: " . $conn->error;
    }
} else {
    $response['message'] = "Username not entered";
}

// Повернення даних профілю у JSON
echo json_encode($response);
?>