<?php

require_once './class/Word.php';
require_once './class/Dictionary.php';

$dictionary = new Dictionary('data/words.json');

$tree = $dictionary->getWord('tree');

if (!is_null($tree)) {
    $tree->print();
}
