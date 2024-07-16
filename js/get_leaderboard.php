<?php

session_start();
$leaderboard = $_SESSION['leaderboard'] ?? [];

// Foreach element in leaderboard, echo the name and score.
$index = 1;
foreach($leaderboard as $entry) {
    echo strval($index) . '. ' . $entry['name'] . ' (' . $entry['score'] . " guess". ((int)($entry['score'] >= 2) ? "es" : "") . ")\n";
    $index++;
}
?>