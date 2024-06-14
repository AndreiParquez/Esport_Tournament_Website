<?php
session_start();


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

// Check if user is logged in
if (!isset($_SESSION['userId'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

$userId = $_SESSION['userId'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $postId = $_POST['postId'];

    // Check if the user has already liked the post
    $checkLikeSql = "SELECT * FROM likes WHERE postId = ? AND userId = ?";
    $stmt = $conn->prepare($checkLikeSql);
    $stmt->bind_param("ii", $postId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // Insert like into the likes table
        $insertLikeSql = "INSERT INTO likes (postId, userId) VALUES (?, ?)";
        $stmt = $conn->prepare($insertLikeSql);
        $stmt->bind_param("ii", $postId, $userId);
        $stmt->execute();

        // Update likes count in the posts table
        $updateLikesCountSql = "UPDATE posts SET likes_count = likes_count + 1 WHERE id = ?";
        $stmt = $conn->prepare($updateLikesCountSql);
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Already liked']);
    }
}
$conn->close();
?>
