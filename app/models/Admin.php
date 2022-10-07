<?php

class Admin
{
    private $db;

    public function __construct()
    {
        $this->db = Mysqldb::getInstance()->getDatabase();
    }

    public function verifyUser($data)
    {
        $errors = [];

        $password = hash_hmac('sha512', $data['password'], ENCRIPTKEY);

        $sql = 'SELECT * FROM admin  WHERE email :email';
        $query = $this->db->prepare($sql);
        $query->bindParam(':email',$data['email'],PDO::PARAM_STR);
        $query->execute();
        $admins = $query->fetchAll(PDO::FETCH_OBJ); //es un array


        if ( ! $admins ) {
            array_push($errors, 'El usuario no existe en nuestros registros');
        } elseif (count($admins) > 1) {
            array_push($errors, 'El correo electrónico está duplicado');
        } elseif ($password != $admins[0]->password) {
            array_push($errors, 'La clave de acceso no es correcta');
        } else {
            $sql2 = 'UPDATE admin SET login_at=:login  WHERE id=:id';
            $query2 = $this->db->prepare($sql2);
            $params = [
                ':login' => date('Y-m-d H:i:s'),
                ':id' => $admins[0]->id,
            ];

            if (! $query2->execute($params)) {
                array_push($errors, 'Error al modificar la decha de último acceso');
            }
        }
        return $errors;
    }
}