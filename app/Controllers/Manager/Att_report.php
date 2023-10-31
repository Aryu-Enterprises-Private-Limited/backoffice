<?php

namespace App\Controllers\Manager;

use App\Controllers\BaseController;
use \DateTime;

class Att_report extends BaseController
{
    public function __construct()
    {
        $this->session = session();
        $this->LmsModel = new \App\Models\LmsModel();
    }

    public function list()
    {
        if ($this->checkSession('M') != '') {
            $this->data['title'] = 'Attendance Report';

            $currentMonth = date('F');
            $currentYear = date('Y');
            $month = $this->request->getGetPost('month');
            $year_filter = $this->request->getPostGet('year');
            if (isset($month) && $month != '') {
                $timestamp = strtotime($month); // Convert the date string to a timestamp
                $currentMonth = date("F", $timestamp);
                $currentYear = date("Y", $timestamp);
            }



            $cond = array('is_deleted' => 0);
            $this->data['emp_details'] = $emp_details = $this->LmsModel->get_selected_fields(EMPLOYEE_DETAILS, $cond, ['id', 'email', 'first_name', 'last_name', 'employeeid'])->getResult();

            $condition3 = array('is_deleted' => '0', 'status' => '1');
            $this->data['att_cat_details'] = $att_cat_details = $this->LmsModel->get_all_details(ATTENDANCE_CATEGORY, $condition3)->getResult();


            $att_report = array();
            if (isset($emp_details) && !empty($emp_details)) {
                foreach ($emp_details as $emp_data) {
                    $emp_data_id = $emp_data->id;
                    $monthName = $currentMonth;
                    $monthNumber = date('m', strtotime($monthName));
                    $month = $monthNumber;
                    $year = $currentYear;

                    $firstDayOfMonth = date('Y-m-d', strtotime("{$year}-{$month}-01"));

                    $lastDayOfMonth = date('Y-m-t', strtotime("{$year}-{$month}-01"));
                    $condition = array('employee_id' => $emp_data_id);
                    $condition['att_current_date >='] = $firstDayOfMonth;
                    $condition['att_current_date <='] = $lastDayOfMonth;


                    $condition2 = array('month_dt' => $firstDayOfMonth, 'employee_id' => $emp_data_id);
                    if (isset($year_filter) && !empty($year_filter)) {
                        if (!empty($year_filter)) {
                            // $condition = array('employee_id' => $emp_data_id);
                            // $condition['att_current_date'] = $year_filter;
                            // $condition2 = array('month_dt' => $year_filter, 'employee_id' => $emp_data_id);

                            

                            $yearDate = DateTime::createFromFormat('Y', $year_filter);

                            // Get the first date of the year
                            $firstDateOfYear = new DateTime($yearDate->format('Y-01-01'));

                            // Get the last date of the year
                            $lastDateOfYear = new DateTime($yearDate->format('Y-12-31'));

                            // // Create a DateTime object for the current date
                            // $currentDate = new DateTime();

                            // Format the dates as needed (e.g., as strings)
                            $firstDateOfYearFormatted = $firstDateOfYear->format('Y-m-d');
                            $lastDateOfYearFormatted = $lastDateOfYear->format('Y-m-d');

                            $condition = array('employee_id' => $emp_data_id);
                            $condition['att_current_date >='] = $firstDateOfYearFormatted;
                            $condition['att_current_date <='] = $lastDateOfYearFormatted;

                            $condition2 = array('employee_id' => $emp_data_id);
                            $condition2['month_dt >='] = $firstDateOfYearFormatted;
                            $condition2['month_dt <='] = $lastDateOfYearFormatted;
                        }
                    }
                    //  print_r($condition2);
                    $emp_tracker = $this->LmsModel->get_all_details(EMPLOYEE_TRACKER, $condition2)->getResult();

                    $att_Counts = $this->LmsModel->get_all_counts(EMPLOYEE_ATTENDANCE_TOTAL_HOURS, $condition);


                    $tracker_dt = array();
                    if (isset($emp_tracker)) {
                        foreach ($emp_tracker as $track_details) {
                            $tracker_dt = json_decode($track_details->date);
                        }
                    }
                    // $cat_data =array();
                    // foreach($att_cat_details as $details){
                    //     $cat_data = $details->category_code;
                    // }
                    // print_r($cat_data);
                    $statusCounts = array(
                        'P' => 0,
                        'L' => 0,
                        'A' => 0,
                        'PH' => 0,
                        'HD' => 0,
                        'ML/PL' => 0,
                        'OT' => 0,
                        'WH' => 0,
                        'CO' => 0,
                        'WO' => 0,
                        'WFH' => 0
                    );
                    // $statusCounts = array(
                    //     "'$cat_data'" => 0,
                    // );
                    // print_r($statusCounts);
                    foreach ($tracker_dt as $date => $status) {
                        switch ($status) {
                            case 'P':
                                $statusCounts['P']++;
                                break;
                            case 'L':
                                $statusCounts['L']++;
                                break;
                            case 'A':
                                $statusCounts['A']++;
                                break;
                            case 'PH':
                                $statusCounts['PH']++;
                                break;
                            case 'HD':
                                $statusCounts['HD']++;
                                break;
                            case 'ML/PL':
                                $statusCounts['ML/PL']++;
                                break;
                            case 'OT':
                                $statusCounts['OT']++;
                                break;
                            case 'WH':
                                $statusCounts['WH']++;
                                break;
                            case 'CO':
                                $statusCounts['CO']++;
                                break;
                            case 'WO':
                                $statusCounts['WO']++;
                                break;
                            case 'WFH':
                                $statusCounts['WFH']++;
                                break;
                                // Add more cases for other statuses if needed
                        }
                    }
                    // print_r($statusCounts);
                    // Access the counts
                    // $countP = $statusCounts['P'];
                    // $countWO = $statusCounts['WO'];
                    // $countPH = $statusCounts['PH'];

                    // // Print the counts
                    // echo "Count of P: $countP<br>";
                    // echo "Count of WO: $countWO<br>";
                    // echo "Count of PH: $countPH<br>";


                    $employeeData = [
                        'first_name' => $emp_data->first_name,
                        'last_name' => $emp_data->last_name,
                        'employeeid' => $emp_data->employeeid,
                        'att_Counts' => $att_Counts,
                        // 'emp_tracker' => $tracker_dt,
                        'emp_tracker' => $statusCounts,
                    ];
                    $att_report[$emp_data_id] = $employeeData;
                }
            }
            //             echo"<pre>";
            // print_r($att_report);die;
            $this->data['att_report'] = $att_report;
            $this->data['dates'] = $datesForCurrentMonth = $this->generateDatesForMonth($currentMonth, $currentYear);


            echo view(MANAGER_PATH . '/attendance/att_report', $this->data);
        } else {
            $this->data['title'] = 'Manager Login';
            return view(MANAGER_PATH . '/pages/login', $this->data);
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
}
