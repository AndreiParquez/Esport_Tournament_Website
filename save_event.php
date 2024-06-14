<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "event_management";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $eventName = $_POST['eventName'];
    $eventDescription = $_POST['eventDescription']; // Add description field
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
    $startTime = $_POST['startTime'];
    $endTime = $_POST['endTime'];
    $thumbnail = $_FILES['thumbnail'];

    $thumbnailPath = 'uploads/' . basename($thumbnail['name']);
    if (move_uploaded_file($thumbnail['tmp_name'], $thumbnailPath)) {
        $stmt = $conn->prepare("INSERT INTO events (name, description, start_date, end_date, start_time, end_time, thumbnail) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $eventName, $eventDescription, $startDate, $endDate, $startTime, $endTime, $thumbnailPath); // Updated binding parameters

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to upload thumbnail']);
    }
}

$conn->close();
?>
