var validWordSet = new Set();
var documentsLoaded = 0;
var squares = [];
var word = "";
var rowIndex = 0;
var letterIndex = 0;

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

function getTileColor(currentWord, index) {
    let letter = currentWord[index];
    const isCorrectLetter = word.includes(letter);

    if (!isCorrectLetter) {
        return "rgb(58, 58, 60)";
    }

    const letterInThatPosition = word.charAt(index);
    const isCorrectPosition = letter === letterInThatPosition;

    if (isCorrectPosition) {
        return "rgb(83, 141, 78)";
    }

    return "rgb(181, 159, 59)";
}

function setTileColor(currentWord, letterIndex) {
    squares[letterIndex + rowIndex].style.backgroundColor = getTileColor(currentWord, letterIndex);
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
        key.addEventListener("click", () => {
            let letter = key.innerHTML;
            
            if(letter == "Del") {
                if(letterIndex == 0) return;

                letterIndex--;
                squares[rowIndex + letterIndex].innerHTML = "";
            }
            else if(letter == "Enter") {
                if(letterIndex != 5) return;
                
                let currentWord = getCurrentWord();

                if(!validWordSet.has(currentWord)) {
                    for(let i = 0; i < 5; i++) {
                        squares[rowIndex + i].innerHTML = "";
                    }
                    letterIndex = 0;
                    alert("Invalid word");
                    return;
                }

                // Onto next guess
                for(let i = 0; i < 5; i++) {
                    setTileColor(currentWord, i);
                }

                if(currentWord == word) {
                    alert("You win!");
                }

                letterIndex = 0;
                rowIndex += 5;
            } 
            else {
                if(letterIndex == 5) return;
                
                squares[rowIndex + letterIndex].innerHTML = letter;
                letterIndex++;
            }
        });
    });

    loadData();
})

