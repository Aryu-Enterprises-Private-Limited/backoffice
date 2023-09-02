<?php

namespace App\Controllers\Admin;

// use App\Models\userModel;
use PhpParser\Node\Expr\Print_;
use App\Controllers\BaseController;

class Crm extends BaseController
{

    public function __construct()
    {
        $this->session = session();
        $this->CrmModel = new \App\Models\CrmModel();
        $this->LmsModel = new \App\Models\LmsModel();
        // $this->load->helper('uri');
    }

    public function index()
    {
        if ($this->checkSession('A') != '') {
            $this->data['title'] = 'Crm List';
            echo view(ADMIN_PATH . '/crm/list', $this->data);
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
                'project_details' => trim($dtSearchKeyVal),
                'price' => strtolower(trim($dtSearchKeyVal)),
                'Lead' => trim($dtSearchKeyVal),
            );
        }

        $totCounts = $this->CrmModel->get_all_counts(CRM, $condition, '', $likeArr);
        $sortArr = array('dt' => -1);
        if ($sortField != '') {
            $sortArr = array($sortField => $sortJob);
        }
        // $condition
        // print_r($condition);die;
        $ajaxDataArr = $this->CrmModel->get_all_details(CRM, $condition, $sortArr, $rowperpage, $row_start, $likeArr);


        // if (isset($_GET['export']) && $_GET['export'] == 'excel') {
        //     $returnArr['status'] = '1';
        //     $returnArr['response'] = $ajaxDataArr;
        //     return $returnArr;
        // }
        $tblData = array();
        $position = 1;

