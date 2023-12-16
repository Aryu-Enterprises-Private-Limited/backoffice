<?php

namespace App\Controllers\Admin;


use App\Controllers\BaseController;

class Fees extends BaseController
{

    public function __construct()
    {
        $this->session = session();
        $this->LmsModel = new \App\Models\LmsModel();
    }

    public function index()
    {
        if ($this->checkSession('A') != '') {
            $this->data['title'] = 'Fees List';
            echo view(ADMIN_PATH . '/fees/list', $this->data);
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/' . ADMIN_PATH);
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
        $condition = array('is_deleted' => '0');
        if ($dtSearchKeyVal != '') {
            $likeArr = array(
                'student_name' => trim($dtSearchKeyVal),
                'course_name' => trim($dtSearchKeyVal),
                'total_fee' => trim($dtSearchKeyVal),
                'no_of_installment' => trim($dtSearchKeyVal),
                // 'amount_paid' => trim($dtSearchKeyVal),
                // 'bal_due' => trim($dtSearchKeyVal),

            );
        }

        $totCounts = $this->LmsModel->get_all_counts(FEES, $condition, '', $likeArr);
        $sortArr = array('id' => -1);
        if ($sortField != '') {
            $sortArr = array($sortField => $sortJob);
        }
        $ajaxDataArr = $this->LmsModel->get_all_details(FEES, $condition, $sortArr, $rowperpage, $row_start, $likeArr);


        $tblData = array();
$x =1;
        foreach ($ajaxDataArr->getResult() as $row) {
            $rowId =  (string)$row->id;
            $disp_status = 'Inactive';
            $actTitle = 'Click to active';
            $mode = 1;
            $btnColr = 'btn-danger';
            if (isset($row->status) && $row->status == '1') {
                $disp_status = 'Active';
                $mode = 0;
                $btnColr = 'btn-success';
                $actTitle = 'Click to inactivate';
            }
            $statusTxt = $actTitle;
            $actionTxt = '';

            $actionTxt = '<a class="btn btn-icon text-info" href="/' . ADMIN_PATH . '/fees/view/' . (string)$rowId . '"><i class="fas fa-eye"></i></a>';

            $statusTxt =  '<a data-toggle="tooltip" data-original-title="' . $actTitle . '" class="stsconfirm" href="javascript:void(0);" data-row_id="' . $rowId . '" data-act_url="/' . ADMIN_PATH . '/fees/change-status" data-stsmode="' . $mode . '"> <button type="button" class="btn ' . $btnColr . ' btn-sm waves-effect waves-light">' . $disp_status . '</button></a>';

            $actionTxt .= '<a class="btn btn-icon " href="/' . ADMIN_PATH . '/fees/edit/' . (string)$rowId . '"><i class="fas fa-edit"></i></a>';

            $notesTxt = '<a class="btn btn-icon text-primary" href="/' . ADMIN_PATH . '/fees/payment_details/' . (string)$rowId . '"><i class="fa fa-credit-card"></i>Billing</a>';

            $actionTxt .= '<a href="javascript:void(0);" class="delconfirm btn btn-icon text-danger" data-row_id="' . $rowId . '" data-act_url="/' . ADMIN_PATH . '/fees/delete"><i class="fas fa-trash-alt"></i></a>';


            $tblData[] = array(
                // 'DT_RowId' => (string)$rowId,
                // 'checker_box' => '<input class="checkRows" name="checkbox_id[]" type="checkbox" value="' . $rowId . '">',
                's_no' => $x,
                'date' => $row->date,
                'student_name' => ucfirst($row->student_name),
                'course_name' => $row->course_name,
                'total_fee' => $row->total_fee,
                'no_of_installment' => isset($row->no_of_installment) && $row->no_of_installment != '' ? ($row->no_of_installment) : '-',
                // 'amount_paid' => $row->amount_paid,
                // 'bal_due' => $row->bal_due,
                "bill_payment" =>  $notesTxt,
                "status" =>  $statusTxt,
                "action" =>  $actionTxt
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


    public function add_edit($id = "")
    {
        if ($this->checkSession('A') != '') {
            $uri = service('uri');
            $id = $uri->getSegment(4);
            $this->data['course_details'] = $this->LmsModel->get_selected_fields(COURSES, ['status' => '1', 'is_deleted' => '0'], ['id', 'course_name'])->getResult();
            $this->data['stu_details'] = $this->LmsModel->get_selected_fields(STUDENT_INFO, ['status' => '1', 'is_deleted' => '0'], ['id', 'first_name', 'last_name'])->getResult();
            if ($id != '') {
                $condition = array('is_deleted' => '0', 'id' => $id);
                $this->data['fee_info'] = $this->LmsModel->get_selected_fields(FEES, $condition)->getRow();
                if (!empty($this->data['fee_info'])) {
                    $this->data['title'] = 'Edit Fees Details';
                } else {
                    $this->session->setFlashdata('error_message', 'Couldnot find the Fees Details');
                    return redirect()->route(ADMIN_PATH . '/fees/list');
                }
            } else {
                $this->data['title'] = 'Add Fees Details';
            }
            echo view(ADMIN_PATH . '/fees/add_edit', $this->data);
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/' . ADMIN_PATH);
        }
    }

    public function payment_details($id = "")
    {
        if ($this->checkSession('A') != '') {
            $uri = service('uri');
            $id = $uri->getSegment(4);
            // $this->data['course_details'] = $this->LmsModel->get_selected_fields(COURSES, ['status' => '1', 'is_deleted' => '0'], ['id', 'course_name'])->getResult();
            // $this->data['stu_details'] = $this->LmsModel->get_selected_fields(STUDENT_INFO, ['status' => '1', 'is_deleted' => '0'], ['id', 'first_name','last_name'])->getResult();
            if ($id != '') {
                $condition = array('is_deleted' => '0', 'id' => $id);
                $this->data['fee_info'] = $this->LmsModel->get_selected_fields(FEES, $condition)->getRow();
                // print_r($this->data['fee_info']->student_id);die;
                $cond = array('student_id' => $this->data['fee_info']->student_id, 'course_id' => $this->data['fee_info']->course_id);
                $this->data['payment_info'] = $this->LmsModel->get_selected_fields(FEES_PAYMENT_DETAILS, $cond)->getRow();
                    // echo"<pre>"; print_r($this->data['payment_info']);die;
                if (!empty($this->data['fee_info'])) {
                    $this->data['title'] = 'Update Payment Details';
                } else {
                    $this->session->setFlashdata('error_message', 'Couldnot find the Fees Details');
                    return redirect()->route(ADMIN_PATH . '/fees/list');
                }
            } else {
                $this->data['title'] = 'Add Fees Details';
            }
            echo view(ADMIN_PATH . '/fees/fee_payment', $this->data);
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/' . ADMIN_PATH);
        }
    }

    public function insertUpdate_payment()
    {
        if ($this->checkSession('A') != '') {

            $date = (string)$this->request->getPostGet('date');
            $amount_paid = (string)$this->request->getPostGet('amount_paid');
            $id = (string)$this->request->getPostGet('id');
            $student_id = (string)$this->request->getPostGet('student_id');
            $student_name = (string)$this->request->getPostGet('student_name');
            $course_id = (string)$this->request->getPostGet('course_id');
            $course_name = (string)$this->request->getPostGet('course_name');

            $fSubmit = FALSE;
            if ($date != '' && $course_name != '' && $amount_paid != '') {

                $paymentDetails = [
                    $date => $amount_paid
                ];

                // Encode the array as JSON
                $paymentDetailsJSON = json_encode($paymentDetails);
                $dataArr = array(
                    'payment_details' => $paymentDetailsJSON,
                    'course_id' => $course_id,
                    'course_name' => $course_name,
                    'student_id' => $student_id,
                    'student_name' => $student_name,
                    // 'total_fee' => $total_fee,
                    // 'no_of_installment' => $no_of_installment,
                    // 'status' => $status,
                    // 'is_deleted' => '0',
                );
                if ($id == '') {
                    $this->LmsModel->simple_insert(FEES_PAYMENT_DETAILS, $dataArr);
                    $this->session->setFlashdata('success_message', 'Fees payment added successfully.');
                    $fSubmit = TRUE;
                } else {
                    $cond = array('id' => $id);
                    $fees_details = $this->LmsModel->get_selected_fields(FEES_PAYMENT_DETAILS, $cond)->getRow();
                    $existingPaymentDetailsJSON = $fees_details->payment_details;
                    $existingPaymentDetails = json_decode($existingPaymentDetailsJSON, true);
                    $existingPaymentDetails[$date] = $amount_paid;
                    $updatedPaymentDetailsJSON = json_encode($existingPaymentDetails);

                    $dataArr['payment_details'] =   $updatedPaymentDetailsJSON;
                    // print_r($dataArr);die;
                    $condition = array('id' => $id);
                    $this->LmsModel->update_details(FEES_PAYMENT_DETAILS, $dataArr, $condition);
                    $this->session->setFlashdata('success_message', 'Fees payment update successfully');
                    $fSubmit = TRUE;
                }
            } else {
                $this->session->setFlashdata('error_message', 'Form data is missing.');
            }
            if ($fSubmit) {
                $url = ADMIN_PATH . '/fees/list';
            } else {
                if ($id == '') $url = ADMIN_PATH . '/fees/add';
                else $url = ADMIN_PATH . '/fees/edit/' . $id;
            }
            return redirect()->to("$url");
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/' . ADMIN_PATH);
        }
    }

    public function insertUpdate()
    {
        if ($this->checkSession('A') != '') {
            $date = (string)$this->request->getPostGet('date');
            $course_name = (string)$this->request->getPostGet('course_name');
            $stu_details = (string)$this->request->getPostGet('stu_details');
            $total_fee = (string)$this->request->getPostGet('total_fee');
            $no_of_installment = (string)$this->request->getPostGet('no_of_installment');
            $status = (string)$this->request->getPostGet('status');
            $id = (string)$this->request->getPostGet('id');
            $str_arr = explode(",", $stu_details);
            $course_name_arr = explode(",", $course_name);
            if ($status == '') {
                $status = 'off';
            }

            $fSubmit = FALSE;
            if ($date != '' && $course_name != '' && $stu_details != '' && $total_fee != '') {
                if ($status == 'on') {
                    $status = '1';
                } else {
                    $status = '0';
                }
                $dataArr = array(
                    'date' => $date,
                    'course_id' => $course_name_arr[0],
                    'course_name' => $course_name_arr[1],
                    'student_id' => $str_arr[0],
                    'student_name' => $str_arr[1],
                    'total_fee' => $total_fee,
                    'no_of_installment' => $no_of_installment,
                    'status' => $status,
                    'is_deleted' => '0',
                );
                if ($id == '') {
                    $this->LmsModel->simple_insert(FEES, $dataArr);
                    $this->session->setFlashdata('success_message', 'Fees added successfully.');
                    $fSubmit = TRUE;
                } else {
                    $condition = array('id' => $id);
                    $this->LmsModel->update_details(FEES, $dataArr, $condition);
                    $this->session->setFlashdata('success_message', 'Fees update successfully');
                    $fSubmit = TRUE;
                }
            } else {
                $this->session->setFlashdata('error_message', 'Form data is missing.');
            }
            if ($fSubmit) {
                $url = ADMIN_PATH . '/fees/list';
            } else {
                if ($id == '') $url = ADMIN_PATH . '/fees/add';
                else $url = ADMIN_PATH . '/fees/edit/' . $id;
            }
            return redirect()->to("$url");
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/' . ADMIN_PATH);
        }
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
                $status = ($mode == '0') ? '0' : '1';
                $newdata = array('status' => $status);
                $condition = array('id' => $id);
                $this->LmsModel->update_details(FEES, $newdata, $condition);
                $returnArr['status'] = '1';
                $returnArr['response'] = 'Fees Status Changed Successfully';
            }
            echo json_encode($returnArr);
            exit;
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/' . ADMIN_PATH);
        }
    }

    public function view($id = "")
    {
        if ($this->checkSession('A') != '') {
            $uri = service('uri');
            $id = $uri->getSegment(4);
            if ($id != '') {
                $condition = array('id' => $id, 'is_deleted' => '0');
                $this->data['feeDetails'] = $this->LmsModel->get_all_details(FEES, $condition)->getRow();
                if (!empty($this->data['feeDetails'])) {
                    $this->data['title'] = 'Fees view';
                    echo view(ADMIN_PATH . '/fees/view', $this->data);
                } else {
                    $this->session->setFlashdata('error_message', 'Couldnot find the Fees');
                    // $this->setFlashMessage('error', 'Couldn\'t find the subadmin');
                    return redirect()->route(ADMIN_PATH . '/fees/list');
                }
            } else {
                $this->session->setFlashdata('error_message', 'Couldnot find the Fees');
                // $this->setFlashMessage('error', 'Couldn\'t find the subadmin');
                return redirect()->route(ADMIN_PATH . '/fees/list');
            }
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/' . ADMIN_PATH);
        }
    }

    public function delete()
    {
        if ($this->checkSession('A') != '') {
            $returnArr['status'] = '0';
            $returnArr['response'] = 'Failed to delete, Please try again';
            if ($this->checkSession('A') == '') {
                $returnArr['status'] = '00';
                $returnArr['response'] = 'Session has been timed out, Please login again and try.';
            } else {
                $record_id = $this->request->getPostGet('record_id');
                if (isset($record_id)) {
                    $this->LmsModel->isDelete(FEES, 'id', TRUE);
                    // $this->setFlashMessage('success', 'Lms deleted successfully');
                    $returnArr['status'] = '1';
                    $returnArr['response'] = 'Fees Deleted Successfully';
                }
                // $this->LmsModel->commonDelete(LMS, array('id' => $record_id));
            }

            echo json_encode($returnArr);
            exit;
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/' . ADMIN_PATH);
        }
    }
}
