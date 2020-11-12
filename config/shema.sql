PRAGMA foreign_keys = ON;

CREATE TABLE IF NOT EXISTS words
(
    id            INTEGER PRIMARY KEY AUTOINCREMENT,
    name          TEXT NOT NULL,
    translation   TEXT NOT NULL,
    transcription TEXT NOT NULL,
    description   TEXT NOT NULL
);

CREATE
    UNIQUE INDEX idx_words_name
    ON words (name);

CREATE
    INDEX idx_words_translation
    ON words (translation);

CREATE TABLE IF NOT EXISTS examples
(
    id      INTEGER PRIMARY KEY AUTOINCREMENT,
    example TEXT    NOT NULL,
    word_id INTEGER NOT NULL,
    FOREIGN KEY (word_id) REFERENCES words (id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
);