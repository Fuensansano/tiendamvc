<?php

class Admin
{
    private $db;

    public function __construct()
    {
        $this->db = Mysqldb::getInstance()->getDatabase();
    }

    public function verifyUser($email,$password)
    {
        $errors = [];

        $password = hash_hmac('sha512', $password, ENCRIPTKEY);

        $admins = $this->findUserByEmail($email);

        if ( ! $admins ) {
            $errors[] = 'El usuario no existe en nuestros registros';
        } elseif (count($admins) > 1) {
            $errors[] ='El correo electrónico está duplicado';
        } elseif ($password != $admins[0]->password) {
            $errors[] = 'La clave de acceso no es correcta';
        } else {

            $errors = $this->updateLastLogin($admins[0], $errors);
        }
        return $errors;
    }


    public function findUserByEmail($email): array|false
    {
        $sql = 'SELECT * FROM admins WHERE email=:email';
        $query = $this->db->prepare($sql);
        $query->bindParam(':email',$email,PDO::PARAM_STR);
        $query->execute();
        $admins = $query->fetchAll(PDO::FETCH_OBJ);
        return $admins;
    }


    public function updateLastLogin($admins, $errors): array
    {
        $sql2 = 'UPDATE admins SET login_at=:login WHERE id=:id';
        $query2 = $this->db->prepare($sql2);
        $params = [
            ':login' => date('Y-m-d H:i:s'),
            ':id' => $admins->id,
        ];
        if (!$query2->execute($params)) {
            $errors[] = 'Error al modificar la fecha de último acceso';
        }
        return $errors;
    }
}