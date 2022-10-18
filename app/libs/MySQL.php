<?php

class MySQL
{
    private PDO $connection;

    private function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public static function fromPDOConnection(PDO $connection): self
    {
        return new self($connection);
    }

    public function query($sql, $params, $typeOfReturn = MysqlReturnTypes::ALL)
    {
        $query = $this->connection->prepare($sql);
        $query->execute($params);

        return match ($typeOfReturn) {
            MysqlReturnTypes::ALL => $query->fetchAll(PDO::FETCH_OBJ),
            MysqlReturnTypes::ONE =>  $query->fetch(PDO::FETCH_OBJ),
            MysqlReturnTypes::BOOLEAN => $query->fetch(PDO::FETCH_OBJ) !== 0,
            MysqlReturnTypes::COUNT => $query->rowCount(),
        };
    }
}