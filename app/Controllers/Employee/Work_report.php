<?php

namespace App\Controllers\Employee;

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
        if ($this->checkSession('E') != '') {
            $this->data['title'] = 'Work Report List';

            echo view(EMPLOYEE_PATH . '/work_report/list', $this->data);
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/');
        }
    }


    public function insertUpdate()
    {
        if ($this->checkSession('E') != '') {
            $date = (string)$this->request->getPostGet('date');
            $proj_name = $this->request->getPostGet('proj_name');
            $task_dt = $this->request->getPostGet('task_dt');
            $comment = (string)$this->request->getPostGet('comment');
            $wrk_sts = $this->request->getPostGet('wrk_sts');
            $id = (string)$this->request->getPostGet('id');
            $employee_id =   $this->session->get(APP_NAME . '_session_employee_id');
            $employee_email = $this->session->get(APP_NAME . '_session_employee_email');
            $employee_name = $this->session->get(APP_NAME . '_session_employee_name');

            $fSubmit = FALSE;
            if ($date != '' && $proj_name != ''  && $task_dt != '' && $wrk_sts != '') {

                $dataArr = [
                    'id' => null,
                    'date' => $date,
                    'proj_task_dts' => [],
                    'comment' => $comment,
                    'employee_id' => $employee_id,
                    'employee_email' => $employee_email,
                    'employee_name' => $employee_name
                ];


                foreach ($proj_name as $index => $projName) {
                    $dataArr['proj_task_dts'][] = [
                        'proj_name' => $projName,
                        'task_dt' => $task_dt[$index],
                        'wrk_sts' => $wrk_sts[$index],
                    ];
                }

                // Encode proj_task_dts as JSON before insertion
                $dataArr['proj_task_dts'] = json_encode($dataArr['proj_task_dts']);

                $this->LmsModel->simple_insert(EMPLOYEE_WORK_REPORT, $dataArr);
                $this->session->setFlashdata('success_message', 'Attendance details added successfully.');
                $fSubmit = TRUE;
            } else {
                $this->session->setFlashdata('error_message', 'Form data is missing.');
            }
            if ($fSubmit) {
                $url = EMPLOYEE_PATH . '/work_report/list';
            } else {
                $url = EMPLOYEE_PATH . '/work_report/list';
            }
            return redirect()->to("$url");
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/');
        }
    }

    public function list_ajax($returnType = 'json')
    {
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



        $current_yr = date('Y');
        $current_mnth = date('m');
        $firstDayOfMonth = date('Y-m-d', strtotime("{$current_yr}-{$current_mnth}-01"));
        $lastDayOfMonth = date('Y-m-t', strtotime("{$current_yr}-{$current_mnth}-01"));
        $employe_id = session()->get(APP_NAME . '_session_employee_id');
        $condition = array('employee_id' => $employe_id);
        $condition['date >='] = $firstDayOfMonth;
        $condition['date <='] = $lastDayOfMonth;
        if ($dtSearchKeyVal != '') {
            $likeArr = array(
                'employee_name' => trim($dtSearchKeyVal),
                'employee_email' => strtolower(trim($dtSearchKeyVal)),
                'reason' => trim($dtSearchKeyVal),
            );
        }

        $totCounts = $this->LmsModel->get_all_counts(EMPLOYEE_WORK_REPORT, $condition, '', $likeArr);
        $sortArr = array('date' => -1);
        if ($sortField != '') {
            $sortArr = array($sortField => $sortJob);
        }

        $ajaxDataArr = $this->LmsModel->get_all_details(EMPLOYEE_WORK_REPORT, $condition, $sortArr, $rowperpage, $row_start, $likeArr);

        $tblData = array();
        $position = 1;

        foreach ($ajaxDataArr->getResult() as $row) {
            $rowId =  (string)$row->id;
            $actionTxt = '<a class="btn btn-icon text-info" href="/' . EMPLOYEE_PATH . '/work_report/view/' . (string)$rowId . '"><i class="fas fa-eye"></i></a>';
            $tblData[] = array(
                'employee_name' => $row->employee_name,
                'date' => $row->date,
                'proj_task_dts' => $actionTxt,
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

    public function add_edit($id = "")
    {
        if ($this->checkSession('E') != '') {
            $this->data['title'] = 'Add Work Report';
            echo view(EMPLOYEE_PATH . '/work_report/add', $this->data);
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/');
        }
    }
    public function view($id = "")
    {
        if ($this->checkSession('E') != '') {
            $uri = service('uri');
            $id = $uri->getSegment(4);
            if ($id != '') {
                $condition = array('id' => $id);
                $this->data['report_details'] = $this->LmsModel->get_all_details(EMPLOYEE_WORK_REPORT, $condition)->getRow();
                if (!empty($this->data['report_details'])) {
                    $this->data['title'] = 'Report Detail view';
                    echo view(EMPLOYEE_PATH . '/work_report/view', $this->data);
                } else {
                    $this->session->setFlashdata('error_message', 'Couldnot find the Report details');
                    // $this->setFlashMessage('error', 'Couldn\'t find the subadmin');
                    return redirect()->route(EMPLOYEE_PATH . '/work_report/list');
                }
            } else {
                $this->session->setFlashdata('error_message', 'Couldnot find the details');
                // $this->setFlashMessage('error', 'Couldn\'t find the subadmin');
                return redirect()->route(EMPLOYEE_PATH . '/work_report/list');
            }
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/');
        }
    }
}
