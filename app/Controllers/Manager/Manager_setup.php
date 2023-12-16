<?php

namespace App\Controllers\Manager;

use App\Controllers\BaseController;
use DateTime;

class Manager_setup extends BaseController
{

    public function __construct()
    {
        $this->session = session();
        $this->AdminModel = new \App\Models\AdminModel();
    }

    public function index()
    {
        if ($this->checkSession('M') != '') {
            $this->data['title'] = 'Dashboard';
            echo view(MANAGER_PATH . '/pages/dashboard', $this->data);
        } else {
            $this->data['title'] = 'Manager Login';
            return view(MANAGER_PATH . '/pages/login', $this->data);
        }
    }

    public function do_login()
    {
        $email = $this->request->getPostGet('email');
        $pword = $this->request->getPostGet('password');

        $validation = \Config\Services::validation();

        $validation->setRules([
            'email' => 'required|valid_email',
            'password' => 'required|min_length[8]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            $this->session->setFlashdata('error_message', 'Login failed.');
            return redirect()->to('/' . MANAGER_PATH);
        } else {
            $condition = ['email' => $email];
            $getManagerData = $this->AdminModel->get_selected_fields(MANAGER_USERS, $condition, array('status', 'password', 'id', 'user_name', 'email'));

            if ($getManagerData->getNumRows() == 1) {
                $status = $getManagerData->getRow()->status;
                if ($status == 1) {
                    $aPWord = $getManagerData->getRow()->password;
                    $isvalidPWord = password_verify($pword, $aPWord);
                    if ($isvalidPWord) {
                        $sessdata = array(
                            APP_NAME . '_session_manager_id' => $getManagerData->getRow()->id,
                            APP_NAME . '_session_manager_name' => $getManagerData->getRow()->user_name,
                            APP_NAME . '_session_manager_email' => $getManagerData->getRow()->email
                        );
                        $this->session->set($sessdata);
                        $this->session->setFlashdata('success_message', 'Login successful!');
                        return redirect()->to(MANAGER_PATH . '/dashboard');
                    } else {
                        $this->session->setFlashdata('error_message', 'Entered password is incorrect.');
                        return redirect()->to('/' . MANAGER_PATH);
                    }
                } else {
                    $this->session->setFlashdata('error_message', 'This account is not active.');
                    return redirect()->to('/' . MANAGER_PATH);
                }
            } else {
                $this->session->setFlashdata('error_message', 'This Email is not registered.');
                return redirect()->to('/' . MANAGER_PATH);
            }
        }
    }

    public function dashboard()
    {
        if ($this->checkSession('M') != '') {
            $this->data['title'] = 'Dashboard';

            $condition = array('is_deleted' => '0');
            $this->data['int_sts_dts'] = $this->AdminModel->get_all_details(INTERVIEW_STATUS, $condition)->getResult();

            // $condition = array('is_deleted' => '0');
            // $candidate_dts = $this->AdminModel->get_all_details(CANDIDATES_DETAILS, $condition)->getResult();

            // $applied_cnt = $hired_cnt  = $rej_cnt = 0;
            // foreach ($candidate_dts as $details) {
            //     $cond2 = ['id' => $details->application_status_id];
            //     $app_details = $this->AdminModel->get_all_details(APPLICATION_STATUS, $cond2)->getRow();
            //     if ($app_details->app_status == 'Applied') {
            //         $applied_cnt++;
            //     }  else if ($app_details->app_status == 'Hired') {
            //         $hired_cnt++;
            //     }  else if ($app_details->app_status == 'Rejected') {
            //         $rej_cnt++;
            //     }
            // }


            $condition = array('is_deleted' => '0');
            $candidate_dts = $this->AdminModel->get_all_details(CANDIDATES_DETAILS, $condition)->getResult();
            $sch_cnt = $n_sch_cnt = $re_sch_cnt = $show_cnt = $not_able_cnt = $off_cnt =0;
            $applied_cnt = $hired_cnt  = $rej_cnt = $completed_cnt = $wl_cnt = 0;
            foreach ($candidate_dts as $details) {
                $cond2 = ['candidate_id' => $details->id];
                $app_details = $this->AdminModel->get_all_details(APP_STATUS_LOG, $cond2)->getResult();

                $cond4 = ['id' => $details->application_status_id];
                $app_sts_details = $this->AdminModel->get_all_details(APPLICATION_STATUS, $cond4)->getRow();

                if ($app_sts_details->app_status == 'Hired') {
                    $hired_cnt++;
                } else if ($app_sts_details->app_status == 'Rejected') {
                    $rej_cnt++;
                }
                foreach ($app_details as $app_data) {
                    $cond3 = ['id' => $app_data->application_status_id];
                    $application_details = $this->AdminModel->get_all_details(APPLICATION_STATUS, $cond3)->getRow();
                    if ($application_details->app_status == 'Applied') {
                        $applied_cnt++;
                    }
                    //  else if ($app_sts_details->app_status == 'Hired') {
                    //     $hired_cnt++;
                    // }  else if ($app_sts_details->app_status == 'Rejected') {
                    //     $rej_cnt++;
                    // }
                }

                $cond5 = ['id' => $details->interview_status_id, 'is_deleted' => '0'];
                $int_sts_details = $this->AdminModel->get_all_details(INTERVIEW_STATUS, $cond5)->getRow();
                if($int_sts_details->interview_sts == 'Scheduled'){
                    $sch_cnt++;
                }else if($int_sts_details->interview_sts == 'Not Scheduled'){
                    $n_sch_cnt++;
                }else if($int_sts_details->interview_sts == 'Re-Schedule'){
                    $re_sch_cnt++;
                }else if($int_sts_details->interview_sts == 'No Show'){
                    $show_cnt++;
                }else if($int_sts_details->interview_sts == 'Completed'){
                    $completed_cnt++;
                }else if($int_sts_details->interview_sts == 'Not able to Come'){
                    $not_able_cnt++;
                }else if($int_sts_details->interview_sts == 'waiting list'){
                    $wl_cnt++;
                }else if($int_sts_details->interview_sts == 'offer process'){
                    $off_cnt++;
                }
            }

            $this->data['applied_cnt'] = $applied_cnt;
            $this->data['hired_cnt'] = $hired_cnt;
            $this->data['rej_cnt'] = $rej_cnt;

            $this->data['sch_cnt'] = $sch_cnt;
            $this->data['n_sch_cnt'] = $n_sch_cnt;
            $this->data['re_sch_cnt'] = $re_sch_cnt;
            $this->data['show_cnt'] = $show_cnt;
            $this->data['completed_cnt'] = $completed_cnt;
            $this->data['not_able_cnt'] = $not_able_cnt;
            $this->data['wl_cnt'] = $wl_cnt;
            $this->data['off_cnt'] = $off_cnt;



            echo view(MANAGER_PATH . '/pages/dashboard', $this->data);
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/' . MANAGER_PATH);
        }
    }

    public function logout()
    {
        $sessdata = array(
            APP_NAME . '_session_manager_id' => '',
            APP_NAME . '_session_manager_name' => '',
            APP_NAME . '_session_manager_email' => '',
        );
        $this->session->set($sessdata);
        $this->session->setFlashdata('success_message', 'Successfully logout from your account');
        return redirect()->to('/' . MANAGER_PATH);
    }

    public function holiday_list()
    {
        if ($this->checkSession('M') != '') {
            $this->data['title'] = 'PUBLIC HOLIDAY';
            echo view(MANAGER_PATH . '/public_holiday/holiday_list', $this->data);
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
        $condition = array('status' => 1, 'is_deleted' => 0, 'current_year' => $current_yr);
        if ($dtSearchKeyVal != '') {
            $likeArr = array(
                'reason' => trim($dtSearchKeyVal),
                'current_year' => strtolower(trim($dtSearchKeyVal)),
            );
        }

        $totCounts = $this->AdminModel->get_all_counts(PUBLIC_HOLIDAY, $condition, '', $likeArr);
        $sortArr = array('date' => -1);
        if ($sortField != '') {
            $sortArr = array($sortField => $sortJob);
        }

        $ajaxDataArr = $this->AdminModel->get_all_details(PUBLIC_HOLIDAY, $condition, $sortArr, $rowperpage, $row_start, $likeArr);

        $tblData = array();
        $position = 1;

        foreach ($ajaxDataArr->getResult() as $row) {
            $date = new DateTime($row->date);
            $dayOfWeek = $date->format('l');
            $tblData[] = array(
                'date' => $row->date,
                'day' => $dayOfWeek,
                'reason' => ucfirst($row->reason),
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
