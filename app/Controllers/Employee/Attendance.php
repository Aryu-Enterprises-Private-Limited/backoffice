<?php

namespace App\Controllers\Employee;

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
        if ($this->checkSession('E') != '') {
            $this->data['title'] = 'Attendance';
            $this->data['atttitle'] = 'Attendance List';
            $currentDate = date('m/d/Y');
            $employe_id = session()->get(APP_NAME . '_session_employee_id');
            $condition = array('att_current_date' => $currentDate, 'employee_id' => $employe_id);
            $this->data['att_details'] = $this->LmsModel->get_all_details(EMPLOYEE_ATTENDANCE, $condition, 'desc')->getResult();
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
            $ipAddress = $this->request->getIPAddress();
            $delimiter = "-";
            $parts = explode($delimiter, $curr_date_time);

            $employee_id =   $this->session->get(APP_NAME . '_session_employee_id');
            $employee_email = $this->session->get(APP_NAME . '_session_employee_email');
            $employee_name = $this->session->get(APP_NAME . '_session_employee_name');
            $fSubmit = FALSE;
            if ($curr_date_time != '' && $reason != '') {
                $dataArr = array(
                    'employee_name' => $employee_name,
                    'employee_email' => $employee_email,
                    'employee_id' => $employee_id,
                    'att_current_date' => trim($parts[0]),
                    'att_current_time' => trim($parts[1]),
                    'reason' => $reason,
                    'ip_address' => $ipAddress,
                );

                if (isset($reason) && $reason == 'logout') {
                    $currentDate = date('m/d/Y');
                    $condition = array('att_current_date' => $currentDate, 'employee_id' => $employee_id);
                    $groupby = $this->LmsModel->group_by_tbl($condition)->getRow();

                    $reason = $groupby->concatenated_reasons;
                    $time = $groupby->concatenated_current_time;

                    $att_reason = explode("~", $reason);
                    $att_time = explode("~", $time);
                    date_default_timezone_set('Asia/Kolkata');

                    $currentTime = date("h:i:s A");

                    $key1 = array_search('login', $att_reason);
                    $key2 = array_search('break_out', $att_reason);
                    $key3 = array_search('break_in', $att_reason);
                    $key4 = '3';

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
                        $logout = $currentTime;
                    }
                    if (isset($login) && isset($logout)) {
                        $login = $login;
                        $logout = $logout;

                        // Create DateTime objects with AM/PM format
                        $loginTime = DateTime::createFromFormat('h:i:s A', $login);
                        $logoutTime = DateTime::createFromFormat('h:i:s A', $logout);

                        // Calculate the time difference
                        $timeDifference = $logoutTime->diff($loginTime);

                        // Format the time difference as "hh:mm:ss"
                        $login_out_diff = $timeDifference->format('%H:%I:%S');
                        $login_out_diff2 = $timeDifference->format('%H:%I');

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

                        $total_hrs  = $login_out_diff;
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
                            // $timeDifferenceFormatted = sprintf("%02d:%02d", $hours, $minutes);
                            $total_hrs = $timeDifferenceFormatted;
                        }
                    }

                    $parts_arr = explode(":", $total_hrs);
                    $seconds = $parts_arr[0] * 3600 + $parts_arr[1] * 60 + $parts_arr[2];
                    $currentDate = date("Y-m-d");
                    $newArr = array(
                        'employee_name' => $employee_name,
                        'employee_email' => $employee_email,
                        'employee_id' => $employee_id,
                        'att_current_date' => $currentDate,
                        'total_hrs' => $seconds,
                    );
                    $this->LmsModel->simple_insert(EMPLOYEE_ATTENDANCE_TOTAL_HOURS, $newArr);
                }
                $this->LmsModel->simple_insert(EMPLOYEE_ATTENDANCE, $dataArr);
                $this->session->setFlashdata('success_message', 'Attendance details added successfully.');
                $fSubmit = TRUE;
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

        $ajaxDataArr = $this->LmsModel->get_all_details(EMPLOYEE_ATTENDANCE, $condition, $sortArr, $rowperpage, $row_start, $likeArr);

        $tblData = array();
        $position = 1;

        foreach ($ajaxDataArr->getResult() as $row) {
            $tblData[] = array(
                // 'DT_RowId' => (string)$rowId,
                // 'checker_box' => '<input class="checkRows" name="checkbox_id[]" type="checkbox" value="' . $rowId . '">',
                'employee_name' => $row->employee_name,
                'employee_email' => $row->employee_email,
                'att_current_date' => $row->att_current_date,
                "att_current_time" =>  $row->att_current_time,
                "reason" =>  str_replace("_", " ", ucfirst($row->reason)),
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
