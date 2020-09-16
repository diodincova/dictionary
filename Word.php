<?php

class Word 
{
    /** string $name **/
    public $name;
    /** string $translation **/
    public $translation;
    /** string $transcription **/
    public $transcription;
    /** string $description **/
    public $description;
    /** array $examples **/
    public $examples;

    public function save() {
        $words = json_decode(file_get_contents('words.json'));
        $words[] = serialize($this);
        file_put_contents('words.json', json_encode($words));
    }

    public function print() {
        echo 'name: ' . $this->name . PHP_EOL;
        echo 'translation: ' . $this->transcription . PHP_EOL;
        echo 'transcription: ' . $this->translation . PHP_EOL;
        echo 'description: ' . $this->description . PHP_EOL;
        echo 'examples: ' . PHP_EOL;
        foreach($this->examples as $example) {
            echo $example . PHP_EOL;
        }
    }
}
