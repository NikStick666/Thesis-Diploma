<?php
// Підключення БД
require_once 'login/db.php'; 

// Отримуємо ID з адреси 
$car_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Шукаємо необхідну машину в БД
$sql = "SELECT * FROM cars WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $car_id);
$stmt->execute();
$result = $stmt->get_result();

// Якщо машину знайдено
if ($result->num_rows > 0) {
    $car = $result->fetch_assoc();
} else {
    die("Машину не знайдено!");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($car['title']); ?></title>
    <link rel="stylesheet" href="electric.css"> <style>
        .car-details-container {
            max-width: 800px;
            margin: 50px auto;
            text-align: center;
            color: #fff; 
        }
        .car-details-img {
            width: 100%;
            border-radius: 10px;
        }
        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #333;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body style="background-color: #111;"> <div class="car-details-container">
        <h1><?php echo htmlspecialchars($car['title']); ?></h1>
        
        <img src="<?php echo htmlspecialchars($car['image_path']); ?>" alt="Car Image" class="car-details-img">
        
        <div style="margin-top: 30px; font-size: 18px; line-height: 1.6;">
            <p><?php echo nl2br(htmlspecialchars($car['description'])); ?></p>
        </div>

        <a href="index.html" class="back-btn">← Back to Showroom</a>
    </div>

</body>
</html>