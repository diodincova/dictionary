<?php

class MyPDO
{
    private PDO $pdo;

    public function __construct(string $dbname)
    {
        $file = 'data/' . $dbname . '.db';

        if (!file_exists($file)) {
            file_put_contents($file, '');
        }

        $this->pdo = new PDO('sqlite:' . $file, '', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        $this->pdo->exec(file_get_contents('config/shema.sql'));
    }

    public function getRow(string $query, array $args = [])
    {
        return $this->sql($query, $args)->fetch(PDO::FETCH_ASSOC);
    }  

    public function getRows(string $query, array $args = [])
    {
        return $this->sql($query, $args)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert(string $query, array $args = [])
    {
        $this->sql($query, $args);
        return $this->pdo->lastInsertId();
    }       

    public function update(string $query, array $args = [])
    {
        return $this->sql($query, $args)->rowCount();
    }

    public function delete(string $query, array $args = [])
    {
        return $this->sql($query, $args)->rowCount();
    }      

    private function sql(string $query, array $args)
    {
        $stmt = $this->pdo->prepare($query);  
        $stmt->execute($args);

        return $stmt;
    }
}
