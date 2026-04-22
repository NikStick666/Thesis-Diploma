<?php
header('Content-Type: application/json');

require_once 'db.php';

$response = array('success' => false, 'message' => '');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT id, username, password, isAdmin FROM `volvo-logins-table` WHERE username = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $db_username, $db_password_hash, $isAdmin);
            $stmt->fetch();

            if (password_verify($password, $db_password_hash)) {
                $response['success'] = true;
                $response['username'] = $db_username;
                $response['isAdmin'] = $isAdmin;
            } else {
                $response['message'] = 'Incorrect password!';
            }
        } else {
            $response['message'] = 'User not found!';
        }
        $stmt->close();
    } else {
        $response['message'] = 'Database error: ' . $conn->error;
    }
    $conn->close();
}

echo json_encode($response);
exit;
?>