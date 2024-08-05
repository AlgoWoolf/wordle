<?php

$conn = new mysqli('localhost', 'root', '', 'wordledb');
$sql = "DELETE FROM scores";
$conn->query($sql);
$conn->close();

?>