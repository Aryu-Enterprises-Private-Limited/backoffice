<?php

namespace App\Controllers\Manager;

use App\Controllers\BaseController;

class Employee extends BaseController
{
    public function __construct()
    {
        $this->session = session();
        $this->LmsModel = new \App\Models\LmsModel();
    }

    public function index()
    {
        if ($this->checkSession('M') != '') {
            $this->data['title'] = 'Employee List';
            echo view(MANAGER_PATH . '/employee/list', $this->data);
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/' . MANAGER_PATH);
        }
    }

    public function list_ajax($returnType = 'json')
    {
        $draw = $this->request->getPostGet('draw');
        $row_start = $this->request->getPostGet('start');
        $rowperpage = $this->request->getPostGet('length'); // Rows display per page
        $status = $this->request->getPostGet('status');

        $columnIndex = 0;
        if (isset($this->request->getPostGet('order')[0]['column'])) {
            $columnIndex = $this->request->getPostGet('order')[0]['column']; // Column index
        }
        $sortField = ''; // Column name
        if (isset($this->request->getPostGet('columns')[$columnIndex]['data'])) {
            $sortField = $this->request->getPostGet('columns')[$columnIndex]['data'];
        }
        $columnIndex = 'asc';
        if (isset($this->request->getPostGet('order')[0]['dir'])) {
            $sortJob = $this->request->getPostGet('order')[0]['dir']; // asc or desc
        }
        $dtSearchKeyVal = '';
        if (isset($this->request->getPostGet('search')['value'])) {
            $dtSearchKeyVal = $this->request->getPostGet('search')['value']; // Search value
        }
        $likeArr = [];
        $condition = array('is_deleted' => '0');
        if (isset($status) && $status != '') {
            $condition = array('is_deleted' => '0', 'status' => $status);
        }
        if ($dtSearchKeyVal != '') {
            $likeArr = array(
                'first_name' => trim($dtSearchKeyVal),
                'employeeid' => trim($dtSearchKeyVal),
                'last_name' => strtolower(trim($dtSearchKeyVal)),
                // 'email' => trim($dtSearchKeyVal),

            );
        }

        $totCounts = $this->LmsModel->get_all_counts(EMPLOYEE_DETAILS, $condition, '', $likeArr);
        $sortArr = array('id' => -1);
        if ($sortField != '') {
            $sortArr = array($sortField => $sortJob);
        }

        $ajaxDataArr = $this->LmsModel->get_all_details(EMPLOYEE_DETAILS, $condition, $sortArr, $rowperpage, $row_start, $likeArr);

        $tblData = array();
        $position = 1;
        $x=1;
        foreach ($ajaxDataArr->getResult() as $row) {
            $cond = ['id' => $row->role_id];
            $role_name = $this->LmsModel->get_all_details(EMPLOYEE_ROLE, $cond)->getRow();

            $cond2 = ['id' => $row->department_id];
            $dept_name = $this->LmsModel->get_all_details(DEPARTMENT_DETAILS, $cond2)->getRow();

            $rowId =  (string)$row->id;
            $disp_status = 'Relieved';
            $actTitle = 'Click to active';
            $mode = 1;
            $btnColr = 'btn-danger';
            if (isset($row->status) && $row->status == '1') {
                $disp_status = 'Current';
                $mode = 0;
                $btnColr = 'btn-success';
                $actTitle = 'Click to inactivate';
            }
            $statusTxt = $actTitle;


            $statusTxt =  '<a data-toggle="tooltip"  href="javascript:void(0);"> <button type="button" class="btn ' . $btnColr . ' btn-sm waves-effect waves-light">' . $disp_status . '</button></a>';

            $tblData[] = array(
                's_no' => $x,
                'first_name' => ucfirst($row->first_name) . ' ' . ucfirst($row->last_name),
                'employeeid' => $row->employeeid,
                // 'email' => $row->email,
                'role_id' => ucfirst($role_name->role_name) . ' / ' . ucfirst($dept_name->department_name),
                "status" =>  $statusTxt,
            );
            $x++;
        }
        $response = array(
            "status" => '1',
            "draw" => intval($draw),
            "iTotalRecords" => $totCounts,
            "iTotalDisplayRecords" => $totCounts,
            "aaData" => $tblData
        );
        $returnArr = $response;
        echo json_encode($returnArr);
    }

    public function fetchData()
    {
        $condition = array('is_deleted' => '0', 'status' => '1');
        $active_cnt = $this->LmsModel->get_all_counts(EMPLOYEE_DETAILS, $condition);
        $condition2 = array('is_deleted' => '0', 'status' => '0');
        $inactive_cnt = $this->LmsModel->get_all_counts(EMPLOYEE_DETAILS, $condition2);

        $data = [$active_cnt, $inactive_cnt]; // Replace with your data retrieval logic
        return $this->response->setJSON($data);
    }

    public function fetchgenderData()
    {
        $condition = array('status'=>'1','is_deleted' => '0', 'gender' => 'male');
        $male_cnt = $this->LmsModel->get_all_counts(EMPLOYEE_DETAILS, $condition);

        $condition2 = array('status'=>'1','is_deleted' => '0', 'gender' => 'female');
        $female_cnt = $this->LmsModel->get_all_counts(EMPLOYEE_DETAILS, $condition2);

        $condition3 = array('status'=>'1','is_deleted' => '0', 'gender' => 'others');
        $other_cnt = $this->LmsModel->get_all_counts(EMPLOYEE_DETAILS, $condition3);

        $data = [$male_cnt, $female_cnt, $other_cnt]; // Replace with your data retrieval logic
        return $this->response->setJSON($data);
    }

    public function fetchjobtypeData()
    {
        $condition2 = array('status'=>'1','is_deleted' => '0', 'employment_type' => 'full_time');
        $fulltime_cnt = $this->LmsModel->get_all_counts(EMPLOYEE_DETAILS, $condition2);

        $condition3 = array('status'=>'1','is_deleted' => '0', 'employment_type' => 'freelance');
        $free_cnt = $this->LmsModel->get_all_counts(EMPLOYEE_DETAILS, $condition3);

        $condition4 = array('status'=>'1','is_deleted' => '0', 'employment_type' => 'intern');
        $intern_cnt = $this->LmsModel->get_all_counts(EMPLOYEE_DETAILS, $condition4);

        $data = [$fulltime_cnt, $free_cnt, $intern_cnt]; // Replace with your data retrieval logic
        return $this->response->setJSON($data);
    }
}
