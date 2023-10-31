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
                'day' =>$dayOfWeek,
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
