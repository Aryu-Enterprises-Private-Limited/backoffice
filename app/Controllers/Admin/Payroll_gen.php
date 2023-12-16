<?php

namespace App\Controllers\Admin;


use App\Controllers\BaseController;

class Payroll_gen extends BaseController
{

    public function __construct()
    {
        $this->session = session();
        $this->LmsModel = new \App\Models\LmsModel();
    }

    public function generate_payroll()
    {
        if ($this->checkSession('A') != '') {
            $daterange = $this->request->getGetPost('month');

            $this->data['title'] = 'Generate Payroll';
            $sortArr = array('first_name' => 'asc');
            $this->data['employee_dts'] = $this->LmsModel->get_selected_fields(EMPLOYEE_DETAILS, ['status' => '1', 'is_deleted' => '0'], ['id', 'employeeid', 'first_name', 'last_name'], $sortArr)->getResult();

            $condition3 = array('is_deleted' => '0', 'status' => '1');
            $this->data['pay_details'] = $att_cat_details = $this->LmsModel->get_all_details(PAY, $condition3)->getResult();

            $firstDayOfMonth = date('Y-m-01');
            if (isset($daterange) && $daterange != '') {
                $firstDayOfMonth = date("Y-m-01", strtotime($daterange));
            }
            // print_r($firstDayOfMonth);
            $condition5 = array('date' => $firstDayOfMonth);
            $pay_roll_Counts = $this->LmsModel->get_all_counts(PAYROLL_REPORT, $condition5);
            $this->data['disable_btn'] = '';
            if ($pay_roll_Counts > 0) {
                $this->data['disable_btn'] = 'disableMe';
            }


            $this->data['month_totl_days'] = date('t', strtotime($firstDayOfMonth));
            // echo $numDays;die;
            $cond = array('month_dt' => $firstDayOfMonth);
            $this->data['emp_track_dts'] = $emp_track_dts =  $this->LmsModel->get_all_details(EMPLOYEE_TRACKER, $cond)->getResult();

            echo view(ADMIN_PATH . '/payroll_gen/add_generate', $this->data);
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/' . ADMIN_PATH);
        }
    }


    public function insertUpdate()
    {
        if ($this->checkSession('A') != '') {
            $employee_id = $this->request->getPostGet('id');
            $employee_name = $this->request->getPostGet('employee_name');
            $month_sal = $this->request->getPostGet('month_sal');
            $present_cnt = $this->request->getPostGet('present_cnt');
            $absent_cnt = $this->request->getPostGet('absent_cnt');
            $take_home = $this->request->getPostGet('take_home');
            $date = (string)$this->request->getPostGet('date');
            $month = (string)$this->request->getPostGet('month');

            $fSubmit = FALSE;
            if ($employee_id != '' && $employee_name != '' && $month_sal != '' && $present_cnt != '' && $absent_cnt != '' && $take_home != '' && $date != '' && $month != '') {

                $dataArr = [];
                for ($i = 0; $i < count($employee_id); $i++) {
                    $dataArr[] = [
                        'employee_id' => $employee_id[$i],
                        'employee_name' => $employee_name[$i],
                        'month_salary' => $month_sal[$i],
                        'no_present_day' => $present_cnt[$i],
                        'no_absent_day' => $absent_cnt[$i],
                        'take_home' => $take_home[$i], // Assuming this holds the 'take home' value
                        'date' => $date,
                        'month' => $month
                    ];
                    // $this->LmsModel->simple_insert(PAYROLL_REPORT, $dataArr);
                }
                foreach ($dataArr as $ins_data) {
                    $this->LmsModel->simple_insert(PAYROLL_REPORT, $ins_data);
                }
                $this->session->setFlashdata('success_message', 'payroll generate successfully.');
                $fSubmit = TRUE;
            } else {
                $this->session->setFlashdata('error_message', 'Form data is missing.');
            }
            if ($fSubmit == TRUE) {
                $url = ADMIN_PATH . '/payroll_gen/generate_payroll';
            }
            return redirect()->to("$url");
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/' . ADMIN_PATH);
        }
    }

