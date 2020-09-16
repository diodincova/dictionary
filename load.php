<?php

function loadDictionaryWords() {
    $words = json_decode(file_get_contents('words.json'));
    foreach ($words as $word) {
        $word = unserialize($word);
        $word->print();
    }
}
