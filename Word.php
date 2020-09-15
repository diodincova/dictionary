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
        $word_json = json_encode($this);
        file_put_contents('words/' . $this->name . '.php', $word_json);
    }
}
