<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "event_management";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
 
    echo json_encode(['error' => 'Connection failed: ' . $conn->connect_error]);
    exit(); 
}

$sql = "SELECT * FROM events";
$result = $conn->query($sql);

$events = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
         
         $startDateTime = strtotime($row['start_date'] . ' ' . $row['start_time']);
         $start = date('M j, Y g:i A', $startDateTime);
 
         $endDateTime = strtotime($row['end_date']);
         $end = date('M j, Y', $endDateTime);

        $events[] = array(
            'id' => $row['id'],
            'title' => $row['name'],
            'start' => $start,
            'end' => $end,
            'description_text' => $row['description'],
            'image' => $row['thumbnail'],
            'participants' => $row['participant_count'],
            'prize_pool' => $row['prizepool']
        );
    }
} else {

    $events = array();
}

echo json_encode($events);

$conn->close();
?>
