<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;


class Work_report extends BaseController
{

    public function __construct()
    {
        $this->session = session();
        $this->LmsModel = new \App\Models\LmsModel();
    }

    public function index()
    {
        if ($this->checkSession('A') != '') {
            $this->data['title'] = 'Work Report List';
            $this->data['employee_details'] = $this->LmsModel->get_selected_fields(EMPLOYEE_DETAILS, ['is_deleted' => '0', 'status' => '1'], ['id', 'first_name', 'last_name'])->getResult();
            echo view(ADMIN_PATH . '/work_report/list', $this->data);
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/' . ADMIN_PATH);
        }
    }


    public function list_ajax($returnType = 'json')
    {
        // $daterange = $this->request->getGetPost('daterange');
        $from_date = $this->request->getGetPost('from_date');
        $to_date = $this->request->getGetPost('to_date');
        $employee_id = $this->request->getGetPost('employee_name');
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

        // if (isset($daterange) && !empty($daterange)) {
        //     $newDate = date("Y-m-d", strtotime($daterange));
        //     $condition = array('date' => $newDate);
        // }
        
        if (isset($employee_id) && !empty($employee_id)) {
            if (!empty($employee_id)) {
                $condition['employee_id'] = $employee_id;
            }
        }else if (isset($from_date) && !empty($from_date) && !empty($to_date)) {
                $frDate = date("Y-m-d", strtotime($from_date));
                $toDate = date("Y-m-d", strtotime($to_date));
                $condition['date >='] = $frDate;
                $condition['date <='] = $toDate;
        }else{
            $current_dt = date("Y-m-d");
            $condition = array('date' => $current_dt);
        }
        if ($dtSearchKeyVal != '') {
            $likeArr = array(
                'employee_name' => trim($dtSearchKeyVal),
                'employee_email' => strtolower(trim($dtSearchKeyVal)),
                'reason' => trim($dtSearchKeyVal),
            );
        }
        // print_r($condition);die;
        $totCounts = $this->LmsModel->get_all_counts(EMPLOYEE_WORK_REPORT, $condition, '', $likeArr);
        $sortArr = array('date' => -1);
        if ($sortField != '') {
            $sortArr = array($sortField => $sortJob);
        }

        $ajaxDataArr = $this->LmsModel->get_all_details(EMPLOYEE_WORK_REPORT, $condition, $sortArr, $rowperpage, $row_start, $likeArr);

        $tblData = array();
        $position = 1;
        $x =1;
        foreach ($ajaxDataArr->getResult() as $row) {
            $rowId =  (string)$row->id;
            $actionTxt = '<a class="btn btn-icon text-info" target="_blank" href="/' . ADMIN_PATH . '/work_report/view/' . (string)$rowId . '"><i class="fas fa-eye"></i></a>';
            $empl_name = '<a href="/' . ADMIN_PATH . '/employee/view/' . $row->employee_id . '" class="text-decoration-none">'. ucfirst($row->employee_name).'</a>'; 
            $tblData[] = array(
                's_no' => $x,
                'employee_name' => $empl_name,
                'date' => $row->date,
                'proj_task_dts' => $actionTxt,
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

    
    public function view($id = "")
    {
        if ($this->checkSession('A') != '') {
            $uri = service('uri');
            $id = $uri->getSegment(4);
            if ($id != '') {
                $condition = array('id' => $id);
                $this->data['report_details'] = $this->LmsModel->get_all_details(EMPLOYEE_WORK_REPORT, $condition)->getRow();
                if (!empty($this->data['report_details'])) {
                    $this->data['title'] = 'Report Detail view';
                    echo view(ADMIN_PATH . '/work_report/view', $this->data);
                } else {
                    $this->session->setFlashdata('error_message', 'Couldnot find the Report details');
                    return redirect()->route(ADMIN_PATH . '/work_report/list');
                }
            } else {
                $this->session->setFlashdata('error_message', 'Couldnot find the details');
                return redirect()->route(ADMIN_PATH . '/work_report/list');
            }
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/' . ADMIN_PATH);
        }
    }
}
