<?php
include("../includes/session.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $department = $_POST['department-name'];
    $event_name = $_POST['event-name'];
    $day = $_POST['day'];
    $time_slot = $_POST['time_slot'];
    $room_name = $_POST['room_name'];

    $sql = "INSERT INTO tbl_events (department, event_name, day, time_slot, room_name) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $department, $event_name, $day, $time_slot, $room_name);
    
    if ($stmt->execute()) {
        $_SESSION['success'] =  "Event assigned successfully!";
        header("location: ../view-assigned-events");
    } else {
        $_SESSION['success'] = "Error: " . $conn->error;
        header("location: ../assign-event-to-department");
    }
}
?>