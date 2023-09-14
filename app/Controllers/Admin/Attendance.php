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
        $daterange = $this->request->getGetPost('daterange');
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
        // $currentDate = date('m/d/Y');
        // $condition = array('att_current_date' => $currentDate);
        $condition = array();
        if ($dtSearchKeyVal != '') {
            $likeArr = array(
                'employee_name' => trim($dtSearchKeyVal),
                'employee_email' => strtolower(trim($dtSearchKeyVal)),
                'reason' => trim($dtSearchKeyVal),
            );
        }
        if (isset($daterange) && !empty($daterange)) {
            if (!empty($daterange)) {
                $condition['att_current_date'] = $daterange;
            }
        }
        $totCounts = $this->LmsModel->get_all_counts(EMPLOYEE_ATTENDANCE, $condition, '', $likeArr);
        $sortArr = array('dt' => -1);
        if ($sortField != '') {
            $sortArr = array($sortField => $sortJob);
        }
        // $condition
        //  print_r($condition);die;
        $ajaxDataArr = $this->LmsModel->get_all_details(EMPLOYEE_ATTENDANCE, $condition, $sortArr, $rowperpage, $row_start, $likeArr);


        $tblData = array();
        $position = 1;

        foreach ($ajaxDataArr->getResult() as $row) {
            $tblData[] = array(
                // 'DT_RowId' => (string)$rowId,
                // 'checker_box' => '<input class="checkRows" name="checkbox_id[]" type="checkbox" value="' . $rowId . '">',
                'employee_name' => $row->employee_name,
                'employee_email' => $row->employee_email,
                'att_current_date' => $row->att_current_date,
                "att_current_time" =>  $row->att_current_time,
                "reason" =>  str_replace("_", " ", ucfirst($row->reason))
            );
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
