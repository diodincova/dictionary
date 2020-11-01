<?php

require_once './class/Word.php';
require_once './class/Dictionary.php';
require_once './class/MyPDO.php';

$data = 'data/english.db';

$isDBExists = file_exists($data);

$connection = new MyPDO($data);

if (!$isDBExists) {
    $connection->exec(file_get_contents('config/shema.sql'));
}

$dictionary = new Dictionary($connection);

$dictionary->runLoader();
