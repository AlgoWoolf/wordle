<?php

$conn = new mysqli('localhost', 'root', '', 'wordledb');

$sql = "SELECT * FROM status";
$result = $conn->query($sql);

$result = $result->fetch_assoc();
$username = $result['username'];

echo $username;

$conn->close();

?>