    public function payroll_report()
    {
        if ($this->checkSession('A') != '') {
            $this->data['title'] = 'Payroll Report';
            echo view(ADMIN_PATH . '/payroll_gen/payroll_report', $this->data);



            // $daterange = $this->request->getGetPost('month');

            // $this->data['title'] = 'Payroll Report';
            // $this->data['employee_dts'] = $this->LmsModel->get_selected_fields(EMPLOYEE_DETAILS, ['status' => '1', 'is_deleted' => '0'], ['id', 'employeeid', 'first_name', 'last_name'])->getResult();

            // $condition3 = array('is_deleted' => '0', 'status' => '1');
            // $this->data['pay_details'] = $att_cat_details = $this->LmsModel->get_all_details(PAY, $condition3)->getResult();

            // $firstDayOfMonth = date('Y-m-01');
            // if (isset($daterange) && $daterange != '') {
            //     $firstDayOfMonth = date("Y-m-01", strtotime($daterange));
            // }
            // $cond = array('month_dt' => $firstDayOfMonth);
            // $this->data['emp_track_dts'] = $emp_track_dts =  $this->LmsModel->get_all_details(EMPLOYEE_TRACKER, $cond)->getResult();

            // echo view(ADMIN_PATH . '/Payroll_gen/payroll_report', $this->data);
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/' . ADMIN_PATH);
        }
    }

