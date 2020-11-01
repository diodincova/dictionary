CREATE TABLE IF NOT EXISTS dictionary (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT UNIQUE NOT NULL,
    translation TEXT NOT NULL,
    transcription TEXT NOT NULL,
    description TEXT NOT NULL,
    examples TEXT NOT NULL
);