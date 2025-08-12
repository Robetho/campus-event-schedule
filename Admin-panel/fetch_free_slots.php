<?php
include('includes/session.php');

$day = $_GET['day'];
$time_slots = ["7:00-9:00", "9:00-11:00", "11:00-13:00", "13:00-15:00", "15:00-17:00"];

$free_slots = [];
$free_rooms = [];

// Tafuta time slots ambazo hazitumiki kwa siku hiyo
foreach ($time_slots as $slot) {
    $sql = "SELECT * FROM tbl_programme_timetable WHERE day = '$day' AND time_slot = '$slot'";
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
        $free_slots[] = $slot;
    }
}

// Tafuta vyumba vyote
$sql = "SELECT room_name FROM tbl_rooms";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Angalia kama chumba hakijatumika katika siku hiyo
        $sql_check = "SELECT * FROM tbl_programme_timetable WHERE day = '$day' AND room_name = '{$row['room_name']}'";
        $check_result = $conn->query($sql_check);

        if ($check_result->num_rows == 0) {
            $free_rooms[] = $row['room_name'];
        }
    }
}

echo json_encode(["time_slots" => $free_slots, "rooms" => $free_rooms]);
?>