        foreach ($ajaxDataArr->getResult() as $row) {
            $lead_details = $this->LmsModel->get_selected_fields(LMS, ['status' => '1'], ['id', 'first_name', 'last_name'])->getResult();
            foreach ($lead_details as $details) {
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

                $actionTxt = '<a class="btn btn-icon text-info" href="/' . ADMIN_PATH . '/crm/view/' . (string)$rowId . '"><i class="fas fa-eye"></i></a>';


                $statusTxt =  '<a data-toggle="tooltip" data-original-title="' . $actTitle . '" class="stsconfirm" href="javascript:void(0);" data-row_id="' . $rowId . '" data-act_url="/' . ADMIN_PATH . '/crm/change-status" data-stsmode="' . $mode . '"> <button type="button" class="btn ' . $btnColr . ' btn-sm waves-effect waves-light">' . $disp_status . '</button></a>';

                $actionTxt .= '<a class="btn btn-icon" href="/' . ADMIN_PATH . '/crm/edit/' . (string)$rowId . '"><i class="fas fa-edit"></i></a>';


                $actionTxt .= '<a href="javascript:void(0);" class="delconfirm btn btn-icon text-danger" data-row_id="' . $rowId . '" data-act_url="/' . ADMIN_PATH . '/crm/delete"><i class="fas fa-trash-alt"></i></a>';

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
                    'lead' => $details->first_name . ' ' . $details->last_name,
                    'project_details' => $row->project_details,
                    'price' => $row->price,
                    "status" =>  $statusTxt,
                    "action" =>  $actionTxt
                    //"action" =>  '1'
                );
            }
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
            $this->data['lms_opt'] = $this->CrmModel->get_selected_fields(LMS, ['status' => '1'], ['id', 'first_name', 'last_name'])->getResult();
            if ($id != '') {
                $condition = array('is_deleted' => '0');
                $this->data['info'] = $this->LmsModel->get_selected_fields(CRM, $condition)->getRow();
                if (!empty($this->data['info'])) {
                    $this->data['title'] = 'Edit Crm';
                } else {
                    $this->session->setFlashdata('error_message', 'Couldnot find the Lms');
                    return redirect()->route(ADMIN_PATH . '/crm/list');
                }
            } else {
                $this->data['title'] = 'Add Crm';
            }
            echo view(ADMIN_PATH . '/crm/add_edit', $this->data);
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
                $this->data['crmDetails'] = $this->CrmModel->get_all_details(CRM, $condition)->getRow();
                $this->data['lmsDetails'] = $this->LmsModel->get_all_details(LMS, $condition)->getRow();
                if (!empty($this->data['crmDetails']) && !empty($this->data['lmsDetails'])) {
                    $this->data['title'] = 'Crm view';
                    echo view(ADMIN_PATH . '/crm/view', $this->data);
                } else {
                    $this->session->setFlashdata('error_message', 'Couldnot find the Crm');
                    // $this->setFlashMessage('error', 'Couldn\'t find the subadmin');
                    return redirect()->route(ADMIN_PATH . '/crm/list');
                }
            } else {
                $this->session->setFlashdata('error_message', 'Couldnot find the Crm');
                // $this->setFlashMessage('error', 'Couldn\'t find the subadmin');
                return redirect()->route(ADMIN_PATH . '/crm/list');
            }
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/' . ADMIN_PATH);
        }
    }

    public function insertUpdate()
    {
        if ($this->checkSession('A') != '') {
            $project_details = (string)$this->request->getPostGet('project_details');
            $file = $this->request->getFile('crm_file');
            $price = (string)$this->request->getPostGet('price');
            $lead = (string)$this->request->getPostGet('lead');
            $status = (string)$this->request->getPostGet('status');
            $id = (string)$this->request->getPostGet('id');
            if ($status == '') {
                $status = 'off';
            }
            // echo $file;die;
            $fSubmit = FALSE;
            if ($project_details != '' && $price != '' && $lead != '') {
                if ($status == 'on') {
                    $status = '1';
                } else {
                    $status = '0';
                }
                $name =  '';
                $dataArr = array(
                    'lead' => $lead,
                    'project_details' => $project_details,
                    'price' => $price,
                    'status' => $status,
                );
                // $file->setRules('uploaded[crm_file]|max_size[crm_file,1024]|ext_in[crm_file,pdf,docx]');
                if ($file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move(WRITEPATH . CRM_DOC_PATH, $newName);
                    $dataArr['document_name'] = $file->getName();
                } else {
                    echo 'Upload failed.';
                }
                if ($id == '') {
                    $this->LmsModel->simple_insert(CRM, $dataArr);
                    $this->session->setFlashdata('success_message', 'Crm details added successfully.');
                    // $this->setFlashMessage('success', 'Crm details added successfully');
                    $fSubmit = TRUE;
                } else {
                    $condition = array('id' => $id);
                    $this->LmsModel->update_details(CRM, $dataArr, $condition);
                    $this->session->setFlashdata('success_message', 'Crm details update successfully');
                    $fSubmit = TRUE;
                }
            } else {
                $this->session->setFlashdata('error_message', 'Form data is missing.');
            }
            if ($fSubmit) {
                $url = ADMIN_PATH . '/crm/list';
            } else {
                if ($id == '') $url = ADMIN_PATH . '/crm/add';
                else $url = ADMIN_PATH . '/crm/edit/' . $id;
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
                $this->CrmModel->update_details(CRM, $newdata, $condition);
                $returnArr['status'] = '1';
                $returnArr['response'] = 'Crm Status Changed Successfully';
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
                    $this->LmsModel->isDelete(CRM, 'id', TRUE);
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

    public function showFile()
    {
        $uri = service('uri');
        $filename = $uri->getSegment(4);

        helper("filesystem");
        $path = WRITEPATH . 'uploads/crm_doc/';

        $fullpath = $path . $filename;
        $file = new \CodeIgniter\Files\File($fullpath, true);
        $binary = readfile($fullpath);
        return $this->response
            ->setHeader('Content-Type', $file->getMimeType())
            ->setHeader('Content-disposition', 'inline; filename="' . $file->getBasename() . '"')
            ->setStatusCode(200)
            ->setBody($binary);
    }
}