    public function list_ajax($returnType = 'json')
    {
        $daterange = $this->request->getGetPost('month');
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
        $firstDayOfMonth = date('Y-m-01');
        if (isset($daterange) && $daterange != '') {
            $firstDayOfMonth = date("Y-m-01", strtotime($daterange));
        }
        $condition = array('date' =>  $firstDayOfMonth);
        if ($dtSearchKeyVal != '') {
            $likeArr = array(
                'employee_name' => trim($dtSearchKeyVal),
            );
        }

        $totCounts = $this->LmsModel->get_all_counts(PAYROLL_REPORT, $condition, '', $likeArr);
        $sortArr = array('id' => -1);
        if ($sortField != '') {
            $sortArr = array($sortField => $sortJob);
        }
        $ajaxDataArr = $this->LmsModel->get_all_details(PAYROLL_REPORT, $condition, $sortArr, $rowperpage, $row_start, $likeArr);

        $tblData = array();
        $x = 1;
        foreach ($ajaxDataArr->getResult() as $row) {
            // $cond = array('id' => $row->department_id);
            // $dept_name = $this->LmsModel->get_selected_fields(DEPARTMENT_DETAILS, $cond)->getRow();
            $rowId =  (string)$row->id;
            $disp_status = 'Unpaid';
            $actTitle = 'Click to Paid';
            $mode = 1;
            $btnColr = 'btn-danger';
            if (isset($row->payment_status) && $row->payment_status == 'paid') {
                $disp_status = 'Paid';
                $mode = 0;
                $btnColr = 'btn-success';
                $actTitle = 'Click to Unpaid';
            }
            $statusTxt = $actTitle;

            $statusTxt =  '<a data-toggle="tooltip" data-original-title="' . $actTitle . '" class="stsconfirm" href="javascript:void(0);" data-row_id="' . $rowId . '" data-act_url="/' . ADMIN_PATH . '/payroll_gen/change-status" data-stsmode="' . $mode . '"> <button type="button" class="btn ' . $btnColr . ' btn-sm waves-effect waves-light">' . $disp_status . '</button></a>';

            $notestxt =  '<button type="button" class="btn btn-info pay_notes" data-act_url="/' . ADMIN_PATH . '/payroll_gen/get-notes_dts"  data-pay_id="' . $rowId . '"> view </button>';

            $empl_name = '<a href="/' . ADMIN_PATH . '/employee/view/' . $row->employee_id . '" class="text-decoration-none">' . ucfirst($row->employee_name) . '</a>';
            $formatted_date = '';
            if (isset($row->paid_date) && $row->paid_date != '') {
                $dbDate = $row->paid_date; // Your date
                $formatted_date = date('d/m/Y', strtotime($dbDate));
            }
            $tblData[] = array(
                'DT_RowId' => (string)$rowId,
                'checker_box' => '<input class="checkRows" name="checkbox_id[]" type="checkbox" value="' . $rowId . '" data-stsmode="' . $mode . '">',
                's_no' => $x,
                'employee_name' => $empl_name,
                'month_salary' => $row->month_salary,
                'no_present_day' => $row->no_present_day,
                "no_absent_day" =>  $row->no_absent_day,
                "take_home" =>  number_format($row->take_home, 2),
                "payment_status" =>  $statusTxt,
                "paid_date" =>  isset($formatted_date) && $formatted_date != '' ? ($formatted_date) : '-',
                "notes" =>  $notestxt,

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
    public function get_notes_dts()
    {

        $pay_id = (string)$this->request->getPostGet('pay_id');
        $condition = array('id' => $pay_id);
        $payroll_report_notes = $this->LmsModel->get_selected_fields(PAYROLL_REPORT, $condition)->getRow();
        $html = '';
        if (isset($payroll_report_notes->notes) && $payroll_report_notes->notes != '') {
            $decode = json_decode($payroll_report_notes->notes);
            $html .='<div class=" row">
                            <input type="hidden" id="pay_roll_id" name="pay_roll_id" value="'.$pay_id.'">
                            <label class="col-sm-2 col-form-label fw-bold">Notes <span class="text-danger">*</span></label>
                        <div class="col-sm-10 input-container">
                        <div class="input-group  control-group after-add-more col-sm-10">
                        <input type="text" name="addmore[]" class="form-control" id="addmore" placeholder="Notes" value="">
                        <div class="input-group-btn">
                        <button class="btn btn-success add-more" type="button"><i class="glyphicon glyphicon-plus"></i> Add</button>
                    </div>
                            </div>';
                                foreach ($decode as $data) {
                                    if ($data != '') {
                                        $html .= '<div class="mb-3 row copy hide_show">
                                            <div class="col-sm-10 ">
                                                <div class="control-group input-group input-grp removemore" style="margin-top:10px">
                                                    <input type="text" name="addmore[]" class="form-control" value="' .  $data.'">
                                                    <div class="input-group-btn">
                                                        <button class="btn btn-danger remove" type="button"><i class="glyphicon glyphicon-remove"></i> Remove</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>';
                                     }
                             }
                             $html .=  '</div>
                    </div>';
        } else {
            $html .= '<div class="row">
                <input type="hidden" id="pay_roll_id" name="pay_roll_id" value="'.$pay_id.'">
                <label class="col-sm-2 col-form-label fw-bold">Notes <span class="text-danger">*</span></label>
                <div class="col-sm-10 input-container">
                    <div class="input-group  control-group after-add-more col-sm-10">
                        <input type="text" name="addmore[]" class="form-control" id="addmore" placeholder="Notes" value="">
                        <div class="input-group-btn">
                            <button class="btn btn-success add-more" type="button"><i class="glyphicon glyphicon-plus"></i> Add</button>
                        </div>
                    </div>
                </div>
            </div>';
        }

        echo json_encode($html);
        exit;
    }

    public function update_notes(){
        $pay_roll_id = $this->request->getPostGet('pay_roll_id');
        $pay_roll_notes = array_values($this->request->getPostGet('addmore'));
        $array_fil = array_filter($pay_roll_notes);
        $response = '0';
        if(isset($array_fil) && !empty($array_fil)){
            $dataArr['notes'] = json_encode($array_fil);
            $condition = array('id' => $pay_roll_id);
            $this->LmsModel->update_details(PAYROLL_REPORT, $dataArr, $condition);
            $this->session->setFlashdata('success_message', 'Payroll Notes added Successfully');
            $response = '1';
        }else{
            $response = '0';
        }
        echo json_encode($response);
        exit;
    }

    public function update_status()
    {
        if ($this->checkSession('A') != '') {
            $returnArr['status'] = '0';
            $returnArr['response'] = 'Failed to updated, Please try again';
            if ($this->checkSession('A') == '') {
                $returnArr['status'] = '00';
                $returnArr['response'] = 'Session has been timed out, Please login again and try.';
            } else {
                $mode = $this->request->getPostGet('mode');
                $id = $this->request->getPostGet('record_id');
                $status = ($mode == '0') ? 'un_paid' : 'paid';
                $currentDateTime = new \DateTime();
                $currentDate = $currentDateTime->format('Y-m-d');
                $newdata = array('payment_status' => $status, 'paid_date' => $currentDate);
                $condition = array('id' => $id);
                $this->LmsModel->update_details(PAYROLL_REPORT, $newdata, $condition);
                $returnArr['status'] = '1';
                $returnArr['response'] = 'Payment Status Changed Successfully';
            }
            echo json_encode($returnArr);
            exit;
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/' . ADMIN_PATH);
        }
    }

    public function update_payment_status()
    {
        if ($this->checkSession('A') != '') {
            $mode = $this->request->getPostGet('stsmode');
            $ids = $this->request->getPostGet('checkedValues_id');

            if (!empty($ids) && !empty($mode)) {
                // Loop through the IDs and modes
                foreach ($ids as $key => $id) {
                    $condition = ['id' => $id];
                    $currentDate = date('Y-m-d');

                    // Determine the payment status based on the mode value
                    $status = ($mode[$key] == 0) ? 'un_paid' : 'paid';

                    // Prepare data for updating payment status
                    $newdata = [
                        'payment_status' => $status,
                        'paid_date' => $currentDate
                    ];
                    // echo"<pre>";
                    // print_r($condition);
                    // Update each ID with its respective mode value
                    $this->LmsModel->update_details(PAYROLL_REPORT, $newdata, $condition);
                }
                $this->session->setFlashdata('success_message', 'Payment Status Changed Successfully');
                $message = 'success';
            } else {
                $message = 'fail';
                $this->session->setFlashdata('error_message', 'Form data is missing.');
            }
            return json_encode($message);
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/' . ADMIN_PATH);
        }
    }
}
