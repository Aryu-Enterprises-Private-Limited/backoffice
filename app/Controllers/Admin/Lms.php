<?php

namespace App\Controllers\Admin;

// use App\Models\userModel;
use PhpParser\Node\Expr\Print_;
use App\Controllers\BaseController;

class Lms extends BaseController
{

    public function __construct()
    {

        $this->session = session();
        $this->LmsModel = new \App\Models\LmsModel();
        // if ($this->checkSession('A') == '') {
        //     $this->session->setFlashdata('error_message', 'Session Expired!!!');
        // 	// $this->setFlashMessage('error', 'Session Expired');
        //     // (base_url(ADMIN_PATH . '/do-login'))
        // 	$this->forceRedirect('/'.ADMIN_PATH);
        // }
        // $this->load->helper('uri');
    }

    public function index()
    {
        // helper('url');
        if ($this->checkSession('A') != '') {
            $this->data['title'] = 'Lms List';
            echo view(ADMIN_PATH . '/lms/list', $this->data);
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
                'address' => trim($dtSearchKeyVal),
                'phone' => trim($dtSearchKeyVal),
                'email' => trim($dtSearchKeyVal),
                'lead_source' => trim($dtSearchKeyVal),
                'linked_in' => trim($dtSearchKeyVal),
                'twitter' => trim($dtSearchKeyVal),
                'facebook' => trim($dtSearchKeyVal),
                'follow_up_alert' => trim($dtSearchKeyVal),
            );
        }

        $totCounts = $this->LmsModel->get_all_counts(LMS, $condition, '', $likeArr);
        $sortArr = array('dt' => -1);
        if ($sortField != '') {
            $sortArr = array($sortField => $sortJob);
        }
        // $condition
        // print_r($condition);die;
        $ajaxDataArr = $this->LmsModel->get_all_details(LMS, $condition, $sortArr, $rowperpage, $row_start, $likeArr);


        // if (isset($_GET['export']) && $_GET['export'] == 'excel') {
        //     $returnArr['status'] = '1';
        //     $returnArr['response'] = $ajaxDataArr;
        //     return $returnArr;
        // }
        $tblData = array();
        $position = 1;

        foreach ($ajaxDataArr->getResult() as $row) {
            // $notes_details = $this->LmsModel->table_join()->getResult();

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

            $actionTxt = '<a class="btn btn-icon text-info" href="/' . ADMIN_PATH . '/lms/view/' . (string)$rowId . '"><i class="fas fa-eye"></i></a>';

            $notesTxt = '<button type="button" class="btn btn-info v_btn" data-act_url="/' . ADMIN_PATH . '/lms/get-notes_details"  data-row_id="' . $rowId . '">View </button>';

            $statusTxt =  '<a data-toggle="tooltip" data-original-title="' . $actTitle . '" class="stsconfirm" href="javascript:void(0);" data-row_id="' . $rowId . '" data-act_url="/' . ADMIN_PATH . '/lms/change-status" data-stsmode="' . $mode . '"> <button type="button" class="btn ' . $btnColr . ' btn-sm waves-effect waves-light">' . $disp_status . '</button></a>';

            $actionTxt .= '<a class="btn btn-icon " href="/' . ADMIN_PATH . '/lms/edit/' . (string)$rowId . '"><i class="fas fa-edit"></i></a>';


            $actionTxt .= '<a href="javascript:void(0);" class="delconfirm btn btn-icon text-danger" data-row_id="' . $rowId . '" data-act_url="/' . ADMIN_PATH . '/lms/delete"><i class="fas fa-trash-alt"></i></a>';

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
                'address' => $row->address,
                'phone' => $row->phone,
                'email' => $row->email,
                'lead_source' => $row->lead_source,
                'linked_in' => $row->linked_in,
                'twitter' => $row->twitter,
                'facebook' => $row->facebook,
                "id" => $notesTxt,
                'follow_up_alert' => $row->follow_up_alert,
                "status" =>  $statusTxt,
                "action" =>  $actionTxt
                //"action" =>  '1'
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

    public function get_notes_data()
    {
        if ($this->checkSession('A') != '') {
        $lms_id = (string)$this->request->getPostGet('lms_id');
        $condition = array('lms_id' => $lms_id);
        $lms_data = $this->LmsModel->get_all_details(NOTES, $condition);
        $html = '';
        // echo "<pre>";
        // print_r($lms_data->getResult());
        // die;
        if (!empty($lms_data)) {
            $x = 1;
            foreach($lms_data->getResult() as $data){
                $html .= '<div class="row">
                <div class="col-12">
                    <div class="row form-group">
                        <label class="col-sm-3 control-label">Notes '.$x.': </label>
                        <p class="control-label">'.ucfirst($data->note).'</p>
                    </div>
                </div>
            </div>';
            $x++;
            }
        } else {
            $html = 'No Records Found';
        }
        echo json_encode($html);
    }else{
        $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/' . ADMIN_PATH);
    }
    }

    public function add_edit($id = "")
    {
        if ($this->checkSession('A') != '') {
        $uri = service('uri');
        $id = $uri->getSegment(4);
        if ($id != '') {
            $condition = array('id' => $id, 'is_deleted' => '0');
            $condition2 = array('lms_id' => $id);
            $this->data['info'] = $this->LmsModel->get_selected_fields(LMS, $condition)->getRow();
            $this->data['notes_info'] = $this->LmsModel->get_selected_fields(NOTES, $condition2)->getResult();
            //   echo"<pre>";print_r($this->data['notes_info']);die;
            if (!empty($this->data['info'])) {
                $this->data['title'] = 'Edit Lms';
            } else {
                $this->session->setFlashdata('error_message', 'Couldnot find the Lms');
                return redirect()->route(ADMIN_PATH . '/lms/list');
            }
        } else {
            $this->data['title'] = 'Add Lms';
        }
        echo view(ADMIN_PATH . '/lms/add_edit', $this->data);
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
            $this->data['lmsDetails'] = $this->LmsModel->get_selected_fields(LMS, $condition)->getRow();
            if (!empty($this->data['lmsDetails'])) {
                $this->data['title'] = 'Lms view';
                echo view(ADMIN_PATH . '/lms/view', $this->data);
            } else {
                $this->session->setFlashdata('error_message', 'Couldnot find the Lms');
                // $this->setFlashMessage('error', 'Couldn\'t find the subadmin');
                return redirect()->route(ADMIN_PATH . '/lms/list');
            }
        } else {
            $this->session->setFlashdata('error_message', 'Couldnot find the Lms');
            // $this->setFlashMessage('error', 'Couldn\'t find the subadmin');
            return redirect()->route(ADMIN_PATH . '/lms/list');
        }
    }else{
        $this->session->setFlashdata('error_message', 'Please login!!!');
        return redirect()->to('/' . ADMIN_PATH);
    }
    }

