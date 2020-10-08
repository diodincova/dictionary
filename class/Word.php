<?php

class Word 
{
    private string $name;
    private string $translation;
    private string $transcription;
    private string $description;
    private array $examples;

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

    public function getInfo(): array
    {
        return [
            'name' => $this->name,
            'translation' => $this->translation,
            'transcription' => $this->transcription,
            'description' => $this->description,
            'examples' => $this->examples,
        ];
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
