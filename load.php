<?php

require_once './class/Word.php';
require_once './class/Dictionary.php';
require_once './class/MyPDO.php';

$dictionary = new Dictionary('english');

$tree = $dictionary->getWord('tree');

if (!is_null($tree)) {
    $tree->print();
}

$house = $dictionary->getWord('house');

if (!is_null($house)) {
    $house->print();
}
