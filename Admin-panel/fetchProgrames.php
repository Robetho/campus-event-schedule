<?php 	

include('includes/session.php');

$sql = "SELECT * FROM tbl_programmes";
$result = $conn->query($sql);

$data = $result->fetch_all();

$conn->close();

echo json_encode($data);
