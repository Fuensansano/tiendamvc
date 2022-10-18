<?php

class AdminProduct
{
    private $db;
    private $queryBuilder;

    public function __construct()
    {
        $this->db = Mysqldb::getInstance()->getDatabase();
        $this->queryBuilder = MySQL::fromPDOConnection($this->db);
    }

    public function getProducts()
    {
        $sql = 'SELECT * FROM products WHERE deleted=:deleted';
        $params = [
            ':deleted' => ProductDeleteState::NOT_DELETED->value,
        ];
        return $this->queryBuilder->query($sql, $params);
    }

    public function getConfig($type)
    {
        $sql = 'SELECT * FROM config WHERE type=:type ORDER BY value';
        $params = [':type' => $type];
        return $this->queryBuilder->query($sql, $params);
    }

    public function getCatalogue()
    {
        $sql = 'SELECT id, name, type FROM products WHERE deleted=:deleted AND status!=:status ORDER BY type, name';
        $params = [
            ':deleted' => ProductDeleteState::NOT_DELETED->value,
            ':status' => ProductActiveStatus::INACTIVE->value,
        ];
        return $this->queryBuilder->query($sql, $params);
    }
}