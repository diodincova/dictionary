<?php

class MyPDO extends PDO
{
    public function __construct(string $database)
    {
        parent::__construct('sqlite:' . $database);
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
        return $this->lastInsertId();
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
        $stmt = $this->prepare($query);
        $stmt->execute($args);

        return $stmt;
    }
}
