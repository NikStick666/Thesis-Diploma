<?php
header('Content-Type: application/json');

require_once 'db.php';

$response = array('success' => false, 'message' => '');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = trim($_POST['username']);
    $email = trim($_POST['email']);
    $pass = $_POST['password'];

    if (empty($user) || empty($email) || empty($pass)) {
        $response['message'] = "Будь ласка, заповніть всі поля!";
        echo json_encode($response);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = "Невірний формат Email!";
        echo json_encode($response);
        exit;
    }

    if (strlen($pass) < 8 || !preg_match("/[A-Z]/", $pass) || !preg_match("/[0-9]/", $pass)) {
        $response['message'] = "Пароль занадто слабкий (потрібно 8+ символів, цифра та велика літера)";
        echo json_encode($response);
        exit;
    }

    $checkSql = "SELECT id FROM `volvo-logins-table` WHERE email = ?";
    
    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $response['success'] = false;
        $response['message'] = "Користувач з таким Email вже існує!"; 
        
        $stmt->close();
        echo json_encode($response);
        exit; 
    }
    $stmt->close();

    $hashed_password = password_hash($pass, PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO `volvo-logins-table` (username, email, password) VALUES (?, ?, ?)";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sss", $user, $email, $hashed_password);
        
        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = "Реєстрація успішна!";
        } else {
            $response['message'] = "Помилка при збереженні: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $response['message'] = "Помилка бази даних: " . $conn->error;
    }
    
    $conn->close();
}

echo json_encode($response);
?>