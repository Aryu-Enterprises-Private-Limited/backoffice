<?php

namespace App\Controllers\Admin;


use App\Controllers\BaseController;

class Company_info extends BaseController
{

    public function __construct()
    {
        $this->session = session();
        $this->LmsModel = new \App\Models\LmsModel();
    }

    public function index()
    {
        if ($this->checkSession('A') != '') {
            $this->data['title'] = 'Company Info List';
            echo view(ADMIN_PATH . '/company_info/list', $this->data);
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
                'company_title' => trim($dtSearchKeyVal),
                'user_id_name' => trim($dtSearchKeyVal),
                // 'value' => trim($dtSearchKeyVal),
            );
        }

        $totCounts = $this->LmsModel->get_all_counts(COMPANY_INFO, $condition, '', $likeArr);
        $sortArr = array('id' => -1);
        if ($sortField != '') {
            $sortArr = array($sortField => $sortJob);
        }

        $ajaxDataArr = $this->LmsModel->get_all_details(COMPANY_INFO, $condition, $sortArr, $rowperpage, $row_start, $likeArr);

        $tblData = array();
        $position = 1;

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

            $actionTxt = '<a class="btn btn-icon text-info" href="/' . ADMIN_PATH . '/company_info/view/' . (string)$rowId . '"><i class="fas fa-eye"></i></a>';

            $statusTxt =  '<a data-toggle="tooltip" data-original-title="' . $actTitle . '" class="stsconfirm" href="javascript:void(0);" data-row_id="' . $rowId . '" data-act_url="/' . ADMIN_PATH . '/company_info/change-status" data-stsmode="' . $mode . '"> <button type="button" class="btn ' . $btnColr . ' btn-sm waves-effect waves-light">' . $disp_status . '</button></a>';

            $actionTxt .= '<a class="btn btn-icon " href="/' . ADMIN_PATH . '/company_info/edit/' . (string)$rowId . '"><i class="fas fa-edit"></i></a>';


            $actionTxt .= '<a href="javascript:void(0);" class="delconfirm btn btn-icon text-danger" data-row_id="' . $rowId . '" data-act_url="/' . ADMIN_PATH . '/company_info/delete"><i class="fas fa-trash-alt"></i></a>';


