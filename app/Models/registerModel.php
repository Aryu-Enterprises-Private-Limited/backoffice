<?php

namespace App\Models;

use CodeIgniter\Model;

class registerModel extends Model
{
    protected $table = 'employee_register';

    protected $primaryKey = 'reg_id';
    
    protected $allowedFields = ['first_name', 'last_name','date_of_birth','phone_number','email','address','pin_code','city','state','blood_group','aadhar_no','pan_no','relationship','parent_name',
'parent_phone','parents_address','fresher_experience','cv_resume','notes'];

    // function save_record($data) {
    //      $this->insert($data);
    //  } 

}