<?php
session_start();

$randomWord = generateRandomWord();
$_SESSION['game'] = [
    'target_word' => $randomWord,
    'attempts' => [],
    'max_attempts' => 6,
    'did_win' => false,
    'did_lose' => false,
];
function generateRandomWord() {
    $words = file("src/text/wordle-answers-alphabetical.txt", FILE_IGNORE_NEW_LINES);
    $randomIndex = array_rand($words);
    return $words[$randomIndex];
}
?>

<!DOCTYPE html>
<html lang = "en">
<head>
    <meta charset = "UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wordle Copycat</title>

    <script src="./src/main.js?v=<?php echo time() ?>"></script>

    <link rel="stylesheet" href="src/css/styles.css?v=<?php echo time() ?>">

</head>
<body>
    <div id="container">
        <div id="game">
            <header>
                <h1 class="title">WORDLE</h1>
            </header>
            <div id="board-container"/>
                <div id="board">
            </div>
            <div id="keyboard-container">
                <div class="keyboard-row">
                  <button data-key="q">q</button>
                  <button data-key="w">w</button>
                  <button data-key="e">e</button>
                  <button data-key="r">r</button>
                  <button data-key="t">t</button>
                  <button data-key="y">y</button>
                  <button data-key="u">u</button>
                  <button data-key="i">i</button>
                  <button data-key="o">o</button>
                  <button data-key="p">p</button>
                </div>
                <div class="keyboard-row">
                  <div class="spacer-half"></div>
                  <button data-key="a">a</button>
                  <button data-key="s">s</button>
                  <button data-key="d">d</button>
                  <button data-key="f">f</button>
                  <button data-key="g">g</button>
                  <button data-key="h">h</button>
                  <button data-key="j">j</button>
                  <button data-key="k">k</button>
                  <button data-key="l">l</button>
                  <div class="spacer-half"></div>
                </div>
                <div class="keyboard-row">
                  <button data-key="enter" class="wide-button">Enter</button>
                  <button data-key="z">z</button>
                  <button data-key="x">x</button>
                  <button data-key="c">c</button>
                  <button data-key="v">v</button>
                  <button data-key="b">b</button>
                  <button data-key="n">n</button>
                  <button data-key="m">m</button>
                  <button data-key="del" class="wide-button">Del</button>
                </div>
            </div>

            <div class="row">

                <div class="col" id="user">
                    <?php include ("src/form_validate.php") ?>
                </div>

                <div class="col" id="leaderboard">
                    <h3 class="title-2">Leaderboard</h3>

                    <div id="leaderboard-entries">
                        1. N/A (? guesses)<br>
                        2. N/A (? guesses)<br>
                        3. N/A (? guesses)<br>
                        4. N/A (? guesses)<br>
                        5. N/A (? guesses)<br>
                        6. N/A (? guesses)<br>
                        7. N/A (? guesses)<br>
                        8. N/A (? guesses)<br>
                        9. N/A (? guesses)<br>
                        10. N/A (? guesses)<br>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</body>
</html>