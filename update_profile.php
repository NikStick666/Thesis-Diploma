<?php
header('Content-Type: application/json');
require_once 'login/db.php';

$response = array('success' => false, 'message' => '', 'new_avatar' => '');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $full_name = trim($_POST['full_name']);
    $avatar_query_part = "";
    
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
        $uploadDir = 'uploads/avatars/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $fileName = time() . '_' . basename($_FILES['avatar']['name']);
        $targetFilePath = $uploadDir . $fileName;
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

        $allowTypes = array('jpg', 'png', 'jpeg', 'gif', 'webp');
        if (in_array($fileType, $allowTypes)) {
            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $targetFilePath)) {
                $avatar_query_part = ", profile_picture = '" . $fileName . "'";
                $response['new_avatar'] = $fileName;
            }
        } else {
            $response['message'] = "Only JPG, JPEG, PNG, GIF, WEBP image formats allowed.";
            echo json_encode($response);
            exit;
        }
    }

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