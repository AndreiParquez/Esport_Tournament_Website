<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "event_management";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    // Return error response
    echo json_encode(['error' => 'Connection failed: ' . $conn->connect_error]);
    exit(); // Terminate script execution
}

// Initialize variables
$userId = $_SESSION['userId'];
$username = $_SESSION['username'];
$avatar = $_SESSION['imagePath'];
$handle = isset($_SESSION['handle']) ? $_SESSION['handle'] : 'anonymous'; // Provide a fallback handle
$content = '';
$imagePath = '';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $content = $_POST['content'];

    // Check if an image is uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Validate image file type
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

    // Insert post into the database
    $sql = "INSERT INTO posts (userId, username, avatar, handle, content, image) VALUES (?, ?, ?, ?, ?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("isssss", $userId, $username, $avatar, $handle, $content, $imagePath);
        if ($stmt->execute()) {
            header("Location: social.php"); // Redirect to the dashboard or wherever appropriate
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