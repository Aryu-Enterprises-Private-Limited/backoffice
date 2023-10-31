<?php

namespace App\Controllers\Manager;

use App\Controllers\BaseController;

class Candidate extends BaseController
{
    public function __construct()
    {
        $this->session = session();
        $this->LmsModel = new \App\Models\LmsModel();
    }

    public function index()
    {
        if ($this->checkSession('M') != '') {
            $this->data['job_open_opt'] = $this->LmsModel->get_selected_fields(JOBS, ['status' => '1', 'is_deleted' => '0'], ['id', 'jobs_name'])->getResult();
            $this->data['title'] = 'Candidate Application List';
            echo view(MANAGER_PATH . '/candidates/list', $this->data);
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/' . MANAGER_PATH);
        }
    }

    public function list_ajax($returnType = 'json')
    {
        $job_open_id = $this->request->getGetPost('job_open');
        $month = $this->request->getPostGet('month');
        $year = $this->request->getPostGet('year');
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
        $condition = array('is_deleted' => '0');
        if ($dtSearchKeyVal != '') {
            $likeArr = array(
                'first_name' => trim($dtSearchKeyVal),
                'last_name' => trim($dtSearchKeyVal),
                'contact_no' => trim($dtSearchKeyVal),
                'email' => trim($dtSearchKeyVal),
            );
        }
        if (isset($job_open_id) && !empty($job_open_id)) {
            if (!empty($job_open_id)) {
                $condition['job_opening_id'] = $job_open_id;
            }
        }
        if (isset($month) && !empty($month)) {
            if (!empty($month)) {
                // $dateStringWithSpace = str_replace(',', ', ', $month);

                // $dateString = $dateStringWithSpace;

                // Parse the date string to obtain the year and month
                $dateComponents = explode(',', $month);
                // print_r($dateComponents);die;
                if (count($dateComponents) === 2) {
                    $month = $dateComponents[0];
                    $year = $dateComponents[1];

                    // Calculate the first day of the specified month and year
                    $firstDayOfMonth = date('Y-m-d', strtotime("{$year}-{$month}-01"));

                    // Calculate the last day of the specified month and year
                    $lastDayOfMonth = date('Y-m-t', strtotime("{$year}-{$month}-01"));
                    $condition['date >='] = $firstDayOfMonth;
                    $condition['date <='] = $lastDayOfMonth;
                }
            }
        }
        if (isset($year) && !empty($year) && empty($month)) {
            if (!empty($year)) {
                $condition = array('is_deleted' => '0');
                $condition['date'] = $year;
            }
        }
        
        $totCounts = $this->LmsModel->get_all_counts(CANDIDATES_DETAILS, $condition, '', $likeArr);
        $sortArr = array('first_name' => -1);
        if ($sortField != '') {
            $sortArr = array($sortField => $sortJob);
        }

        $ajaxDataArr = $this->LmsModel->get_all_details(CANDIDATES_DETAILS, $condition, $sortArr, $rowperpage, $row_start, $likeArr);


        $tblData = array();
        foreach ($ajaxDataArr->getResult() as $row) {
            // $cond = ['id' => $row->job_opening_id];
            // $job_details = $this->LmsModel->get_all_details(JOBS, $cond)->getRow();


            $cond2 = ['id' => $row->application_status_id];
            $app_details = $this->LmsModel->get_all_details(APPLICATION_STATUS, $cond2)->getRow();
            $tblData[] = array(
                'first_name' => ucfirst($row->first_name) . ' ' . ucfirst($row->last_name),
                'date' => $row->date,
                'contact_no' => $row->contact_no,
                'email' => $row->email,
                'application_status_id' => ucfirst($app_details->app_status),
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




    public function fetchData()
    {
        $cond = array('status' => '1', 'is_deleted' => '0');
        $sourceNames = $this->LmsModel->get_selected_fields(APPLICATION_SOURCE, $cond, ['id', 'source_name'])->getResult();
        // $sourceNames = ['LinkedIn', 'Naukri']; // Source names to count

        $year = date("Y"); // Get the current year
        $data = []; // Initialize an array to store the counts

        for ($month = 1; $month <= 12; $month++) {
            $firstDay = date("Y-m-01", strtotime("$year-$month-01"));
            $lastDay = date("Y-m-t", strtotime("$year-$month-01"));
            $counts = [];
            foreach ($sourceNames as $sourceName) {
                $condition = [
                    'is_deleted' => '0',
                    'source_id' => $sourceName->id,
                    'date >=' => $firstDay,
                    'date <=' => $lastDay,
                ];
                $count = $this->LmsModel->get_all_counts(CANDIDATES_DETAILS, $condition);
                $counts[$sourceName->source_name] = $count;
            }

            $data[$month] = $counts; // Store counts for the current month
        }

        // Output the data as JSON
        return $this->response->setJSON($data);
    }

    public function fetchfunnelData()
    {
        $condition = array('is_deleted' => '0');
        $candidate_dts = $this->LmsModel->get_all_details(CANDIDATES_DETAILS, $condition)->getResult();

        $applied_cnt = $phn_scr_cnt = $interview_sh_cnt = $hired_cnt = $off_process_cnt = $wait_list_cnt = $rej_cnt = 0;
        foreach ($candidate_dts as $details) {
            $cond2 = ['id' => $details->application_status_id];
            $app_details = $this->LmsModel->get_all_details(APPLICATION_STATUS, $cond2)->getRow();
            if ($app_details->app_status == 'Applied') {
                $applied_cnt++;
            } else if ($app_details->app_status == 'Phone Screening') {
                $phn_scr_cnt++;
            } else if ($app_details->app_status == 'Interview Scheduled') {
                $interview_sh_cnt++;
            } else if ($app_details->app_status == 'Hired') {
                $hired_cnt++;
            } else if ($app_details->app_status == 'Offer Process') {
                $off_process_cnt++;
            } else if ($app_details->app_status == 'Waiting list') {
                $wait_list_cnt++;
            } else if ($app_details->app_status == 'Rejected') {
                $rej_cnt++;
            }
        }
        $data = [$applied_cnt, $phn_scr_cnt, $interview_sh_cnt, $hired_cnt, $off_process_cnt, $wait_list_cnt, $rej_cnt];

        // Return the data as JSON
        return $this->response->setJSON($data);
    }
}
