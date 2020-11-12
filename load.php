<?php

use app\MyPDO;
use app\Dictionary;

require_once 'vendor/autoload.php';

$data = 'data/english.db';
$isDBExists = file_exists($data);
$connection = new MyPDO($data);

if (!$isDBExists) {
    $connection->exec(file_get_contents('config/shema.sql'));
}

$dictionary = new Dictionary($connection);
$tree = $dictionary->getWord('tree');

if (!is_null($tree)) {
    $tree->print();
}
