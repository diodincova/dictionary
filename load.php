<?php

require_once './class/Word.php';
require_once './class/Dictionary.php';
require_once './class/MyPDO.php';

$connection = new MyPDO('data/english.db');
$dictionary = new Dictionary($connection);

$tree = $dictionary->getWord('tree');

if (!is_null($tree)) {
    $tree->print();
}

$house = $dictionary->getWord('house');

if (!is_null($house)) {
    $house->print();
}
