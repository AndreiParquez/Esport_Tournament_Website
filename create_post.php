<?php
session_start();

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "event_management";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    
    echo json_encode(['error' => 'Connection failed: ' . $conn->connect_error]);
    exit(); 
}


$userId = $_SESSION['userId'];
$username = $_SESSION['username'];
$avatar = $_SESSION['imagePath'];
$handle = isset($_SESSION['handle']) ? $_SESSION['handle'] : 'anonymous';
$content = '';
$imagePath = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $content = $_POST['content'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        $validExtensions = array("jpg", "jpeg", "png", "gif");
        if (in_array($imageFileType, $validExtensions)) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                $imagePath = $targetFile;
            } else {
                echo "Error uploading the file.";
            }
        } else {
            echo "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
        }
    }

 
    $sql = "INSERT INTO posts (userId, username, avatar, handle, content, image) VALUES (?, ?, ?, ?, ?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("isssss", $userId, $username, $avatar, $handle, $content, $imagePath);
        if ($stmt->execute()) {
            header("Location: social.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing the statement: " . $conn->error;
    }
}

$conn->close();
?>