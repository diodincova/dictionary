<?php

class Dictionary
{
    private static $instance = null;
    private string $path = './data/words.json';
    private array $data;
    
    private function __construct() {
        $this->data = json_decode(file_get_contents($this->path), true);
    }

    public static function getInstance() {
        if (is_null(self::$instance)) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(): void
    {
        file_put_contents($this->path, json_encode($this->data, JSON_UNESCAPED_UNICODE));
    }

    public function getWord(string $name): Word
    {
        if (!$this->isWordExist($name)) {
            return null;
        }

        return new Word(
            $dictionary[$name]['name'],
            $dictionary[$name]['translation'],
            $dictionary[$name]['transcription'],
            $dictionary[$name]['description'],
            $dictionary[$name]['examples'],
        );
    }

    public function setWord(array $word): void
    {
        $name = $word['name'];
        $this->data[$name] = $word;
    }

    public function isWordExist(string $name): bool
    {
        $name = mb_strtolower($name);

        if (array_key_exists($name, $this->data)) {
            return true;
        }

        return false;
    }

    private function __clone() {}

    private function __wakeup() {}
}