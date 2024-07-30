<?php

$conn = new mysqli('localhost', 'root', '', 'wordledb');

$sql = "SELECT username, score FROM scores ORDER BY score ASC, timestamp ASC LIMIT 10";
$result = $conn->query($sql);

$index = 1;
while ($entry = $result->fetch_assoc()) {
    echo strval($index) . '. ' . $entry['username'] . ' - ' . $entry['score'] . "\n";
    $index++;
}

$conn->close();


/*session_start();
$leaderboard = $_SESSION['leaderboard'] ?? [];

// Foreach element in leaderboard, echo the name and score.
$index = 1;
foreach($leaderboard as $entry) {
    echo strval($index) . '. ' . $entry['name'] . ' (' . $entry['score'] . " guess". ((int)($entry['score'] >= 2) ? "es" : "") . ")\n";
    $index++;
}*/
?>