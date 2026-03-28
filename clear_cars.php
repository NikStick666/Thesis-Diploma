<?php
require_once 'login/db.php'; 

header('Content-Type: application/json');

$sql = "SELECT image_path FROM cars";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $file = $row['image_path'];
        if (file_exists($file)) {
            unlink($file);
        }
    }
}

$sqlDelete = "TRUNCATE TABLE cars"; 

if ($conn->query($sqlDelete) === TRUE) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => $conn->error]);
}

$conn->close();
?>