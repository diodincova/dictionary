<?php

require_once './class/Word.php';
require_once './class/Dictionary.php';
require_once './class/MyPDO.php';

try {
    $dictionary = new Dictionary('english');
    $dictionary->runLoader();
} catch (PDOException $e) {
    echo $e->getMessage();
}
