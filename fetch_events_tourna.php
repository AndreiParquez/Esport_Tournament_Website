<?php

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

$sql = "SELECT * FROM events";
$result = $conn->query($sql);

$events = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
         // Convert start date and time to readable format
         $startDateTime = strtotime($row['start_date'] . ' ' . $row['start_time']);
         $start = date('M j, Y g:i A', $startDateTime);
 
         // Convert end date to include only the year
         $endDateTime = strtotime($row['end_date']);
         $end = date('M j, Y', $endDateTime);

        $events[] = array(
            'id' => $row['id'],
            'title' => $row['name'],
            'start' => $start,
            'end' => $end,
            'description_text' => $row['description'],
            'image' => $row['thumbnail'],
            'participants' => $row['participant_count']
        );
    }
} else {
    // No events found, return an empty array
    $events = array();
}

// Return events in JSON format
echo json_encode($events);

$conn->close();
?>
