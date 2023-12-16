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
            ->where($condition)->groupBy('employee_id, employee_email, att_current_date, employee_name');
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
            ->groupBy('employee_id, employee_email, att_current_date, employee_name');

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

    public function group_by_atttbl($condition = '')
    {
        // $sql = 'employee_id,employee_email,group_concat(total_hrs SEPARATOR "~") AS concatenated_hrs,group_concat(att_current_date SEPARATOR "~") AS concatenated_date,employee_name';

        $sql = 'employee_id,employee_email,CONCAT_WS("~", GROUP_CONCAT(total_hrs SEPARATOR "~")) AS concatenated_hrs,CONCAT_WS("~", GROUP_CONCAT(att_current_date SEPARATOR "~")) AS concatenated_date,employee_name';
        $builder = $this->db->table(EMPLOYEE_ATTENDANCE_TOTAL_HOURS)->select(new RawSql($sql))
            ->where($condition)->groupBy('employee_id, employee_email, employee_name');
        return $builder->get();
    }

    public function public_yr_group($condition = ''){
        $sql = 'current_year,CONCAT_WS("~", GROUP_CONCAT(reason SEPARATOR "~")) AS concatenated_reason,CONCAT_WS("~", GROUP_CONCAT(date SEPARATOR "~")) AS concatenated_date';
        $builder = $this->db->table(PUBLIC_HOLIDAY)->select(new RawSql($sql))
            ->where($condition)->groupBy('current_year');
        return $builder->get();
    }

    public function grp_by_schedule($condition = ''){
        $sql = 'employee_id,CONCAT_WS("~", GROUP_CONCAT(daily_working_hrs SEPARATOR "~")) AS concatenated_daily_working_hrs';
        $builder = $this->db->table(SCHEDULE_HOURS)->select(new RawSql($sql))
            ->where($condition)->groupBy('employee_id');
        return $builder->get();
    }

    public function grp_by_income($condition = ''){
        $sql = 'billed_acc_id,CONCAT_WS("~", GROUP_CONCAT(amount SEPARATOR "~")) AS concatenated_amount';
        $builder = $this->db->table(INCOME_DETAILS)->select(new RawSql($sql))
            ->where($condition)->groupBy('billed_acc_id');
        return $builder->get();
    }

    public function grp_by_expense($condition = ''){
        $sql = 'category_id,CONCAT_WS("~", GROUP_CONCAT(amount SEPARATOR "~")) AS concatenated_amount';
        $builder = $this->db->table(EXPENSE_DETAILS)->select(new RawSql($sql))
            ->where($condition)->groupBy('category_id');
        return $builder->get();
    }
}
