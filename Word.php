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
        $words[] = json_encode($this);
        file_put_contents('words.json', json_encode($words));
    }
}
