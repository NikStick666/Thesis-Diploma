<?php
header('Content-Type: application/json');
require_once 'login/db.php';

$response = array('success' => false, 'data' => null, 'message' => '');

if (isset($_GET['username'])) {
    $username = $_GET['username'];

    $sql = "SELECT email, full_name, profile_picture FROM `volvo-logins-table` WHERE username = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($db_email, $db_full_name, $db_profile_picture);
            $stmt->fetch();

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
            $response['message'] = "User didn't found";
        }
        $stmt->close();
    } else {
        $response['message'] = "Database error: " . $conn->error;
    }
} else {
    $response['message'] = "Username isn't entered";
}

echo json_encode($response);
?>