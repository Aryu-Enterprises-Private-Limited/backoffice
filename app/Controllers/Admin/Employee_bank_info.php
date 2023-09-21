<?php

namespace App\Controllers\Admin;


use App\Controllers\BaseController;

class Employee_bank_info extends BaseController
{

    public function __construct()
    {
        $this->session = session();
        $this->LmsModel = new \App\Models\LmsModel();
    }

    public function index()
    {
        if ($this->checkSession('A') != '') {
            $this->data['title'] = 'Employee bank info List';
            echo view(ADMIN_PATH . '/emp_bank_info/list', $this->data);
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
                'job_type_name' => trim($dtSearchKeyVal),
            );
        }

        $totCounts = $this->LmsModel->get_all_counts(EMPLOYEE_BANK_INFO, $condition, '', $likeArr);
        $sortArr = array('dt' => -1);
        if ($sortField != '') {
            $sortArr = array($sortField => $sortJob);
        }
        // $condition
        // print_r($condition);die;
        $ajaxDataArr = $this->LmsModel->get_all_details(EMPLOYEE_BANK_INFO, $condition, $sortArr, $rowperpage, $row_start, $likeArr);


        $tblData = array();

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

            $actionTxt = '<a class="btn btn-icon text-info" href="/' . ADMIN_PATH . '/emp_bank_info/view/' . (string)$rowId . '"><i class="fas fa-eye"></i></a>';

            $statusTxt =  '<a data-toggle="tooltip" data-original-title="' . $actTitle . '" class="stsconfirm" href="javascript:void(0);" data-row_id="' . $rowId . '" data-act_url="/' . ADMIN_PATH . '/emp_bank_info/change-status" data-stsmode="' . $mode . '"> <button type="button" class="btn ' . $btnColr . ' btn-sm waves-effect waves-light">' . $disp_status . '</button></a>';

            $actionTxt .= '<a class="btn btn-icon " href="/' . ADMIN_PATH . '/emp_bank_info/edit/' . (string)$rowId . '"><i class="fas fa-edit"></i></a>';


            $actionTxt .= '<a href="javascript:void(0);" class="delconfirm btn btn-icon text-danger" data-row_id="' . $rowId . '" data-act_url="/' . ADMIN_PATH . '/emp_bank_info/delete"><i class="fas fa-trash-alt"></i></a>';


