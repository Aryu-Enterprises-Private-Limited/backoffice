<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\RawSql;

class LmsModel extends Model
{
    protected $table = 'lms';
    protected $primaryKey = 'id';
    protected $allowedFields = ['first_name', 'last_name', 'phone', 'email', 'lead_source', 'linked_in', 'twitter', 'facebook', 'status', 'follow_up_alert', 'is_deleted', 'created_at'];

    public function group_by_tbl($condition = '')
    {
        // /$builder = $this->db->table('admin_users')->join('admin_roles', 'admin_roles.role_id = admin_users.role_id', 'inner')->select('admin_users.username, admin_roles.rolename');
        // $builder = $this->db->table('lms')->join('notes', 'notes.lms_id = lms.id', 'inner')->select('notes.note');
        // $currentDate = date('m/d/Y');
        $sql = 'employee_id,employee_email, att_current_date,group_concat(reason SEPARATOR "~") AS concatenated_reasons,employee_name,group_concat(att_current_time SEPARATOR "~") AS concatenated_current_time';
        $builder = $this->db->table(EMPLOYEE_ATTENDANCE)->select(new RawSql($sql))
            ->where($condition)->groupBy('employee_id');
        // ->having('att_current_date =', $currentDate);
        return $builder->get();
    }

    public function group_count_tbl($table = '', $condition = '', $likearr = '',)
    {

        // $likeCondition = 'employee_name LIKE "%search_string%"'; // Replace "search_string" with your actual search term


        $sql = 'employee_id, employee_email, att_current_date, GROUP_CONCAT(reason SEPARATOR "~") AS concatenated_reasons, employee_name, GROUP_CONCAT(att_current_time SEPARATOR "~") AS concatenated_current_time';
        $builder = $this->db->table($table)
            ->select($sql, false)
            ->where($condition)
            ->groupBy('employee_id');

        // Count the results
        // $resultCount = $builder->countAllResults();

        // Add a "LIKE" query
        // $likeCondition = 'employee_name LIKE "%search_string%"'; // Replace "search_string" with your actual search term
        if (!empty($likearr)) {
        $builder->where($likearr);
        $results = $builder->get()->getResult();
        }

        // Retrieve the filtered results
        

        // Output the result count and the filtered results
        // echo 'Result Count: ' . $resultCount . '<br>';
        // echo 'Filtered Results: ';
        // print_r($results);


        return $builder->countAllResults();
    }
}
