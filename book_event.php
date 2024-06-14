<?php
// book_event.php

// Start session
session_start();

// Check if user is logged in
if(isset($_SESSION['userId'])) {
    // Retrieve form data
    $eventId = $_POST["eventId"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];

    // Validate form data (you can add more validation as needed)

    // Connect to MySQL database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "event_management";
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Prepare and bind SQL statement
        $stmt = $conn->prepare("INSERT INTO bookings (event_id, user_id, name, email, phone) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iisss", $eventId, $_SESSION['userId'], $name, $email, $phone);

        // Execute SQL statement
        if ($stmt->execute()) {
            // Update participant count in events table
            $updateStmt = $conn->prepare("
                UPDATE events e
                LEFT JOIN (
                    SELECT event_id, COUNT(*) AS participant_count
                    FROM bookings
                    GROUP BY event_id
                ) b ON e.id = b.event_id
                SET e.participant_count = IFNULL(b.participant_count, 0)
                WHERE e.id = ?
            ");
            $updateStmt->bind_param("i", $eventId);
            $updateStmt->execute();
            $updateStmt->close();

            // Commit transaction
            $conn->commit();

            echo "Booking successful";
        } else {
            // Rollback transaction if there is an error
            $conn->rollback();
            echo "Error: " . $stmt->error;
        }

        // Close statement
        $stmt->close();
    } catch (Exception $e) {
        // Rollback transaction if there is an exception
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }

    // Close connection
    $conn->close();
} else {
    // If user is not logged in, handle the error accordingly
    echo "Error: User not logged in";
}
?>