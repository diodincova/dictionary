<?php

class Word 
{
    public string $name;
    public string $translation;
    public string $transcription;
    public string $description;
    public array $examples;

    public function __construct(
        string $name, 
        string $translation, 
        string $transcription, 
        string $description, 
        array $examples
    ) {
        $this->name = $name;
        $this->translation = $translation;
        $this->transcription = $transcription;
        $this->description = $description;
        $this->examples = $examples;
    }

    public function save(): void
    {
        $dictionary = json_decode(file_get_contents('data/words.json'), true);
        
        if (isset($dictionary[$this->name])) {
            return;
        }

        $dictionary[$this->name] = [
            'name' => $this->name,
            'translation' => $this->translation,
            'transcription' => $this->transcription,
            'description' => $this->description,
            'examples' => $this->examples,
        ];

        file_put_contents('data/words.json', json_encode($dictionary, JSON_UNESCAPED_UNICODE));
    }

    public function print(): void
    {
        echo 'name: ' . $this->name . PHP_EOL;
        echo 'translation: ' . $this->translation . PHP_EOL;
        echo 'transcription: ' . $this->transcription . PHP_EOL;
        echo 'description: ' . $this->description . PHP_EOL;
        echo 'examples: ' . PHP_EOL;

        foreach($this->examples as $example) {
            echo $example . PHP_EOL;
        }
    }
}
