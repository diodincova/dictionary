<?php

class Load
{
    private object $dictionary;
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

    public function __construct()
    {
        $this->dictionary = Dictionary::getInstance();
    }

    public function run(): void
    {              
        while (true) {
            $word = [];
        
            for ($i = 0; $i < count($this->questions); $i++) {
            
                $line = trim(readline($this->questions[$i]['q']));
            
                if (empty($line)) {
                    $i--;
                    continue;
                }   
            
                if ($i == 0 && $this->dictionary->isWordExist($line)) {
                    echo 'Слово уже есть в словаре.' . PHP_EOL;
                    $i--;
                    continue;
                }
                
                if ($this->questions[$i]['index'] == 'examples') {
                    $line = explode('&', $line);
                }
            
                $word[$this->questions[$i]['index']] = $line;
            }
            
            $this->dictionary->setWord($word);
            $this->dictionary->setData();
        }
    }
}
