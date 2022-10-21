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


    public function getProductById($id)
    {
        $sql = 'SELECT * FROM products WHERE id=:id';
        $query = $this->db->prepare($sql);
        $query->execute([':id' => $id]);

        return $query->fetch(PDO::FETCH_OBJ);
    }

    public function updateProduct($data)
    {
        $errors = [];

        $sql = 'UPDATE products SET type=:type, name=:name, description=:description, price=:price, discount=:discount, send=:send, published=:published, relation1=:relation1, relation2=:relation2, relation3=:relation3, mostSold=:mostSold, new=:new, status=:status, deleted=:deleted, updated_at=:updated_at, author=:author, publisher=:publisher, pages=:pages, people=:people, objetives=:objetives, necesites=:necesites';

        $params = [
            ':id'	=> $data['id'],
            ':type' => $data['type'],
            ':name' => $data['name'],
            ':description' => $data['description'],
            ':price' => $data['price'],
            ':discount' => $data['discount'],
            ':send' => $data['send'],
            ':published' => $data['published'],
            ':relation1' => $data['relation1'],
            ':relation2' => $data['relation2'],
            ':relation3' => $data['relation3'],
            ':mostSold' => $data['mostSold'],
            ':new' => $data['new'],
            ':status' => $data['status'],
            ':deleted' => 0,
            ':updated_at' => date('Y-m-d H:i:s'),
            ':author' => $data['author'],
            ':publisher' => $data['publisher'],
            ':pages' => $data['pages'],
            ':people' => $data['people'],
            ':objetives' => $data['objetives'],
            ':necesites' => $data['necesites']
        ];

        if ($data['image']) {
            $sql .= ', image=:image';
            $params[':image'] = $data['image'];
        }

        $sql .= ' WHERE id=:id';

        $query = $this->db->prepare($sql);

        if ( ! $query->execute($params)) {
            array_push($errors, 'Error al modificar el producto');
        }

        return $errors;
    }

    public function delete($id)
    {
        $errors = [];

        $sql = 'UPDATE products SET deleted=:deleted, deleted_at=:deleted_at WHERE id=:id';

        $params = [
            ':id' => $id,
            ':deleted' => 1,
            ':deleted_at' => date('Y-m-d H:i:s'),
        ];

        $query = $this->db->prepare($sql);

        if ( ! $query->execute($params)) {
            array_push($errors, 'Error al borrar el producto');
        }

        return $errors;
    }
}