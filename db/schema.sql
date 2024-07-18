-- Create the User table
CREATE TABLE User (
    username VARCHAR(20) NOT NULL PRIMARY KEY,
    type VARCHAR(10),
    password VARCHAR(30) NOT NULL
);

-- Create the Admin table
CREATE TABLE Admin (
    username VARCHAR(20) NOT NULL,
    password VARCHAR(30) NOT NULL,
    FOREIGN KEY (username, password) REFERENCES User(username, password)
);

-- Create the Player table
CREATE TABLE Player (
    username VARCHAR(20) NOT NULL,
    password VARCHAR(30) NOT NULL,
    best_score_guesses INT,
    best_score_date DATE,
    city VARCHAR(255) NOT NULL,
    FOREIGN KEY (username, password) REFERENCES User(username, password),
    FOREIGN KEY (best_score_guesses, best_score_date, username) REFERENCES Score(guesses, date, name),
    FOREIGN KEY (city) REFERENCES Location(city)
);

-- Create the Score table
CREATE TABLE Score (
    word CHAR(5) NOT NULL,
    guesses INT NOT NULL,
    date DATE,
    name VARCHAR(20),
    FOREIGN KEY (name) REFERENCES Player(username),
    UNIQUE (word, date, name)
);

-- Create the Leaderboard table
CREATE TABLE Leaderboard (
    position INT NOT NULL PRIMARY KEY,
    name VARCHAR(20),
    guesses INT,
    date DATE,
    FOREIGN KEY (name) REFERENCES Player(username),
    FOREIGN KEY (guesses, date, name) REFERENCES Score(guesses, date, name)
);

-- Create the Location table
CREATE TABLE Location (
    country VARCHAR(255) NOT NULL,
    city VARCHAR(255),
    PRIMARY KEY (country, city)
);


-- Sample insert statements

INSERT INTO User (username, type, password) VALUES 
('admin1', 'admin', 'adminpass1'),
('player1', 'player', 'playerpass1'),
('player2', 'player', 'playerpass2');

INSERT INTO Admin (username, password) VALUES 
('admin1', 'adminpass1');

INSERT INTO Location (country, city) VALUES 
('USA', 'New York'),
('Canada', 'Toronto');

INSERT INTO Player (username, password, best_score_guesses, best_score_date, city) VALUES 
('player1', 'playerpass1', 3, '2024-07-01', 'New York'),
('player2', 'playerpass2', 5, '2024-07-02', 'Toronto');

INSERT INTO Score (word, guesses, date, name) VALUES 
('apple', 3, '2024-07-01', 'player1'),
('swain', 5, '2024-07-02', 'player2'),
('berry', 4, '2024-07-03', 'player1');

INSERT INTO Leaderboard (name, guesses, date) VALUES 
('player1', 3, '2024-07-01'),
('player2', 5, '2024-07-02'),
('player1', 4, '2024-07-03');
