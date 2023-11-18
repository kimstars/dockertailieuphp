<?php

require_once 'app/Models/Model.php';

class User extends Model
{
    protected $table = 'users';

    protected $fillable = ['id', 'name', 'email', 'class_name', 'phone', 'password', 'created_at', 'updated_at'];

    protected $hidden = ['password'];

    protected $primaryKey = 'id';

    /**
     * web
     * attempt function
     *
     * @param array $data
     * @return array
     */
    public function attempt($data)
    {
        $email = $data['email'];
        $password = $data['password'];
        $this->hidden = [];
        $exist_user = $this->getFirst("SELECT * FROM users WHERE email = '$email'");
        if (!$exist_user) {
            return false;
        }
        $hash_password = $exist_user['password'];
        if (!password_verify($password, $hash_password)) {
            return false;
        }
        return $exist_user;
    }

    /**
     * web
     * emailExists function
     *
     * @param array $data
     * @return array
     */
    public function emailExists($data)
    {
        $email = trim($data['email']);
        $sql = "SELECT * FROM users WHERE email = '$email'";
        return $this->getFirst($sql);
    }

    /**
     * web
     * findByEmailAndId function
     *
     * @param string $email
     * @param string|number $user_id
     * @return array
     */
    public function findByEmailAndId($email, $user_id)
    {
        $email = trim($email);
        $sql = "SELECT * FROM users WHERE email = '$email' and id = '$user_id'";
        return $this->getFirst($sql);
    }

    /**
     * admin
     * all function
     *
     * @return array
     */
    public function all()
    {
        $sql = "SELECT * FROM users";
        return $this->getAll($sql);
    }

    /**
     * admin
     * updateStatus function
     *
     * @param array $data
     * @return void
     */
    public function updateStatus($data)
    {
        $updated_at = date('Y-m-d H:i:s');
        $sql = "UPDATE users SET active='{$data['active']}' ,`updated_at`='$updated_at' WHERE `id`='{$data['id']}'";
        return $this->dbConnection->query($sql);
    }

    // admin
    public function pagination($limit)
    {
        $sql_all_filter = "SELECT * FROM $this->table";
        return $this->paginationBase($limit, $sql_all_filter);
    }

}
