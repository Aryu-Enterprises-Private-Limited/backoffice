<?php

namespace App\Controllers\Employee;

// use App\Models\userModel;
use PhpParser\Node\Expr\Print_;
use App\Controllers\BaseController;
use DateTime;
class Attendance extends BaseController
{

    public function __construct()
    {
        $this->session = session();
        $this->LmsModel = new \App\Models\LmsModel();
    }

    public function index()
    {
        // helper('url');
        // print_r($_SESSION);die;
        if ($this->checkSession('E') != '') {
            $this->data['title'] = 'Attendance';
            $this->data['atttitle'] = 'Attendance List';
            $currentDate = date('m/d/Y');
            $employe_id = session()->get(APP_NAME . '_session_employee_id');
            $condition = array('att_current_date' => $currentDate, 'employee_id' => $employe_id);
            $this->data['att_details'] = $this->LmsModel->get_all_details(EMPLOYEE_ATTENDANCE, $condition,'desc')->getResult();
            // echo"<pre>";print_r($this->data['att_details']);die;
            echo view(EMPLOYEE_PATH . '/attendance', $this->data);
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/');
        }
    }


    public function insertUpdate()
    {
        if ($this->checkSession('E') != '') {
            $curr_date_time = (string)$this->request->getPostGet('curr_date_time');
            $reason = (string)$this->request->getPostGet('reason');
            $id = (string)$this->request->getPostGet('id');

            $delimiter = "-";
            $parts = explode($delimiter, $curr_date_time);


            $employee_id =   $this->session->get(APP_NAME . '_session_employee_id');
            $employee_email = $this->session->get(APP_NAME . '_session_employee_email');
            $employee_name = $this->session->get(APP_NAME . '_session_employee_name');
            // print_r($parts) ;
            //  echo $parts[0];
            //  echo $parts[1];
            // $dateString = $parts[0] .''. $parts[1];
            // $dateTime = DateTime::createFromFormat('m/d/Y H:i:s A', $dateString);
            // echo $dateTime;
            //  die;
            $fSubmit = FALSE;
            if ($curr_date_time != '' && $reason != '') {
                $dataArr = array(
                    'employee_name' => $employee_name,
                    'employee_email' => $employee_email,
                    'employee_id' => $employee_id,
                    'att_current_date' => trim($parts[0]),
                    'att_current_time' => trim($parts[1]),
                    'reason' => $reason,
                );

                // if ($id == '') {
                $this->LmsModel->simple_insert(EMPLOYEE_ATTENDANCE, $dataArr);
                $this->session->setFlashdata('success_message', 'Attendance details added successfully.');
                // $this->setFlashMessage('success', 'Crm details added successfully');
                $fSubmit = TRUE;
                // } else {
                //     $condition = array('id' => $id);
                //     $this->LmsModel->update_details(CRM, $dataArr, $condition);
                //     $this->session->setFlashdata('success_message', 'Spends details update successfully');
                //     $fSubmit = TRUE;
                // }
            } else {
                $this->session->setFlashdata('error_message', 'Form data is missing.');
            }
            if ($fSubmit) {
                $url = EMPLOYEE_PATH . '/attendance';
            } else {
                $url = EMPLOYEE_PATH . '/attendance';
            }
            return redirect()->to("$url");
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/');
        }
    }

    // public function list()
    // {
    //     if ($this->checkSession('E') != '') {
    //         $this->data['title'] = 'Attendance List';
    //         echo view(EMPLOYEE_PATH . '/attendance/list', $this->data);
    //     } else {
    //         $this->session->setFlashdata('error_message', 'Please login!!!');
    //         return redirect()->to('/');
    //     }
    // }


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
        $currentDate = date('m/d/Y');
        $employe_id = session()->get(APP_NAME . '_session_employee_id');
        $condition = array('att_current_date' => $currentDate, 'employee_id' => $employe_id);
        // print_r($condition);die;
        if ($dtSearchKeyVal != '') {
            $likeArr = array(
                'employee_name' => trim($dtSearchKeyVal),
                'employee_email' => strtolower(trim($dtSearchKeyVal)),
                'reason' => trim($dtSearchKeyVal),
            );
        }

        $totCounts = $this->LmsModel->get_all_counts(EMPLOYEE_ATTENDANCE, $condition, '', $likeArr);
        $sortArr = array('dt' => -1);
        if ($sortField != '') {
            $sortArr = array($sortField => $sortJob);
        }
        // $condition
        //  print_r($condition);die;
        $ajaxDataArr = $this->LmsModel->get_all_details(EMPLOYEE_ATTENDANCE, $condition, $sortArr, $rowperpage, $row_start, $likeArr);


        // if (isset($_GET['export']) && $_GET['export'] == 'excel') {
        //     $returnArr['status'] = '1';
        //     $returnArr['response'] = $ajaxDataArr;
        //     return $returnArr;
        // }
        $tblData = array();
        $position = 1;

        foreach ($ajaxDataArr->getResult() as $row) {
            // $source = '';
            // $logo = MEMBER_PROFILE_DEFAULT;
            // if (isset($row->logo) && $row->logo != '') {
            //     $logo = COMPANIES_LOGO_PATH . $row->logo;
            //     $source .= '<img src="' . base_url() . '/' . $logo . '" width="128" height="128" class="rounded-circle img-thumbnail" alt="' . $row->name . ' Image">';
            // } else {
            //     $source .= '<img src="assets/images/users/avatar-1.jpg" alt="user-img" class="rounded-circle user-img">';
            // }


            $tblData[] = array(
                // 'DT_RowId' => (string)$rowId,
                // 'checker_box' => '<input class="checkRows" name="checkbox_id[]" type="checkbox" value="' . $rowId . '">',
                'employee_name' => $row->employee_name,
                'employee_email' => $row->employee_email,
                'att_current_date' => $row->att_current_date,
                "att_current_time" =>  $row->att_current_time,
                "reason" =>  $row->reason,
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
