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

        $x = 1;
        $tblData = array();
        foreach ($ajaxDataArr->getResult() as $row) {
            // $cond = ['id' => $row->job_opening_id];
            // $job_details = $this->LmsModel->get_all_details(JOBS, $cond)->getRow();


            $cond2 = ['id' => $row->application_status_id];
            $app_details = $this->LmsModel->get_all_details(APPLICATION_STATUS, $cond2)->getRow();
            $tblData[] = array(
                's_no' => $x,
                'first_name' => ucfirst($row->first_name) . ' ' . ucfirst($row->last_name),
                'date' => $row->date,
                'contact_no' => $row->contact_no,
                'email' => $row->email,
                'application_status_id' => ucfirst($app_details->app_status),
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
                    'status' => '1',
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

    public function fetchfinanceData()
    {
        $cond = array('status' => '1', 'is_deleted' => '0');
        $curr_year = date("Y");
        $income_dts = $this->LmsModel->get_selected_fields(INCOME_DETAILS, $cond, ['id', 'amount', 'date'])->getResult();
        $expense_dts = $this->LmsModel->get_selected_fields(EXPENSE_DETAILS, $cond, ['id', 'amount', 'date'])->getResult();
        
        $income_monthWiseTotal = [];
        foreach ($income_dts as $income) {
            // Extract year from the date
            $year = date('Y', strtotime($income->date));

            // echo $curr_year;
            // Filter data for the year 2023
            if ($year === $curr_year) {
                // Extract month from the date
                $month = date('m', strtotime($income->date));
                $mnth = date("M", mktime(0, 0, 0, $month, 1));
                // Calculate month-wise total for 2023
                if (!isset($income_monthWiseTotal[$mnth])) {
                    $income_monthWiseTotal[$mnth] = 0;
                }
                $income_monthWiseTotal[$mnth] += floatval(str_replace(',', '', $income->amount));
            }
        }
        $exp_monthWiseTotal = [];
        foreach ($expense_dts as $expense) {
            // Extract year from the date
            $year = date('Y', strtotime($expense->date));

            // echo $curr_year;
            // Filter data for the year 2023
            if ($year === $curr_year) {
                // Extract month from the date
                $month = date('m', strtotime($expense->date));

                $mnth = date("M", mktime(0, 0, 0, $month, 1));
                // Calculate month-wise total for 2023
                if (!isset($exp_monthWiseTotal[$mnth])) {
                    $exp_monthWiseTotal[$mnth] = 0;
                }
                $exp_monthWiseTotal[$mnth] += floatval(str_replace(',', '', $expense->amount));
            }
        }
        
        $data = ['expense' => $exp_monthWiseTotal, 'income' =>  $income_monthWiseTotal];
        return $this->response->setJSON($data);
    }

}
