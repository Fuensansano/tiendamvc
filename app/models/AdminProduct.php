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

    public function createProduct($data)
    {
        $sql = 'INSERT INTO products(type, name, description, price, discount, send, image, published, relation1, relation2, relation3, mostSold, new, status, deleted, create_at, updated_at, deleted_at, author, publisher, pages, people, objetives, necesites) 
                VALUES (:type, :name, :description, :price, :discount, :send, :image, :published, :relation1, :relation2, :relation3, :mostSold, :new, :status, :deleted, :create_at, :updated_at, :deleted_at, :author, :publisher, :pages, :people, :objetives, :necesites)';
        var_dump($data);
        $params = [
            ':type' => $data['type'],
            ':name' => $data['name'],
            ':description' => $data['description'],
            ':price' => $data['price'],
            ':discount' => $data['discount'],
            ':send' => $data['send'],
            ':image' => $data['image'],
            ':published' => $data['published'],
            ':relation1' => $data['relation1'],
            ':relation2' => $data['relation2'],
            ':relation3' => $data['relation3'],
            ':mostSold' => $data['mostSold'],
            ':new' => $data['new'],
            ':status' => $data['status'],
            ':deleted' => ProductDeleteState::NOT_DELETED->value,
            ':create_at' => date('Y-m-d H:i:s'),
            ':updated_at' => null,
            ':deleted_at' => null,
            ':author' => $data['author'],
            ':publisher' => $data['publisher'],
            ':pages' => $data['pages'],
            ':people' => $data['people'],
            ':objetives' => $data['objetives'],
            ':necesites' => $data['necesites']
        ];

        return $this->queryBuilder->query($sql, $params, MysqlReturnTypes::COUNT);
    }
}