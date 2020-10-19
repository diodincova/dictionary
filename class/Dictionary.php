<?php

class Dictionary
{
    private string $file;
    private array $data = [];
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
    
    public function __construct(string $file)
    {
        $this->file = $file;

        if (file_exists($file)) {
            $this->data = json_decode(file_get_contents($file), true);
        }
    }

    public function saveDictionary(): void
    {
        file_put_contents($this->file, json_encode($this->data, JSON_UNESCAPED_UNICODE));
    }

    public function getWord(string $name): ?Word
    {
        if (!$this->isWordExist($name)) {
            return null;
        }

        return new Word(
            $this->data[$name]['name'],
            $this->data[$name]['translation'],
            $this->data[$name]['transcription'],
            $this->data[$name]['description'],
            $this->data[$name]['examples'],
        );
    }

    public function saveWord(array $word): void
    {
        $name = $word['name'];
        $this->data[$name] = $word;

        $this->saveDictionary();
    }

    public function runLoader()
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
                
                if ($this->questions[$i]['index'] == 'examples') {
                    $line = explode('&', $line);
                }
            
                $word[$this->questions[$i]['index']] = $line;
            }
            
            $this->saveWord($word);
        }
    }

    protected function isWordExist(string $name): bool
    {
        $name = mb_strtolower($name);

        if (array_key_exists($name, $this->data)) {
            return true;
        }

        return false;
    }
}