            $tblData[] = array(
                // 'DT_RowId' => (string)$rowId,
                // 'checker_box' => '<input class="checkRows" name="checkbox_id[]" type="checkbox" value="' . $rowId . '">',
                'employee_name' => ucfirst($row->employee_name),
                'acc_no' => $row->acc_no,
                'ifsc_code' => $row->ifsc_code,
                'acc_type' => $row->acc_type,
                'branch_name' => ucfirst($row->branch_name),
                'employee_sts' => str_replace("_"," ",$row->employee_sts),
                'created_at' => $row->created_at,
                "status" =>  $statusTxt,
                "action" =>  $actionTxt
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


    public function add_edit($id = "")
    {
        if ($this->checkSession('A') != '') {
            $uri = service('uri');
            $id = $uri->getSegment(4);
            $this->data['employee_details'] = $this->LmsModel->get_selected_fields(EMPLOYEE_DETAILS, ['status' => '1', 'is_deleted' => '0'], ['id', 'first_name','last_name'])->getResult();
            if ($id != '') {
                $condition = array('is_deleted' => '0', 'id' => $id);
                $this->data['employee_bank_details'] = $this->LmsModel->get_selected_fields(EMPLOYEE_BANK_INFO, $condition)->getRow();
                if (!empty($this->data['employee_bank_details'])) {
                    $this->data['title'] = 'Edit Employee bank info';
                } else {
                    $this->session->setFlashdata('error_message', 'Couldnot find the employee details');
                    return redirect()->route(ADMIN_PATH . '/emp_bank_info/list');
                }
            } else {
                $this->data['title'] = 'Add Employee bank info';
            }
            echo view(ADMIN_PATH . '/emp_bank_info/add_edit', $this->data);
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/' . ADMIN_PATH);
        }
    }



    public function insertUpdate()
    {
        
        if ($this->checkSession('A') != '') {
            $employee_name = (string)$this->request->getPostGet('employee_name');
            $acc_no = (string)$this->request->getPostGet('acc_no');
            $ifsc_code = (string)$this->request->getPostGet('ifsc_code');
            $acc_type = (string)$this->request->getPostGet('acc_type');
            $branch_name = (string)$this->request->getPostGet('branch_name');
            $employee_sts = (string)$this->request->getPostGet('employee_sts');
            $status = (string)$this->request->getPostGet('status');
            $id = (string)$this->request->getPostGet('id');
            if ($status == '') {
                $status = 'off';
            }
            $str_arr = explode (",", $employee_name); 
            $fSubmit = FALSE;
            if ($acc_no != '' && $ifsc_code!='' && $acc_type!='' && $branch_name!='') {
                if ($status == 'on') {
                    $status = '1';
                } else {
                    $status = '0';
                }
                $dataArr = array(
                    'employee_name' => $str_arr[1],
                    'employee_id' => $str_arr[0],
                    'acc_no' => $acc_no,
                    'ifsc_code' => $ifsc_code,
                    'acc_type' => $acc_type,
                    'branch_name' => $branch_name,
                    'employee_sts' => $employee_sts,
                    'status' => $status,
                    'is_deleted' => '0',
                );

                if ($id == '') {
                    $this->LmsModel->simple_insert(EMPLOYEE_BANK_INFO, $dataArr);
                    $this->session->setFlashdata('success_message', 'Employee bank info added successfully.');
                    $fSubmit = TRUE;
                } else {
                    $condition = array('id' => $id);
                    $this->LmsModel->update_details(EMPLOYEE_BANK_INFO, $dataArr, $condition);
                    $this->session->setFlashdata('success_message', 'Employee bank info update successfully');
                    $fSubmit = TRUE;
                }
            } else {
                $this->session->setFlashdata('error_message', 'Form data is missing.');
            }
            if ($fSubmit) {
                $url = ADMIN_PATH . '/emp_bank_info/list';
            } else {
                if ($id == '') $url = ADMIN_PATH . '/emp_bank_info/add';
                else $url = ADMIN_PATH . '/emp_bank_info/edit/' . $id;
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
                $this->LmsModel->update_details(EMPLOYEE_BANK_INFO, $newdata, $condition);
                $returnArr['status'] = '1';
                $returnArr['response'] = 'Employee bank info Status Changed Successfully';
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
                $this->data['emp_bank_Details'] = $this->LmsModel->get_all_details(EMPLOYEE_BANK_INFO, $condition)->getRow();
                if (!empty($this->data['emp_bank_Details'])) {
                    $this->data['title'] = 'Employee bank info view';
                    echo view(ADMIN_PATH . '/emp_bank_info/view', $this->data);
                } else {
                    $this->session->setFlashdata('error_message', 'Couldnot find the Employee bank info');
                    // $this->setFlashMessage('error', 'Couldn\'t find the subadmin');
                    return redirect()->route(ADMIN_PATH . '/emp_bank_info/list');
                }
            } else {
                $this->session->setFlashdata('error_message', 'Couldnot find the Employee bank info');
                // $this->setFlashMessage('error', 'Couldn\'t find the subadmin');
                return redirect()->route(ADMIN_PATH . '/emp_bank_info/list');
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
                    $this->LmsModel->isDelete(EMPLOYEE_BANK_INFO, 'id', TRUE);
                    // $this->setFlashMessage('success', 'Lms deleted successfully');
                    $returnArr['status'] = '1';
                    $returnArr['response'] = 'Employee bank info Deleted Successfully';
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
