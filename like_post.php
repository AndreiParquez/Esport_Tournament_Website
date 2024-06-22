<?php
session_start();


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "event_management";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    
    echo json_encode(['error' => 'Connection failed: ' . $conn->connect_error]);
    exit(); 
}


if (!isset($_SESSION['userId'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

$userId = $_SESSION['userId'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $postId = $_POST['postId'];


    $checkLikeSql = "SELECT * FROM likes WHERE postId = ? AND userId = ?";
    $stmt = $conn->prepare($checkLikeSql);
    $stmt->bind_param("ii", $postId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        
        $insertLikeSql = "INSERT INTO likes (postId, userId) VALUES (?, ?)";
        $stmt = $conn->prepare($insertLikeSql);
        $stmt->bind_param("ii", $postId, $userId);
        $stmt->execute();

      
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
