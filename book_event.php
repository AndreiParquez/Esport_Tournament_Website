<?php

session_start();

if (isset($_SESSION['userId'])) {
    $eventId = $_POST["eventId"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];

    if (empty($eventId) || empty($name) || empty($email) || empty($phone)) {
        echo json_encode(['error' => 'All fields are required.']);
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

    $conn->begin_transaction();

    try {
        $stmt = $conn->prepare("INSERT INTO bookings (event_id, user_id, name, email, phone) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iisss", $eventId, $_SESSION['userId'], $name, $email, $phone);

        if ($stmt->execute()) {
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

            $conn->commit();

            echo json_encode(['success' => 'Booking successful']);
        } else {
            $conn->rollback();
            echo json_encode(['error' => 'Error: ' . $stmt->error]);
        }

        $stmt->close();
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['error' => 'Error: ' . $e->getMessage()]);
    }

    $conn->close();
} else {
    echo json_encode(['error' => 'User not logged in']);
}
?>
