<?php

class Admin
{
    private $db;

    public function __construct()
    {
        $this->db = Mysqldb::getInstance()->getDatabase();
    }

    public function verifyUser($admin,$password)
    {
        $errors = [];

        $password = hash_hmac('sha512', $password, ENCRIPTKEY);

        if ($password != $admin->password) {
            $errors[] = 'La clave de acceso no es correcta';
        } else {
            $errors = $this->updateLastLogin($admin, $errors);
        }
        return $errors;
    }


    public function findAdminByEmail($email)
    {
        $sql = 'SELECT * FROM admins WHERE email=:email';
        $query = $this->db->prepare($sql);
        $query->bindParam(':email',$email,PDO::PARAM_STR);
        $query->execute();
        return $query->fetch(PDO::FETCH_OBJ);
    }


    public function updateLastLogin($admin, $errors): array
    {
        $sql2 = 'UPDATE admins SET login_at=:login WHERE id=:id';
        $query2 = $this->db->prepare($sql2);
        $params = [
            ':login' => date('Y-m-d H:i:s'),
            ':id' => $admin->id,
        ];
        if (!$query2->execute($params)) {
            $errors[] = 'Error al modificar la fecha de último acceso';
        }
        return $errors;
    }
}