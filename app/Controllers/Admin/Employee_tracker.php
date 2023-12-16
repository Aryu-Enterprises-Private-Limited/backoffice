<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use DateTime;

class Employee_tracker extends BaseController
{

    public function __construct()
    {
        $this->session = session();
        $this->LmsModel = new \App\Models\LmsModel();
    }

    public function index()
    {
        if ($this->checkSession('A') != '') {
            $currentMonth = date('F');
            $currentYear = date('Y');
            $this->data['title'] = 'Employee Tracker List';
            $month = $this->request->getGetPost('month');
            if (isset($month) && $month != '') {
                $timestamp = strtotime($month); // Convert the date string to a timestamp
                $currentMonth = date("F", $timestamp);
                $currentYear = date("Y", $timestamp);
            }

            $this->data['dates'] = $datesForCurrentMonth = $this->generateDatesForMonth($currentMonth, $currentYear);
            // echo"<pre>";print_r($datesForCurrentMonth[0]);die;
            $sortArr = array('first_name' => 'asc');
            $cond = array('status' => 1, 'is_deleted' => 0);
            $this->data['emp_details'] = $this->LmsModel->get_selected_fields(EMPLOYEE_DETAILS, $cond, ['id', 'email','first_name','last_name'],$sortArr)->getResult();

            $this->data['att_category_details'] = $this->LmsModel->get_selected_fields(ATTENDANCE_CATEGORY, $cond, ['id', 'category_code'])->getResult();

            $condition =  array('month_dt' => $datesForCurrentMonth[0]);

            $this->data['att_tracker_details'] = $this->LmsModel->get_all_details(EMPLOYEE_TRACKER, $condition)->getResult();

            echo view(ADMIN_PATH . '/employee_tracker/list', $this->data);
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/' . ADMIN_PATH);
        }
    }

    private function generateDatesForMonth($currentMonth, $currentYear)
    {
        $firstDayOfMonth = date('Y-m-01', strtotime("{$currentYear}-{$currentMonth}"));
        $lastDayOfMonth = date('Y-m-t', strtotime("{$currentYear}-{$currentMonth}"));

        $datesForCurrentMonth = [];
        $currentDate = $firstDayOfMonth;

        while ($currentDate <= $lastDayOfMonth) {
            $datesForCurrentMonth[] = $currentDate;
            $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
        }
        return $datesForCurrentMonth;
    }

    public function insertUpdate()
    {
        $jsonSplitValues = $this->request->getPost('splitValues');

        $splitValues = json_decode($jsonSplitValues, true);
        $condition = '';
        if (isset($splitValues)) {
            $employee_id = $splitValues[0][2];
            $month_dt = $splitValues[0][3];
            $condition =  array('employee_id' => $employee_id, 'month_dt' => $month_dt);
        }
        $emp_track_dts = $this->LmsModel->get_all_details(EMPLOYEE_TRACKER, $condition);

        $groupedData = [];

        foreach ($splitValues as $data) {
            // echo $data[3];
            $employeeEmail = $data[1];
            $employeeId = $data[2];
            $date = $data[3];
            $categoryCode = $data[0];

            // Check if the employee's data exists in $groupedData
            if (!isset($groupedData[$employeeEmail][$employeeId])) {
                $groupedData[$employeeEmail][$employeeId] = [
                    'employee_email' => $employeeEmail,
                    'employee_id' => $employeeId,
                    'date' => [],
                    'month_dt' => $date,
                ];
            }

            // Add the category code to the corresponding date in the category_code array
            // $groupedData[$employeeEmail][$employeeId]['category_code'][$date] = $categoryCode;
            $groupedData[$employeeEmail][$employeeId]['date'][$date] = $categoryCode;
        }
        //    echo"<pre>"; print_r($groupedData);die;

        // Now $groupedData contains the desired format
        // Convert the category_code arrays to JSON format
        foreach ($groupedData as &$employeeData) {
            foreach ($employeeData as &$data) {
                $data['date'] = json_encode($data['date']);
            }
        }
        //  echo"<pre>";print_r($groupedData);die;

        foreach ($groupedData as $employeeEmail => $employeeData) {
            foreach ($employeeData as $employeeId => $data) {
                // Prepare the data for insertion
                $insertData = [
                    'employee_email' => $data['employee_email'],
                    'employee_id' => $data['employee_id'],
                    'date' => $data['date'],
                    'month_dt' => $data['month_dt'],
                ];

                // Insert the data into the database
                // $this->EmployeeModel->insert($insertData);
            }
            if ($emp_track_dts->getNumRows() == 1) {
                $response = array(
                    "status" => '1',
                    "msg" => 'update'
                );
                // $returnArr = $response;
                $this->session->setFlashdata('success_message', 'Tracker Update successfully');
                $this->LmsModel->update_details(EMPLOYEE_TRACKER, $insertData, $condition);
            } else {
                $response = array(
                    "status" => '1',
                    "msg" => 'insert'
                );
                $this->session->setFlashdata('success_message', 'Tracker Added successfully');
                $this->LmsModel->simple_insert(EMPLOYEE_TRACKER, $insertData);
            }
        }
        echo json_encode($response);
        exit();
    }
}
