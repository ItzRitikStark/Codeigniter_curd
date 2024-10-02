<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users'; // Your database table name
    protected $allowedFields = ['name', 'email', 'username','image', 'password', 'created_at','updated_at']; // Fields to be inserted

    

}
?>
