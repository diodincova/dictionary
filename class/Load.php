<?php

class Load
{
    public function getDictionary(): array
    {
        return json_decode(file_get_contents('data/words.json'), true);
    }

    public function getWord(string $name): Word
    {
        $dictionary = $this->getDictionary();
        $name = mb_strtolower($name);

        if (isset($dictionary[$name])) {
            return new Word(
                $dictionary[$name]['name'],
                $dictionary[$name]['translation'],
                $dictionary[$name]['transcription'],
                $dictionary[$name]['description'],
                $dictionary[$name]['examples'],
            );
        }
    }
}
