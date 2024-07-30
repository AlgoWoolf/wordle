<?php

session_start();
if(!$_SESSION['game']['did_win']) {
    exit;
}

$leaderboard = $_SESSION['leaderboard'] ?? [];
$name = $_POST['name'];
$score = count($_SESSION['game']['attempts']);

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
}
?>