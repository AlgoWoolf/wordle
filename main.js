var documentsLoaded = 0;
var squares = [];
var rowIndex = 0;
var letterIndex = 0;
var letterToKey = {};
var letterState = {}


function setTileColor(type, letter, index) {

    // Get color based on type
    let color = "";
    switch(type) {
        case 1:
            color = "rgb(39 39 39)"; 
            break;
        case 2:
            color = "rgb(181, 159, 59)";
            break;
        case 3:
            color = "rgb(83, 141, 78)";
            break;
    }
    
    // Update color of square
    squares[rowIndex + index].style.backgroundColor = color;

    // Update state of key if it's been changed (e.g. yellow to green)
    if(letterState[letter] < type) {
        letterState[letter] = type;
        letterToKey[letter].style.backgroundColor = color;
    }
}

function getCurrentWord () {
    let currentWord = "";
    for(let i = 0; i < 5; i++) {
        currentWord += squares[rowIndex + i].innerHTML;
    }
    return currentWord;
}

function sendScoreName (guessCount) {
    let name = prompt("You won in " + guessCount + " guesses! Enter your name:", "Anonymous");

    let params = new URLSearchParams();
    params.append('name', name);

    fetch('./send_leaderboard_name.php', {
        method: 'POST',
        body: params,
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        }
    })
    .then(response => response.text())
    .then(data => {
        document.getElementById("leaderboard-entries").innerText = data;
    })
    .catch(error => console.error('Error:', error));
}

function updateScore (score) {
    fetch('./get_leaderboard.php')
    .then(response => response.text())
    .then(data => {
        document.getElementById("leaderboard-entries").innerText = data;
    })
    .catch(error => console.error('Error:', error));
}


function sendGuess() {
    let guess = getCurrentWord();

    fetch("./try_word.php", {
        method: 'post',
        body: JSON.stringify({
            'guess': guess
        }),
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error, status = ${response.status}`);
            }
            return response.text(); // First get the raw text
        })
        .then(text => {
            try {
                return JSON.parse(text); // Try to parse it as JSON
            } catch (error) {
                throw new Error(`Failed to parse JSON: ${error} - Received text: ${text}`);
            }
        })
        .then(data => {
            if(!data.isValidRequest) return;

            if(data.isValidWord) {
                for(let i = 0; i < data.letters.length; i++) {
                    setTileColor(data.letters[i].type, data.letters[i].value, i);
                }
                letterIndex = 0;
                rowIndex += 5;

                if(data.didWin) {
                    sendScoreName(data.guessCount);
                }
                if(data.didLose) {
                    alert("You lose!");
                }
            }
            else {
                for(let i = 0; i < 5; i++) {
                    squares[rowIndex + i].innerHTML = "";
                }
                letterIndex = 0;
                alert("Invalid word");
                return;
            }
        });
}

document.addEventListener("DOMContentLoaded", () => {

    const gameBoard = document.getElementById("board");
    for(let i = 0; i < 30; i++)
    {
        let square = document.createElement("div");
        square.classList.add("square");
        square.setAttribute ("id", i+1);
        gameBoard.appendChild(square);
        squares.push(square);
    }

    const keys = document.querySelectorAll(".keyboard-row button");
    keys.forEach(key => {
        letterToKey[key.innerHTML] = key;
        letterState[key.innerHTML] = 0;
        key.addEventListener("click", () => {
            let letter = key.innerHTML;
            
            // Erase current letter
            if(letter == "Del") {
                if(letterIndex == 0) return;

                letterIndex--;
                squares[rowIndex + letterIndex].innerHTML = "";
            }

            // Process row if it's full
            else if(letter == "Enter") {
                if(letterIndex != 5 || rowIndex >= 30) return;
                
                sendGuess();
            }

            // Add letter to row
            else {
                if(letterIndex == 5) return;
                
                squares[rowIndex + letterIndex].innerHTML = letter;
                letterIndex++;
            }
        });
    });

    updateScore();
})



