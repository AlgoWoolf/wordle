class Leaderboard {
    constructor() {
        this.entries = [
            ["N/A", 0],
            ["N/A", 0],
            ["N/A", 0],
            ["N/A", 0],
            ["N/A", 0],
        ];
    }

    // Update the leaderboard results
    display() {
        document.getElementById('entry-1').innerHTML = '1. ' + this.entries[0][0] + ' (' + this.entries[0][1] + ' guesses)';
        document.getElementById('entry-2').innerHTML = '2. ' + this.entries[1][0] + ' (' + this.entries[1][1] + ' guesses)';
        document.getElementById('entry-3').innerHTML = '3. ' + this.entries[2][0] + ' (' + this.entries[2][1] + ' guesses)';
        document.getElementById('entry-4').innerHTML = '4. ' + this.entries[3][0] + ' (' + this.entries[3][1] + ' guesses)';
        document.getElementById('entry-5').innerHTML = '5. ' + this.entries[4][0] + ' (' + this.entries[4][1] + ' guesses)';
    }

    // Tries to add a new entry to the leaderboard
    add(name, guesses) {


        for (let i = 0; i < this.entries.length; i++) {
            if (this.entries[i][1] <= 0) {
                this.entries[i][0] = name;
                this.entries[i][1] = guesses;
                return;
            }

            if (guesses < this.entries[i][1]) {
                let tName = this.entries[i][0];
                let tGuesses = this.entries[i][1];
                this.entries[i][0] = name;
                this.entries[i][1] = guesses;
                this.add(tName, tGuesses);
                return;
            }
        }
    }
}