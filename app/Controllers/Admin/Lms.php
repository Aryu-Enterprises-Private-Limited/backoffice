<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Lms extends BaseController
{

    public function __construct()
    {

        $this->session = session();
        $this->LmsModel = new \App\Models\LmsModel();
    }

    public function index()
    {
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

            $actionTxt = '<a class="btn btn-icon text-info" href="/' . ADMIN_PATH . '/lms/view/' . (string)$rowId . '"><i class="fas fa-eye"></i></a>';

            

            $statusTxt =  '<a data-toggle="tooltip" data-original-title="' . $actTitle . '" class="stsconfirm" href="javascript:void(0);" data-row_id="' . $rowId . '" data-act_url="/' . ADMIN_PATH . '/lms/change-status" data-stsmode="' . $mode . '"> <button type="button" class="btn ' . $btnColr . ' btn-sm waves-effect waves-light">' . $disp_status . '</button></a>';

            $actionTxt .= '<a class="btn btn-icon " href="/' . ADMIN_PATH . '/lms/edit/' . (string)$rowId . '"><i class="fas fa-edit"></i></a>';


            $actionTxt .= '<a href="javascript:void(0);" class="delconfirm btn btn-icon text-danger" data-row_id="' . $rowId . '" data-act_url="/' . ADMIN_PATH . '/lms/delete"><i class="fas fa-trash-alt"></i></a>';

            $tblData[] = array(
                // 'DT_RowId' => (string)$rowId,
                // 'checker_box' => '<input class="checkRows" name="checkbox_id[]" type="checkbox" value="' . $rowId . '">',
                'first_name' => ucfirst($row->first_name),
                'last_name' => ucfirst($row->last_name),
                'address' => $row->address,
                'phone' => $row->phone,
                'email' => $row->email,
                'lead_source' => $row->lead_source,
                'linked_in' => $row->linked_in,
                'twitter' => $row->twitter,
                'facebook' => $row->facebook,
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
                $condition = array('id' => $id, 'is_deleted' => '0');

                $this->data['info'] = $this->LmsModel->get_selected_fields(LMS, $condition)->getRow();
                
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
        } else {
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
            $status = (string)$this->request->getPostGet('status');
            if ($status == '') {
                $status = 'off';
            }

            
            $notes_id = $this->request->getPostGet('notes_id');

            $fSubmit = FALSE;
            if ($first_name != '' && $last_name != '' && $address != '' && $phone != '' && $email != '' && $lead_source != '' && $linked_in != '' && $twitter != '' && $facebook != ''  ) {
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
                    'status' => $status,
                    'is_deleted' => '0'
                );


                if ($id == '') {
                    $this->LmsModel->simple_insert(LMS, $dataArr);
                    $this->session->setFlashdata('success_message', 'lms details added successfully.');
                    // $this->setFlashMessage('success', 'lms details added successfully');
                    $fSubmit = TRUE;
                } else {
                    
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
                    $this->LmsModel->isDelete(LMS, 'id', TRUE);
                    // $this->setFlashMessage('success', 'Lms deleted successfully');
                    $returnArr['status'] = '1';
                    $returnArr['response'] = 'Record Deleted Successfully';
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
