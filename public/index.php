<?php

use app\MyPDO;
use app\Dictionary;
use app\Renderer;

chdir(dirname(__DIR__));
require_once 'vendor/autoload.php';

$data = 'data/english.db';
$isDBExists = file_exists($data);
$connection = new MyPDO($data);

if (!$isDBExists) {
    $connection->exec(file_get_contents('config/shema.sql'));
}

$dictionary = new Dictionary($connection);
$renderer = new Renderer('app/templates');

$url = parse_url($_SERVER['REQUEST_URI']);
$route = explode('/', $url['path']);
$action = $route[1] ?: 'index';

if ($action == 'index') {
    echo $renderer->render('home.phtml', ['content' => $dictionary->getWords()]);
}

if ($action == 'append') {
    $word = [];
    $message = null;

    if ($_POST) {
        foreach ($_POST as $key => $value) {
            $value = trim($value);

            if (empty($value)) {
                $message = 'Поле "' . $key . '" не заполнено!';
                break;
            }

            if ($key == 'name' && $dictionary->isWordExist($value)) {
                $message = 'Слово "' . $value . '" уже есть в словаре!';
                break;
            }

            if ($key == 'examples') {
                $value = array_map('trim', array_diff(explode('&', $value), ['']));
            }

            $word[$key] = $value;
        }

        if (is_null($message) && $dictionary->save($word)) {
            $message = 'Слово "' . $word['name'] . '" сохранено.';
        }
    }

    echo $renderer->render('append.phtml', ['message' => $message]);
}

if ($action == 'update') {
    $word = [];
    $updatedWord = [];
    $message = null;

    if (!empty($_GET['word'])) {
        $name = $_GET['word'];
        $word = $dictionary->getWord($name);

        if (is_null($word)) {
            $message = 'Слова "' . $name . '" нет в словаре!';
        } else {
            $word = $word->getData();
            $word['examples'] = implode('&', $word['examples']);
        }
    }

    if (is_null($message) && !empty($_POST)) {
        foreach ($_POST as $key => $value) {
            $value = trim($value);

            if (empty($value)) {
                $message = 'Поле "' . $key . '" не заполнено!';
                break;
            }

            if ($key == 'examples') {
                $value = array_map('trim', array_diff(explode('&', $value), ['']));
            }

            $updatedWord[$key] = $value;
        }

        if (is_null($message) && $dictionary->update($updatedWord)) {
            $message = 'Изменения успешно сохранены.';
        }
    }

    echo $renderer->render('update.phtml', [
        'message' => $message,
        'word' => $word,
    ]);
}

if ($action == 'delete') {
    $message = null;

    if (!empty($_GET['id']) && $dictionary->delete($_GET['id'])) {
        $message = 'Слово удалено.';
    }

    echo $renderer->render('update.phtml', ['message' => $message]);
}
