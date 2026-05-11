<?php
header('Content-Type: application/json');

// Підключення до БД
require_once 'db.php';

$response = array('success' => false, 'message' => '');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = trim($_POST['username']);
    $email = trim($_POST['email']);
    $pass = $_POST['password'];

    // Перевірка чи всі поля заповнені
    if (empty($user) || empty($email) || empty($pass)) {
        $response['message'] = "Please, fill all the fields";
        echo json_encode($response);
        exit;
    }

    // Перевірка коректності введеного формату email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = "Incorrect email format";
        echo json_encode($response);
        exit;
    }

    // Перевірка надійності пароля відповідно до вимог
    if (strlen($pass) < 8 || !preg_match("/[A-Z]/", $pass) || !preg_match("/[0-9]/", $pass)) {
        $response['message'] = "Password is too weak (needs at least 8 symbols, number and capital letter)";
        echo json_encode($response);
        exit;
    }

    // Перевірка чи існує даний email зареєстрованим у системі
    $checkSql = "SELECT id FROM `volvo-logins-table` WHERE email = ?";
    
    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $response['success'] = false;
        $response['message'] = "User with this email is already exists!"; 
        
        $stmt->close();
        echo json_encode($response);
        exit; 
    }
    $stmt->close();

    // Перевірка чи існує даний username зареєстрованим у системі
    $checkUserSql = "SELECT id FROM `volvo-logins-table` WHERE username = ?";
    $stmt = $conn->prepare($checkUserSql);
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $response['message'] = "User with this username is already exists!";
        $stmt->close();
        echo json_encode($response);
        exit;
    }
    $stmt->close();

    // Хешування пароля 
    $hashed_password = password_hash($pass, PASSWORD_DEFAULT);
    
    // Збереження нового юзера у БД
    $sql = "INSERT INTO `volvo-logins-table` (username, email, password) VALUES (?, ?, ?)";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sss", $user, $email, $hashed_password);
        
        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = "Registration completed succesfully!";
        } else {
            $response['message'] = "Error while saving: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $response['message'] = "Database error: " . $conn->error;
    }
    
    $conn->close();
}

echo json_encode($response);
?>