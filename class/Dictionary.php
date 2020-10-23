<?php

class Dictionary
{
    private MyPDO $myPDO;
    private array $questions = [
        [
            'q' => 'Введите слово: ',
            'index' => 'name',
        ],
        [
            'q' => 'Введите его перевод: ',
            'index' => 'translation',
        ],
        [
            'q' => 'Введите его транскрипцию: ',
            'index' => 'transcription',
        ],
        [
            'q' => 'Введите описание слова: ',
            'index' => 'description',
        ],
        [
            'q' => 'Укажите примеры использования слова, разделяя их символом "&": ',
            'index' => 'examples',
        ],
    ];
    
    public function __construct(string $dbname)
    {   
        $this->myPDO = new MyPDO($dbname);
    }

    public function getWord(string $name): ?Word
    {
        $query = "SELECT * FROM dictionary WHERE name = :name";

        $word = $this->myPDO->getRow($query, ['name' => $name]);
        
        if (!$word) {
            return null;
        }
        
        return new Word(
            $word['name'],
            $word['translation'],
            $word['transcription'],
            $word['description'],
            explode('&', $word['examples']),
        );
    }

    public function saveWord(array $word): void
    {
        $query = "INSERT INTO dictionary (name, translation, transcription, description, examples)
                  VALUES (:name, :translation, :transcription, :description, :examples)";          

        $id = $this->myPDO->insert($query, $word);
    }

    public function runLoader(): void
    {
        while (true) {
            $word = [];
        
            for ($i = 0; $i < count($this->questions); $i++) {
                $line = trim(readline($this->questions[$i]['q']));
            
                if (empty($line)) {
                    $i--;
                    continue;
                }   
            
                if ($i == 0 && $this->isWordExist($line)) {
                    echo 'Слово уже есть в словаре.' . PHP_EOL;
                    $i--;
                    continue;
                }
            
                $word[$this->questions[$i]['index']] = $line;
            }

            $this->saveWord($word);
        }
    }

    protected function isWordExist(string $name): bool
    {
        $name = mb_strtolower($name);
        
        $query = "SELECT id FROM dictionary WHERE name = :name";

        $res = $this->myPDO->getRow($query, ['name' => $name]);

        if ($res) {
            return true;
        }

        return false;
    }
}
