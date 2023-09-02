<?php

namespace App\Controllers\Admin;

// use App\Models\userModel;
use PhpParser\Node\Expr\Print_;
use App\Controllers\BaseController;

class Client extends BaseController
{

    public function __construct()
    {
        $this->session = session();
        $this->LmsModel = new \App\Models\LmsModel();
    }

    public function index()
    {
        if ($this->checkSession('A') != '') {
            $this->data['title'] = 'Client List';
            echo view(ADMIN_PATH . '/client/list', $this->data);
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
                'first_name' => trim($dtSearchKeyVal),
                'last_name' => strtolower(trim($dtSearchKeyVal)),
                'phone' => trim($dtSearchKeyVal),
                'email' => trim($dtSearchKeyVal),
            );
        }

        $totCounts = $this->LmsModel->get_all_counts(CLIENT_DETAILS, $condition, '', $likeArr);
        $sortArr = array('dt' => -1);
        if ($sortField != '') {
            $sortArr = array($sortField => $sortJob);
        }
        // $condition
        // print_r($condition);die;
        $ajaxDataArr = $this->LmsModel->get_all_details(CLIENT_DETAILS, $condition, $sortArr, $rowperpage, $row_start, $likeArr);


        // if (isset($_GET['export']) && $_GET['export'] == 'excel') {
        //     $returnArr['status'] = '1';
        //     $returnArr['response'] = $ajaxDataArr;
        //     return $returnArr;
        // }
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

            $actionTxt = '<a class="btn btn-icon text-info" href="/' . ADMIN_PATH . '/client/view/' . (string)$rowId . '"><i class="fas fa-eye"></i></a>';

            $statusTxt =  '<a data-toggle="tooltip" data-original-title="' . $actTitle . '" class="stsconfirm" href="javascript:void(0);" data-row_id="' . $rowId . '" data-act_url="/' . ADMIN_PATH . '/client/change-status" data-stsmode="' . $mode . '"> <button type="button" class="btn ' . $btnColr . ' btn-sm waves-effect waves-light">' . $disp_status . '</button></a>';

            $actionTxt .= '<a class="btn btn-icon " href="/' . ADMIN_PATH . '/client/edit/' . (string)$rowId . '"><i class="fas fa-edit"></i></a>';


            $actionTxt .= '<a href="javascript:void(0);" class="delconfirm btn btn-icon text-danger" data-row_id="' . $rowId . '" data-act_url="/' . ADMIN_PATH . '/client/delete"><i class="fas fa-trash-alt"></i></a>';

            // $source = '';
            // $logo = MEMBER_PROFILE_DEFAULT;
            // if (isset($row->logo) && $row->logo != '') {
            //     $logo = COMPANIES_LOGO_PATH . $row->logo;
            //     $source .= '<img src="' . base_url() . '/' . $logo . '" width="128" height="128" class="rounded-circle img-thumbnail" alt="' . $row->name . ' Image">';
            // } else {
            //     $source .= '<img src="assets/images/users/avatar-1.jpg" alt="user-img" class="rounded-circle user-img">';
            // }


            $tblData[] = array(
                // 'DT_RowId' => (string)$rowId,
                // 'checker_box' => '<input class="checkRows" name="checkbox_id[]" type="checkbox" value="' . $rowId . '">',
                'first_name' => $row->first_name,
                'last_name' => $row->last_name,
                'phone' => $row->phone,
                'email' => $row->email,
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
            $condition = array('is_deleted' => '0');
            $this->data['client_info'] = $this->LmsModel->get_selected_fields(CLIENT_DETAILS, $condition)->getRow();
            if (!empty($this->data['client_info'])) {
                $this->data['title'] = 'Edit Client Details';
            } else {
                $this->session->setFlashdata('error_message', 'Couldnot find the Employee');
                return redirect()->route(ADMIN_PATH . '/client/list');
            }
        } else {
            $this->data['title'] = 'Add Client';
        }
        echo view(ADMIN_PATH . '/client/add_edit', $this->data);
    }else{
        $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/' . ADMIN_PATH);
    }
    }



    public function insertUpdate()
    {
        if ($this->checkSession('A') != '') {
        $first_name = (string)$this->request->getPostGet('first_name');
        $last_name = (string)$this->request->getPostGet('last_name');
        $email = (string)$this->request->getPostGet('email');
        $phone = (string)$this->request->getPostGet('phone');
        $status = (string)$this->request->getPostGet('status');
        $id = (string)$this->request->getPostGet('id');
        if ($status == '') {
            $status = 'off';
        }
        $fSubmit = FALSE;
        if ($first_name != '' && $last_name != '' && $phone != '' && $email !='') {
            if ($status == 'on') {
                $status = '1';
            } else {
                $status = '0';
            }
            $dataArr = array(
                'first_name' => $first_name,
                'last_name' => $last_name,
                'phone' => $phone,
                'email' => $email,
                'status' => $status,
            );
           
            if ($id == '') {
                $this->LmsModel->simple_insert(CLIENT_DETAILS, $dataArr);
                $this->session->setFlashdata('success_message', 'Client details added successfully.');
                $fSubmit = TRUE;
            } else {
                $condition = array('id' => $id);
                $this->LmsModel->update_details(CLIENT_DETAILS, $dataArr, $condition);
                $this->session->setFlashdata('success_message', 'Client details update successfully');
                $fSubmit = TRUE;
            }
        } else {
            $this->session->setFlashdata('error_message', 'Form data is missing.');
        }
        if ($fSubmit) {
            $url = ADMIN_PATH . '/client/list';
        } else {
            if ($id == '') $url = ADMIN_PATH . '/client/add';
            else $url = ADMIN_PATH . '/client/edit/' . $id;
        }
        return redirect()->to("$url");
    }else{
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
            $this->LmsModel->update_details(CLIENT_DETAILS, $newdata, $condition);
            $returnArr['status'] = '1';
            $returnArr['response'] = 'Client Status Changed Successfully';
        }
        echo json_encode($returnArr);
        exit;
    }else{
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
            $this->data['clientDetails'] = $this->LmsModel->get_all_details(CLIENT_DETAILS, $condition)->getRow();
            if (!empty($this->data['clientDetails'])) {
                $this->data['title'] = 'Client view';
                echo view(ADMIN_PATH . '/client/view', $this->data);
            } else {
                    $this->session->setFlashdata('error_message', 'Couldnot find the Crm');
                    // $this->setFlashMessage('error', 'Couldn\'t find the subadmin');
                    return redirect()->route(ADMIN_PATH . '/client/list');
            }
        } else {
            $this->session->setFlashdata('error_message', 'Couldnot find the Employee');
            // $this->setFlashMessage('error', 'Couldn\'t find the subadmin');
            return redirect()->route(ADMIN_PATH . '/client/list');
        }
    }else{
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
                $this->LmsModel->isDelete(CLIENT_DETAILS, 'id', TRUE);
                // $this->setFlashMessage('success', 'Lms deleted successfully');
                $returnArr['status'] = '1';
                $returnArr['response'] = 'Record Deleted Successfully';
            }
            // $this->LmsModel->commonDelete(LMS, array('id' => $record_id));
        }

        echo json_encode($returnArr);
        exit;
    }else{
        $this->session->setFlashdata('error_message', 'Please login!!!');
        return redirect()->to('/' . ADMIN_PATH);
    }
    }
}
