<?php

namespace app;

class Dictionary
{
    private MyPDO $connection;
    private array $questions;
    
    public function __construct(MyPDO $connection)
    {
        $this->connection = $connection;
        $this->questions = require 'config/questionnaire.php';
    }

    ### GET

    public function isWordExist(string $name): bool
    {
        $name = mb_strtolower($name);

        $query = "
            SELECT COUNT(*) as cnt 
            FROM words 
            WHERE name = :name";

        $result = $this->connection->getRow($query, ['name' => $name]);

        return (bool) $result['cnt'];
    }

    public function getWords(): ?array
    {
        $query = "
            SELECT
                words.id,
                words.name,
                words.translation,
                words.transcription,
                words.description,
                GROUP_CONCAT(examples.example, '&') AS examples
            FROM words INNER JOIN examples 
            ON examples.word_id = words.id
            GROUP BY words.name
            ORDER BY words.name
        ";

        return $this->connection->getRows($query);
    }

    public function getWord(string $name): ?Word
    {
        $query = "
            SELECT
                words.id,
                words.name,
                words.translation,
                words.transcription,
                words.description,
                GROUP_CONCAT(examples.example, '&') AS examples
            FROM words INNER JOIN examples 
            ON examples.word_id = words.id  
            WHERE name = :name
        ";

        $word = $this->connection->getRow($query, ['name' => $name]);

        if (empty($word['name'])) {
            return null;
        }

        return new Word(
            $word['id'],
            $word['name'],
            $word['translation'],
            $word['transcription'],
            $word['description'],
            explode('&', $word['examples'])
        );
    }

    ### SAVE

    public function save(array $word): bool
    {
        list($word, $examples) = array_chunk($word, 4, true);

        $word_id = $this->saveWord($word);

        if (!$word_id) {
            return false;
        }
        return $this->saveExamples($word_id, $examples['examples']);
    }

    public function saveWord(array $word): ?int
    {
        $query = "
            INSERT INTO words (
                name, 
                translation, 
                transcription, 
                description
            )
            VALUES (
                :name, 
                :translation, 
                :transcription, 
                :description
            )";

        return $this->connection->insert($query, $word);
    }

    public function saveExamples(int $word_id, array $examples): bool
    {
        $placeholders = [];
        $values = [];

        foreach ($examples as $example) {
            $placeholders[] = '(?, ?)';
            $values[] = $example;
            $values[] = $word_id;
        }

        $placeholders = implode(',', $placeholders);

        $query = "
            INSERT INTO examples (
                example,
                word_id
            )
            VALUES
                $placeholders
        ";

        return (bool) $this->connection->insert($query, $values);
    }

    ### UPDATE

    public function update(array $word): bool
    {
        if (array_key_exists('examples', $word)) {
            $examples = $word['examples'];
            $this->updateExamples($word['id'], $examples);
            unset($word['examples']);
        }

        if (!empty($word) && !$this->updateWord($word)) {
            return false;
        }

        return true;
    }

    public function updateWord(array $word): bool
    {
        $values = [];

        foreach ($word as $key => $value) {
            $values[] = "$key = :$key";
        }

        $values = implode(', ', $values);

        $query = "
            UPDATE words
            SET $values
            WHERE id = :id             
        ";

        return $this->connection->update($query, $word);
    }

    public function updateExamples(int $word_id, array $examples): bool
    {
        $query = "
            DELETE FROM examples
            WHERE word_id = :word_id;
        ";

        if ($this->connection->delete($query, ['word_id' => $word_id]) &&
            $this->saveExamples($word_id, $examples)) {
            return true;
        }

        return false;
    }

    ### DELETE

    public function delete(int $id): bool
    {
        $query = "
            DELETE FROM words
            WHERE id = :id;
        ";

        return $this->connection->delete($query, ['id' => $id]);
    }

    ### TERMINAL

    public function consoleLoader(): void
    {
        while (true) {
            $word = [];

            for ($i = 0; $i < count($this->questions); $i++) {

                if ($this->questions[$i]['index'] == 'examples') {
                    echo $this->questions[$i]['q'] . PHP_EOL;
                    $line = $this->addWordExamples();
                } else {
                    $line = trim(readline($this->questions[$i]['q']));
                }

                if (empty($line)) {
                    $i--;
                    continue;
                }

                if ($this->questions[$i]['index'] == 'name' && $this->isWordExist($line)) {
                    echo 'Слово уже есть в словаре.' . PHP_EOL;
                    $i--;
                    continue;
                }

                $word[$this->questions[$i]['index']] = $line;
            }

            if (!$this->save($word)) {
                echo 'Ошибка при загрузке слова!';
                break;
            }
        }
    }

    protected function addWordExamples(): array
    {
        $examples = [];

        while(true) {
            $line = trim(readline(' - '));

            if ($line == 'exit') {
                break;
            }

            if (empty($line)) {
                continue;
            }

            if (in_array($line, $examples)) {
                echo 'Пример уже существует.' . PHP_EOL;
                continue;
            }

            $examples[] = $line;
        }

        return $examples;
    }
}