    public function insertUpdate()
    {
        if ($this->checkSession('A') != '') {
        $id = (string)$this->request->getPostGet('id');
        $first_name = (string)$this->request->getPostGet('first_name');
        $last_name = (string)$this->request->getPostGet('last_name');
        $address = (string)$this->request->getPostGet('address');
        $phone = (string)$this->request->getPostGet('phone');
        $email = (string)$this->request->getPostGet('email');
        $lead_source = (string)$this->request->getPostGet('lead_source');
        $linked_in = (string)$this->request->getPostGet('linked_in');
        $twitter = (string)$this->request->getPostGet('twitter');
        $facebook = (string)$this->request->getPostGet('facebook');
        $follow_up_alert = (string)$this->request->getPostGet('follow_up_alert');
        $status = (string)$this->request->getPostGet('status');
        if ($status == '') {
            $status = 'off';
        }

        $addmore = $this->request->getPostGet('addmore');
        $notes_id = $this->request->getPostGet('notes_id');

        $fSubmit = FALSE;
        if ($first_name != '' && $last_name != '' && $address != '' && $phone != '' && $email != '' && $lead_source != '' && $linked_in != '' && $twitter != '' && $facebook != '' && $follow_up_alert != '' && $addmore != '') {
            if ($status == 'on') {
                $status = 1;
            } else {
                $status = 0;
            }

            $dataArr = array(
                'first_name' => $first_name,
                'last_name' => $last_name,
                'address' => $address,
                'phone' => $phone,
                'email' => $email,
                'lead_source' => $lead_source,
                'linked_in' => $linked_in,
                'twitter' => $twitter,
                'facebook' => $facebook,
                'follow_up_alert' => $follow_up_alert,
                'status' => $status,
            );


            if ($id == '') {
                $this->LmsModel->simple_insert(LMS, $dataArr);
                $last_inserted_id = $this->LmsModel->get_last_insert_id();
                $newdata = array();
                foreach ($addmore as $val) {
                    // print_r($val);die;
                    $newdata = array(
                        'lms_id' => $last_inserted_id,
                        'note' => $val,
                    );
                    $this->LmsModel->simple_insert(NOTES, $newdata);
                }

                $this->session->setFlashdata('success_message', 'lms details added successfully.');
                // $this->setFlashMessage('success', 'lms details added successfully');
                $fSubmit = TRUE;
            } else {
                //  print_r($notes_id);die;
                // if(!empty($notes_id)){
                // foreach ($notes_id as $ins_id) {
                //     $cond = array('id' => $ins_id);
                //      $this->LmsModel->commonDelete(NOTES, $cond);
                // }
                $cond = array('lms_id' => $id);
                $this->LmsModel->commonDelete(NOTES, $cond);
                // }

                // print_r($addmore);
                // die;
                $newdata = array();
                foreach ($addmore as $val) {
                    if ($val != '') {
                        $newdata = array(
                            'lms_id' => $id,
                            'note' => $val,
                        );
                        // echo"<pre>";print_r($newdata);die;
                        $this->LmsModel->simple_insert(NOTES, $newdata);
                    }
                }
                $condition = array('id' => $id);

                $this->LmsModel->update_details(LMS, $dataArr, $condition);
                $this->session->setFlashdata('success_message', 'lms details update successfully.');
                // $this->setFlashMessage('success', 'Companies details update successfully');
                $fSubmit = TRUE;
            }
        } else {
            $this->session->setFlashdata('error_message', 'Form data is missing.');
        }
        // echo"<pre>";print_R($_SESSION);die;
        if ($fSubmit) {
            $url = ADMIN_PATH . '/lms/list';
        } else {
            if ($id == '') $url = ADMIN_PATH . '/lms/add';
            else $url = ADMIN_PATH . '/lms/edit/' . $id;
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
            // echo $mode;die;
            $id = $this->request->getPostGet('record_id');
            $status = ($mode == '0') ? '0' : '1';
            $newdata = array('status' => $status);
            $condition = array('id' => $id);
            $this->LmsModel->update_details(LMS, $newdata, $condition);
            $returnArr['status'] = '1';
            $returnArr['response'] = 'Lms Status Changed Successfully';
        }
        echo json_encode($returnArr);
        exit;
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
                $this->LmsModel->isDelete(LMS, 'id', TRUE);
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
