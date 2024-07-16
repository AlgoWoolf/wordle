<?php
session_start();

header('Content-Type: application/json');

function getGameState() {
    return [
        'target_word' => $_SESSION['game']['target_word'],
        'attempts' => $_SESSION['game']['attempts'],
        'max_attempts' => $_SESSION['game']['max_attempts']
    ];
}

function makeGuess($guess) {
    if (count($_SESSION['game']['attempts']) < $_SESSION['game']['max_attempts']) {
        $_SESSION['game']['attempts'][] = $guess;
        if ($guess === $_SESSION['game']['target_word']) {
            updateLeaderboard(count($_SESSION['game']['attempts']));
        }
    }
    return getGameState();
}

function updateLeaderboard($score) {
    $_SESSION['leaderboard'][] = $score;
    $_SESSION['leaderboard'] = array_slice(array_unique($_SESSION['leaderboard']), 0, 10);
    sort($_SESSION['leaderboard']);
}

$action = $_GET['action'] ?? null;

switch ($action) {
    case 'get_state':
        echo json_encode(getGameState());
        break;
    case 'make_guess':
        $guess = $_POST['guess'] ?? '';
        echo json_encode(makeGuess($guess));
        break;
    case 'get_leaderboard':
        echo json_encode($_SESSION['leaderboard']);
        break;
    default:
        echo json_encode(['error' => 'Invalid action']);
        break;
}