            $tblData[] = array(
                // 'DT_RowId' => (string)$rowId,
                // 'checker_box' => '<input class="checkRows" name="checkbox_id[]" type="checkbox" value="' . $rowId . '">',
                'company_title' => ucfirst($row->company_title),
                // 'value' => $row->value,
                'user_id_name' => ucfirst($row->user_id_name),
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
            if ($id != '') {
                $condition = array('is_deleted' => '0', 'id' => $id);
                $this->data['company_info'] = $this->LmsModel->get_selected_fields(COMPANY_INFO, $condition)->getRow();

                if (!empty($this->data['company_info'])) {
                    $this->data['title'] = 'Edit Company Info';
                } else {
                    $this->session->setFlashdata('error_message', 'Couldnot find the Company info');
                    return redirect()->route(ADMIN_PATH . '/company_info/list');
                }
            } else {
                $this->data['title'] = 'Add Company Info';
            }
            echo view(ADMIN_PATH . '/company_info/add_edit', $this->data);
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/' . ADMIN_PATH);
        }
    }



    public function insertUpdate()
    {
        if ($this->checkSession('A') != '') {
            $company_title = (string)$this->request->getPostGet('company_title');
            // $value = (string)$this->request->getPostGet('value');
            $user_id_name = (string)$this->request->getPostGet('user_id_name');
            $password = (string)$this->request->getPostGet('password');
            $links = (string)$this->request->getPostGet('links');
            $status = (string)$this->request->getPostGet('status');
            $id = (string)$this->request->getPostGet('id');
            if ($status == '') {
                $status = 'off';
            }
            $fSubmit = FALSE;
            if ($company_title != '' && $user_id_name != ''  && $links != '') {
                if ($status == 'on') {
                    $status = '1';
                } else {
                    $status = '0';
                }
                $dataArr = array(
                    'company_title' => $company_title,
                    'user_id_name' => $user_id_name,
                    'links' => $links,
                    'status' => $status,
                    'is_deleted' => '0',
                );
            if($password != ''){
                $dataArr['original_password'] = $password;
            }
                if ($id == '') {
                    
                    $dataArr['password'] = password_hash($password, PASSWORD_DEFAULT);
                    $this->LmsModel->simple_insert(COMPANY_INFO, $dataArr);
                    $this->session->setFlashdata('success_message', 'Company info details added successfully.');
                    $fSubmit = TRUE;
                } else {
                    $condition = array('id' => $id);
                    $this->LmsModel->update_details(COMPANY_INFO, $dataArr, $condition);
                    $this->session->setFlashdata('success_message', 'Company info details update successfully');
                    $fSubmit = TRUE;
                }
            } else {
                $this->session->setFlashdata('error_message', 'Form data is missing.');
            }
            if ($fSubmit) {
                $url = ADMIN_PATH . '/company_info/list';
            } else {
                if ($id == '') $url = ADMIN_PATH . '/company_info/add';
                else $url = ADMIN_PATH . '/company_info/edit/' . $id;
            }
            return redirect()->to("$url");
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/' . ADMIN_PATH);
        }
    }
    public function password_validate()
    {
        
        $password = (string)$this->request->getPostGet('password');
        if($password !=''){
            $getAdminData = $this->LmsModel->get_selected_fields(ADMIN_USERS, '', array('status', 'password', 'id', 'user_name', 'email','company_info_password'));
            if ($getAdminData->getNumRows() == 1) {
                $status = $getAdminData->getRow()->status;
                if ($status == 1) {
                    $aPWord = $getAdminData->getRow()->company_info_password;
                    $isvalidPWord = password_verify($password, $aPWord);
                    if ($isvalidPWord) {
                        $returnArr['status'] = '1';
                         $returnArr['response'] = 'Password Verified';
                        // $this->session->setFlashdata('success_message', 'Password Verified.');
                    } else {
                        $returnArr['status'] = '0';
                        // $returnArr['response'] = 'Entered password is incorrect.';
                        $this->session->setFlashdata('error_message', 'Entered password is incorrect.');
                    }
                } else {
                    $returnArr['status'] = '0';
                    // $returnArr['response'] = 'This account is not active.';
                    $this->session->setFlashdata('error_message', 'This account is not active.');
                    // return redirect()->to('/' . ADMIN_PATH);
                }
            }
        }else{
            $returnArr['status'] = '0';
            $this->session->setFlashdata('error_message', 'Enter a password.');
            // $returnArr['response'] = 'Enter a password';
        }
        echo json_encode($returnArr);
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
                $this->LmsModel->update_details(COMPANY_INFO, $newdata, $condition);
                $returnArr['status'] = '1';
                $returnArr['response'] = 'Company info Status Changed Successfully';
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
                $this->data['compDetails'] = $this->LmsModel->get_all_details(COMPANY_INFO, $condition)->getRow();
                if (!empty($this->data['compDetails'])) {
                    $this->data['title'] = 'Company info view';
                    echo view(ADMIN_PATH . '/company_info/view', $this->data);
                } else {
                    $this->session->setFlashdata('error_message', 'Couldnot find the Company info');
                    // $this->setFlashMessage('error', 'Couldn\'t find the subadmin');
                    return redirect()->route(ADMIN_PATH . '/company_info/list');
                }
            } else {
                $this->session->setFlashdata('error_message', 'Couldnot find the Company info');
                // $this->setFlashMessage('error', 'Couldn\'t find the subadmin');
                return redirect()->route(ADMIN_PATH . '/company_info/list');
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
                    $this->LmsModel->isDelete(COMPANY_INFO, 'id', TRUE);
                    // $this->setFlashMessage('success', 'Lms deleted successfully');
                    $returnArr['status'] = '1';
                    $returnArr['response'] = 'Company Record Deleted Successfully';
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
