<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use DateTime;
use DateInterval;

class Report extends BaseController
{

    public function __construct()
    {
        $this->session = session();
        $this->LmsModel = new \App\Models\LmsModel();
    }

    public function index()
    {
        if ($this->checkSession('A') != '') {
            $this->data['title'] = 'Report List';
            $this->data['employee_details'] = $this->LmsModel->get_selected_fields(EMPLOYEE_DETAILS, ['is_deleted' => '0',], ['id', 'first_name', 'last_name'])->getResult();
            echo view(ADMIN_PATH . '/report/list', $this->data);
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/' . ADMIN_PATH);
        }
    }

    public function list_ajax($returnType = 'json')
    {
        $daterange = $this->request->getGetPost('daterange');
        $employee_id = $this->request->getGetPost('employee_name');
        // echo $employee_id;die;
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
        $condition = array('att_current_date' => $currentDate);
        // print_r($condition);die;
        if ($dtSearchKeyVal != '') {
            $likeArr = array(
                'employee_name' => trim($dtSearchKeyVal),
                'employee_email' => strtolower(trim($dtSearchKeyVal)),
            );
        }

        if (isset($daterange) && !empty($daterange)) {
            if (!empty($daterange)) {
                $condition['att_current_date'] = $daterange;
            }
        }
        if (isset($employee_id) && !empty($employee_id)) {
            if (!empty($employee_id)) {
                $condition['employee_id'] = $employee_id;
            }
        }

        $totCounts = $this->LmsModel->group_count_tbl(EMPLOYEE_ATTENDANCE, $condition, $likeArr);

        // $totCounts = $this->LmsModel->get_all_counts(EMPLOYEE_ATTENDANCE, $condition, '', $likeArr);
        $sortArr = array('dt' => -1);
        if ($sortField != '') {
            $sortArr = array($sortField => $sortJob);
        }

        // $ajaxDataArr = $this->LmsModel->get_all_details(EMPLOYEE_ATTENDANCE, $condition, $sortArr, $rowperpage, $row_start, $likeArr);

        $groupby = $this->LmsModel->group_by_tbl($condition, $sortArr, $rowperpage, $row_start, $likeArr);

        $tblData = array();

        foreach ($groupby->getResult() as $data) {
            $reason = $data->concatenated_reasons;
            $time = $data->concatenated_current_time;

            $att_reason = explode("~", $reason);
            $att_time = explode("~", $time);

            $key1 = array_search('login', $att_reason);
            $key2 = array_search('break_out', $att_reason);
            $key3 = array_search('break_in', $att_reason);
            $key4 = array_search('logout', $att_reason);


            // $value1 ='';
            // $value2 = '';
            // $value3 ='';
            // $value4='';
            if ($key1 !== false) {
                $login = $att_time[$key1];
            }
            if ($key2 !== false) {
                $break_out = $att_time[$key2];
            }
            if ($key3 !== false) {
                $break_in = $att_time[$key3];
            }
            if ($key4 !== false) {
                $logout = $att_time[$key4];
            }

            if (isset($login) && isset($logout)) {
                /*$timeString = $login;
                $login = explode(' ', $timeString);

                $timeString2 = $logout;
                $logout = explode(' ', $timeString2);

                $loginTime = $login[0];
                $logoutTime = $logout[0];

                // Convert time strings to seconds
                $loginTimeInSeconds = strtotime($loginTime);
                $logoutTimeInSeconds = strtotime($logoutTime);

                // Calculate the time difference in seconds
                $timeDifferenceInSeconds = $logoutTimeInSeconds - $loginTimeInSeconds;

                // Format the time difference as "hh:mm:ss"
                $hours = floor($timeDifferenceInSeconds / 3600);
                $minutes = floor(($timeDifferenceInSeconds % 3600) / 60);
                $seconds = $timeDifferenceInSeconds % 60;

                // Create the formatted time difference string
                $login_out_diff = sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);

                   echo "Time Difference: " . $login_out_diff;*/
                $login = $login;
                $logout = $logout;

                // Create DateTime objects with AM/PM format
                $loginTime = DateTime::createFromFormat('h:i:s A', $login);
                $logoutTime = DateTime::createFromFormat('h:i:s A', $logout);

                // Calculate the time difference
                $timeDifference = $logoutTime->diff($loginTime);

                // Format the time difference as "hh:mm:ss"
                $login_out_diff = $timeDifference->format('%H:%I:%S');

                // echo "Time Difference: " . $login_out_diff;



                $break_diff = '';
                if (isset($break_out) && isset($break_in)) {
                    $timeString3 = $break_out;
                    $break_out = explode(' ', $timeString3);

                    $timeString4 = $break_in;
                    $break_in = explode(' ', $timeString4);

                    $break_outTime = $break_out[0];
                    $break_inTime = $break_in[0];

                    // Convert time strings to seconds
                    $breakinTimeInSeconds = strtotime($break_outTime);
                    $breakoutTimeInSeconds = strtotime($break_inTime);

                    // Calculate the time difference in seconds
                    $timeDiffInSeconds = $breakoutTimeInSeconds - $breakinTimeInSeconds;

                    // Format the time difference as "hh:mm:ss"
                    $hours = floor($timeDiffInSeconds / 3600);
                    $minutes = floor(($timeDiffInSeconds % 3600) / 60);
                    $seconds = $timeDiffInSeconds % 60;

                    // Create the formatted time difference string
                    $break_diff = sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
                }

                $timeDifferenceFormatted = $login_out_diff;
                if (isset($break_diff) && $break_diff != '' && isset($login_out_diff) && $login_out_diff != '') {
                    $logoutTime = $login_out_diff;
                    $totalBreakTime = $break_diff;

                    // Convert time strings to seconds
                    $logoutTimeInSeconds = strtotime($logoutTime);
                    $totalBreakTimeInSeconds = strtotime($totalBreakTime);

                    // Calculate the time difference in seconds
                    $timeDifferenceInSeconds = $logoutTimeInSeconds - $totalBreakTimeInSeconds;

                    // Ensure the result is positive
                    if ($timeDifferenceInSeconds < 0) {
                        $timeDifferenceInSeconds = 0;
                    }

                    // Format the time difference as "hh:mm:ss"
                    $hours = floor($timeDifferenceInSeconds / 3600);
                    $minutes = floor(($timeDifferenceInSeconds % 3600) / 60);
                    $seconds = $timeDifferenceInSeconds % 60;

                    // Create the formatted time difference string
                    $timeDifferenceFormatted = sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);

                    //  echo "Time Difference: " . $timeDifferenceFormatted;
                }
                //  echo $login_out_diff;
                $tblData[] = array(
                    'employee_name' => $data->employee_name,
                    'employee_email' =>  $data->employee_email,
                    'att_current_date' => $data->att_current_date,
                    'id' => $login_out_diff,
                    'reason' => $break_diff,
                    'att_current_time' => $timeDifferenceFormatted,
                    // 'id' => '0',

                );
            } else if (isset($login) && !isset($logout)) {
                $tblData[] = array(
                    'employee_name' => $data->employee_name,
                    'employee_email' =>  $data->employee_email,
                    'att_current_date' => $data->att_current_date,
                    'id' => 'W',
                    'reason' => 'W',
                    'att_current_time' => 0,
                );
            } else {
                $tblData[] = array(
                    'employee_name' => $data->employee_name,
                    'employee_email' =>  $data->employee_email,
                    'att_current_date' => $data->att_current_date,
                    'id' => 0,
                    'reason' => 0,
                    'att_current_time' => 0,
                );
            }
            unset($login, $break_out, $break_in, $logout, $break_diff, $login_out_diff);
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

    public function monthly_list_ajax()
    {
        $currentMonth = date('F');
        $currentYear = date('Y');
        $this->data['title'] = 'Monthly Report List';
        $this->data['dates'] = $datesForCurrentMonth = $this->generateDatesForMonth($currentMonth, $currentYear);
        echo view(ADMIN_PATH . '/report/monthly_list', $this->data);

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
