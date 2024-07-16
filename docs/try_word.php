<?php

session_start();    

if($_SESSION['game']['did_win'] || $_SESSION['game']['did_lose']) {
    echo json_encode([
        'isValidRequest' => false
    ]);
    exit;
}

$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);
$word = $data['guess'];
$targetWord = $_SESSION['game']['target_word'];

$validWords = array_flip(file("valid-wordle-words.txt", FILE_IGNORE_NEW_LINES));


if(!isset($validWords[$word])) {
    echo json_encode([
        'isValidRequest' => true,
        'isValidWord' => false
    ]);
    exit;
}
$reply = [
    'isValidRequest' => true,
    'isValidWord' => true,
    'letters' => []
];

// Type 0: Incorrect letter
// Type 1: Correct letter, wrong position
// Type 2: Correct letter, correct position

$length = strlen($word);
for ($i = 0; $i < $length; $i++){
    $letter = $word[$i];
    if(strpos($targetWord, $letter) === false) {
        $reply['letters'][] = ['value' => $letter, 'type' => 1];
    }
    else if($targetWord[$i] === $letter) {
        $reply['letters'][] = ['value' => $letter, 'type' => 3];
    }
    else {
        $reply['letters'][] = ['value' => $letter, 'type' => 2];
    }
}

$_SESSION['game']['attempts'][] = $word;

$didWin = $word === $targetWord;
$guessCount = count($_SESSION['game']['attempts']);
$ranOutOfGuesses = $guessCount >= $_SESSION['game']['max_attempts'];

$reply['didWin'] = $didWin;
$reply['didLose'] = $ranOutOfGuesses && !$didWin;
$reply['guessCount'] = $guessCount;
$_SESSION['game']['did_win'] = $didWin;
$_SESSION['game']['did_lose'] = $ranOutOfGuesses && !$didWin;

echo json_encode($reply);
?>