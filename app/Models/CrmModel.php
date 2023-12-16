<?php
namespace App\Models;
use CodeIgniter\Model;
class CrmModel extends Model
{
    protected $table = 'crm';
    protected $primaryKey = 'id';
    // protected $allowedFields = ['first_name', 'last_name','phone','email','lead_source','linked_in','twitter','facebook','status','follow_up_alert','is_deleted','created_at'];

   
}