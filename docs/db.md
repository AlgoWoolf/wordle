# Major Entities
- User: username (PK, varchar, not null), type, password (varchar, not null)
- Admin: username (FK), password (FK)
- Player: username (FK), password (FK), best score guesses (FK) best score date (FK), city (FK)
- Score: word (char[5], not null), guesses (int, not null), date (date), name (FK)
- Leaderboad: position (PK, int, not null), name (FK), guesses (FK)
- Location: country (varchar, not null), city (varchar, not null)