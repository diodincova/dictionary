<?php

require_once './class/Word.php';
require_once './class/Dictionary.php';

$dictionary = new Dictionary('data/words.json');

$dictionary->runLoader();
