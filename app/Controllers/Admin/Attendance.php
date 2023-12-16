<?php

namespace App\Controllers\Admin;


use App\Controllers\BaseController;

class Attendance extends BaseController
{

    public function __construct()
    {
        $this->session = session();
        $this->LmsModel = new \App\Models\LmsModel();
    }

    public function index()
    {

        if ($this->checkSession('A') != '') {
            $this->data['title'] = 'Attendance List';
            echo view(ADMIN_PATH . '/attendance/list', $this->data);
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/' . ADMIN_PATH);
        }
    }



    public function list_ajax($returnType = 'json')
    {
        $from_date = $this->request->getGetPost('from_date');
        $to_date = $this->request->getGetPost('to_date');
        $reason = $this->request->getGetPost('reason');
        $draw = $this->request->getPostGet('draw');
        $row_start = $this->request->getPostGet('start');
        $rowperpage = $this->request->getPostGet('length'); // Rows display per page

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

        $condition = array();
        if ($dtSearchKeyVal != '') {
            $likeArr = array(
                'employee_name' => trim($dtSearchKeyVal),
                'employee_email' => strtolower(trim($dtSearchKeyVal)),
                'reason' => trim($dtSearchKeyVal),
            );
        }
        if (isset($from_date) && !empty($from_date) && !empty($to_date)) {
            if (!empty($from_date)) {
                $condition['att_current_date >='] = $from_date;
                $condition['att_current_date <='] = $to_date;
            }
        }
        if (isset($reason) && !empty($reason)) {
            if (!empty($reason)) {
                $condition['reason'] = $reason;
            }
        }
        //  print_r($condition);die;
        $totCounts = $this->LmsModel->get_all_counts(EMPLOYEE_ATTENDANCE, $condition, '', $likeArr);
        $sortArr = array('employee_name' => -1);
        if ($sortField != '') {
            $sortArr = array($sortField => $sortJob);
        }

        $ajaxDataArr = $this->LmsModel->get_all_details(EMPLOYEE_ATTENDANCE, $condition, $sortArr, $rowperpage, $row_start, $likeArr);
        
        $tblData = array();
        $x =1;
        foreach ($ajaxDataArr->getResult() as $row) {
            $empl_name = '<a href="/' . ADMIN_PATH . '/employee/view/' . $row->employee_id . '" class="text-decoration-none">'. ucfirst($row->employee_name).'</a>';
            $tblData[] = array(
                // 'DT_RowId' => (string)$rowId,
                // 'checker_box' => '<input class="checkRows" name="checkbox_id[]" type="checkbox" value="' . $rowId . '">',
                's_no'=> $x,
                'att_current_date' => $row->att_current_date,
                'employee_name' => $empl_name,
                // 'employee_email' => $row->employee_email,
                "att_current_time" =>  $row->att_current_time,
                "reason" =>  str_replace("_", " ", ucfirst($row->reason)),
                "ip_address" => $row->ip_address,
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
}
