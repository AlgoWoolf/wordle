# HOW TO USE THIS APPLICATION
- Ensure you have xampp installed.
- Copy all the contents of this folder into your ./xampp/htdocs directory, you may remove/replace files already in htdocs.
- Open xampp, then start Apache and MySQL servers.
- Once started, first go to localhost/phpMyAdmin. Copy the text from create_db_command.txt and run it as SQL code.
- You should now be able to play the game at localhost.

# Explanation of Gameplay
- In each session, a random 5-letter word is chosen (but not revealed to the user). 
- The user must then use the digital keyboard to enter their guess, which must be a legitimate word. 
- The user must guess the correct word within 6 guesses to win the game. 
- After each guess, the user will receive hints via the color of each letter tile. 
- If a tile is green, it means the letter matches perfectly with the correct word. If it is yellow, the letter is in the correct word, but in a different spot. If it is gray, the letter does not appear anywhere within the correct word.
- If the user is successful, they can enter their name into the leaderboard and potentially be placed in a position.

- Screenshots of the game in various states are included in the docs folder.