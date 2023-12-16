<?php
namespace App\Models;
use CodeIgniter\Model;
class AdminModel extends Model
{
    protected $table = 'admin';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_name', 'email','password','status'];
    // function save_record($data) {
    //      $this->insert($data);
    //  }


    
}