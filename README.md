# Explanation of Gameplay
- In each game session, a random 5-letter word is chosen (but not revealed to the user). The user must then use the digital keyboard to enter their guess, which must be also be legitimate 5-letter word. The user must guess the correct word within 6 guesses to win the game.
- After each guess, the user will receive hints via the color of each letter tile. If a tile is green, it means the letter matches perfectly with the correct word. If it is yellow, the letter is in the correct word, but in a different spot. If it is gray, the letter does not appear anywhere within the correct word.
- If the user fails to guess the word within 6 guesses, they see a message proclaiming their loss.

# Notes about Assignment 3 Implementation
- The system has been updated to use PHP.
- There is now a leaderboard. Once the user correctly guesses the word, they are asked to enter their username, which is entered into the leaderboard along with the amount of guesses it took for them to get the correct word.
- In previous versions, the user could inspect the page in order to cheat and see what the correct word was. With the addition of PHP, this is no longer possible.
- Screenshots of the game in various states are included in the docs folder.