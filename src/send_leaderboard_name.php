<?php

session_start();
if(!$_SESSION['game']['did_win']) {
    exit;
}

$conn = new mysqli('localhost', 'root', '', 'wordledb');

// Prepare parameters
$name = $conn->real_escape_string($_POST['name']);
$score = count($_SESSION['game']['attempts']);

// Insert the new score into the database.
$stmt = $conn->prepare("INSERT INTO scores (username, score) VALUES (?, ?) ON DUPLICATE KEY UPDATE score = VALUES(score), timestamp = NOW()");
$stmt->bind_param("si", $name, $score);  // "si" denotes string and integer types
$stmt->execute();
$stmt->close();

$sql = "SELECT username, score FROM scores ORDER BY score ASC, timestamp ASC LIMIT 10";
$result = $conn->query($sql);

$index = 1;
while ($entry = $result->fetch_assoc()) {
    echo strval($index) . '. ' . $entry['username'] . ' - ' . $entry['score'] . "\n";
    $index++;
}

$conn->close();

/*$leaderboard = $_SESSION['leaderboard'] ?? [];
$name = $_POST['name'];


// Add the new score to the leaderboard.
$leaderboard[] = [
    'name' => $name,
    'score' => $score
];

// Sort the leaderboard by score.
usort($leaderboard, function($a, $b) {
    return $a['score'] - $b['score'];
});

// Keep only the top 10 scores.
$leaderboard = array_slice($leaderboard, 0, 10);

$_SESSION['leaderboard'] = $leaderboard;

$index = 1;
foreach($leaderboard as $entry) {
    echo strval($index) . '. ' . $entry['name'] . ' - ' . $entry['score'] . "\n";
    $index++;
}*/


?>