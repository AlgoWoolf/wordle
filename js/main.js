var validWordSet = new Set();
var documentsLoaded = 0;
var squares = [];
var word = "";
var rowIndex = 0;
var letterIndex = 0;
var letterToKey = {};
var letterState = {}

function getRandomItem(array) {
    return array[Math.floor(Math.random() * array.length)];
}

function loadData() {
    validWordList.forEach(value => {
        validWordSet.add(value);
    });
    word = getRandomItem(possibleWordList);
    console.log(word);
}

function setTileColor(currentWord, index) {
    let letter = currentWord[index];
    let color = "";
    let newState = 0;
    const isCorrectLetter = word.includes(letter);

    // Find proper key color
    if (!isCorrectLetter) {
        color = "rgb(39 39 39)";
        newState = 1;
    }
    else {
        const letterInThatPosition = word.charAt(index);
        const isCorrectPosition = letter === letterInThatPosition;
    
        if (isCorrectPosition) {
            color = "rgb(83, 141, 78)";
            newState = 3;
        }
        else {
            color = "rgb(181, 159, 59)";
            newState = 2;
        }
    }
    
    // Update color of square
    squares[rowIndex + index].style.backgroundColor = color;

    // Update state of key if it's been changed (e.g. yellow to green)
    if(letterState[letter] < newState) {
        letterState[letter] = newState;
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
                
                let currentWord = getCurrentWord();

                if(!validWordSet.has(currentWord)) {
                    for(let i = 0; i < 5; i++) {
                        squares[rowIndex + i].innerHTML = "";
                    }
                    letterIndex = 0;
                    alert("Invalid word");
                    return;
                }

                // Update colors from guess
                for(let i = 0; i < 5; i++) {
                    setTileColor(currentWord, i);
                }

                if(currentWord == word) {
                    alert("You win!");
                }

                letterIndex = 0;
                rowIndex += 5;
            }

            // Add letter to row
            else {
                if(letterIndex == 5) return;
                
                squares[rowIndex + letterIndex].innerHTML = letter;
                letterIndex++;
            }
        });
    });

    loadData();
})

