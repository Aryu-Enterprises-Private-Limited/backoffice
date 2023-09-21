<?php

namespace App\Controllers\Admin;


use App\Controllers\BaseController;

class Candidate extends BaseController
{

    public function __construct()
    {
        $this->session = session();
        $this->LmsModel = new \App\Models\LmsModel();
    }

    public function index()
    {
        if ($this->checkSession('A') != '') {
            $this->data['title'] = 'Candidates List';
            $this->data['job_open_opt'] = $this->LmsModel->get_selected_fields(JOBS, ['status' => '1', 'is_deleted' => '0'], ['id', 'jobs_name'])->getResult();
            $this->data['app_sts_opt'] = $this->LmsModel->get_selected_fields(APPLICATION_STATUS, ['status' => '1', 'is_deleted' => '0'], ['id', 'app_status'])->getResult();
            $this->data['int_sts_opt'] = $this->LmsModel->get_selected_fields(INTERVIEW_STATUS, ['status' => '1', 'is_deleted' => '0'], ['id', 'interview_sts'])->getResult();
            $this->data['stage_opt'] = $this->LmsModel->get_selected_fields(STAGE, ['status' => '1', 'is_deleted' => '0'], ['id', 'stage_name'])->getResult();
            $this->data['rr_opt'] = $this->LmsModel->get_selected_fields(REASON_REJECTION, ['status' => '1', 'is_deleted' => '0'], ['id', 'reason_for_rej'])->getResult();
            echo view(ADMIN_PATH . '/candidates/list', $this->data);
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/' . ADMIN_PATH);
        }
    }

    public function list_ajax($returnType = 'json')
    {
        $job_open_id = $this->request->getGetPost('job_open');
        $app_sts_id = $this->request->getGetPost('app_sts');
        $int_sts_id = $this->request->getGetPost('int_sts');
        $stage_id = $this->request->getGetPost('stage');
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
                'last_name' => trim($dtSearchKeyVal),
                'location' => trim($dtSearchKeyVal),
                'contact_no' => trim($dtSearchKeyVal),
                'email' => trim($dtSearchKeyVal),
                'source' => trim($dtSearchKeyVal),
                'reason' => trim($dtSearchKeyVal),
            );
        }
        if (isset($job_open_id) && !empty($job_open_id)) {
            if (!empty($job_open_id)) {
                $condition['job_opening_id'] = $job_open_id;
            }
        }
        if (isset($app_sts_id) && !empty($app_sts_id)) {
            if (!empty($app_sts_id)) {
                $condition['application_status_id'] = $app_sts_id;
            }
        }
        if (isset($int_sts_id) && !empty($int_sts_id)) {
            if (!empty($int_sts_id)) {
                $condition['interview_status_id'] = $int_sts_id;
            }
        }
        if (isset($stage_id) && !empty($stage_id)) {
            if (!empty($stage_id)) {
                $condition['stage_id'] = $stage_id;
            }
        }
        $totCounts = $this->LmsModel->get_all_counts(CANDIDATES_DETAILS, $condition, '', $likeArr);
        $sortArr = array('dt' => -1);
        if ($sortField != '') {
            $sortArr = array($sortField => $sortJob);
        }

        $ajaxDataArr = $this->LmsModel->get_all_details(CANDIDATES_DETAILS, $condition, $sortArr, $rowperpage, $row_start, $likeArr);


        $tblData = array();
        $position = 1;

        foreach ($ajaxDataArr->getResult() as $row) {
            $cond = ['id' => $row->job_opening_id];
            $job_details = $this->LmsModel->get_all_details(JOBS, $cond)->getRow();


            $cond2 = ['id' => $row->application_status_id];
            $app_details = $this->LmsModel->get_all_details(APPLICATION_STATUS, $cond2)->getRow();

            $cond3 = ['id' => $row->interview_status_id];
            $int_sts_details = $this->LmsModel->get_all_details(INTERVIEW_STATUS, $cond3)->getRow();


            $cond4 = ['id' => $row->stage_id];
            $stage_details = $this->LmsModel->get_all_details(STAGE, $cond4)->getRow();

            $cond5 = ['id' => $row->reason_rejection_id];
            $rr_details = $this->LmsModel->get_all_details(REASON_REJECTION, $cond4)->getRow();

            $background_check = 'YES';
            if ($row->background_check == '0') {
                $background_check = 'NO';
            }
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

            $actionTxt = '<a class="btn btn-icon text-info" href="/' . ADMIN_PATH . '/candidates/view/' . (string)$rowId . '"><i class="fas fa-eye"></i></a>';

            $statusTxt =  '<a data-toggle="tooltip" data-original-title="' . $actTitle . '" class="stsconfirm" href="javascript:void(0);" data-row_id="' . $rowId . '" data-act_url="/' . ADMIN_PATH . '/candidates/change-status" data-stsmode="' . $mode . '"> <button type="button" class="btn ' . $btnColr . ' btn-sm waves-effect waves-light">' . $disp_status . '</button></a>';

            $actionTxt .= '<a class="btn btn-icon " href="/' . ADMIN_PATH . '/candidates/edit/' . (string)$rowId . '"><i class="fas fa-edit"></i></a>';


            $actionTxt .= '<a href="javascript:void(0);" class="delconfirm btn btn-icon text-danger" data-row_id="' . $rowId . '" data-act_url="/' . ADMIN_PATH . '/candidates/delete"><i class="fas fa-trash-alt"></i></a>';


            $tblData[] = array(
                // 'DT_RowId' => (string)$rowId,
                // 'checker_box' => '<input class="checkRows" name="checkbox_id[]" type="checkbox" value="' . $rowId . '">',
                'name' => ucfirst($row->first_name) . ' ' . ucfirst($row->last_name),
                'date' => $row->date,
                'location' => $row->location,
                'contact_no' => $row->contact_no,
                'email' => $row->email,
                'resume' => 0,
                'job_opening_id' => ucfirst($job_details->jobs_name),        //
                'application_status_id' => ucfirst($app_details->app_status), //
                'interview_status_id' => ucfirst($int_sts_details->interview_sts), //
                'stage_id' => ucfirst($stage_details->stage_name), //
                'background_check' => $background_check, //
                'source' => $row->source,
                'reason_rejection' => ucfirst($rr_details->reason_for_rej),
                'reason' => $row->reason,
                'created_at' => $row->created_at,
                // 'updated_at' => $row->updated_at,
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
            $this->data['job_open_opt'] = $this->LmsModel->get_selected_fields(JOBS, ['status' => '1', 'is_deleted' => '0'], ['id', 'jobs_name'])->getResult();
            $this->data['app_sts_opt'] = $this->LmsModel->get_selected_fields(APPLICATION_STATUS, ['status' => '1', 'is_deleted' => '0'], ['id', 'app_status'])->getResult();
            $this->data['int_sts_opt'] = $this->LmsModel->get_selected_fields(INTERVIEW_STATUS, ['status' => '1', 'is_deleted' => '0'], ['id', 'interview_sts'])->getResult();
            $this->data['stage_opt'] = $this->LmsModel->get_selected_fields(STAGE, ['status' => '1', 'is_deleted' => '0'], ['id', 'stage_name'])->getResult();
            $this->data['rr_opt'] = $this->LmsModel->get_selected_fields(REASON_REJECTION, ['status' => '1', 'is_deleted' => '0'], ['id', 'reason_for_rej'])->getResult();
            if ($id != '') {
                $condition = array('is_deleted' => '0', 'id' => $id);
                $this->data['candidates_info'] = $this->LmsModel->get_selected_fields(CANDIDATES_DETAILS, $condition)->getRow();
                if (!empty($this->data['candidates_info'])) {
                    $this->data['title'] = 'Edit Candidates';
                } else {
                    $this->session->setFlashdata('error_message', 'Couldnot find the Candidates');
                    return redirect()->route(ADMIN_PATH . '/candidates/list');
                }
            } else {
                $this->data['title'] = 'Add Candidates';
            }
            echo view(ADMIN_PATH . '/candidates/add_edit', $this->data);
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/' . ADMIN_PATH);
        }
    }



    public function insertUpdate()
    {

        if ($this->checkSession('A') != '') {
            $first_name = (string)$this->request->getPostGet('first_name');
            $last_name = (string)$this->request->getPostGet('last_name');
            $date = (string)$this->request->getPostGet('date');
            $location = (string)$this->request->getPostGet('location');
            $file = $this->request->getFile('resume');
            $contact_no = (string)$this->request->getPostGet('contact_no');
            $email = (string)$this->request->getPostGet('email');
            $job_opening_id = (string)$this->request->getPostGet('job_opening_id');
            $application_status_id = (string)$this->request->getPostGet('application_status_id');
            $interview_status_id = (string)$this->request->getPostGet('interview_status_id');
            $stage_id = (string)$this->request->getPostGet('stage_id');
            $source = (string)$this->request->getPostGet('source');
            $reason_rejection_id = (string)$this->request->getPostGet('reason_rejection_id');
            $reason = (string)$this->request->getPostGet('reason');
            $status = (string)$this->request->getPostGet('status');
            $background_check = (string)$this->request->getPostGet('background_check');
            $ready_to_relocate = (string)$this->request->getPostGet('ready_to_relocate');
            $id = (string)$this->request->getPostGet('id');
            if ($status == '') {
                $status = 'off';
            }
            if ($background_check == '') {
                $background_check = 'off';
            }
            $fSubmit = FALSE;
            if ($first_name != '' && $last_name != '' && $date != '' && $location != '' && $contact_no != '' && $email != '' && $job_opening_id != '' && $application_status_id != '' && $interview_status_id != '' && $stage_id != '' && $source != '' && $ready_to_relocate!='') {
                if ($status == 'on') {
                    $status = '1';
                } else {
                    $status = '0';
                }
                if ($background_check == 'on') {
                    $background_check = '1';
                } else {
                    $background_check = '0';
                }
                $dataArr = array(
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'date' => $date,
                    'location' => $location,
                    'email' => $email,
                    'contact_no' => $contact_no,
                    'job_opening_id' => $job_opening_id,
                    'application_status_id' => $application_status_id,
                    'interview_status_id' => $interview_status_id,
                    'stage_id' => $stage_id,
                    'ready_to_relocate' => $ready_to_relocate,
                    'source' => $source,
                    'status' => $status,
                    'background_check' => $background_check,
                    'reason_rejection_id' => $reason_rejection_id,
                    'reason' => $reason,
                    'is_deleted' => 0,
                );

                if ($id == '') {
                    $validation = \Config\Services::validation();
                    $validation->setRules(
                        [
                            'contact_no' => 'required|max_length[10]|min_length[10]|is_unique[candidates_details.contact_no]',
                            'email' => 'required|valid_email|is_unique[candidates_details.email]',
                        ],
                        [   // Errors

                            'contact_no' => [
                                'required' => 'This field is required.',
                            ],
                            'email' => [
                                'required' => 'This field is required.',
                            ],
                        ]
                    );
                    if (!$validation->withRequest($this->request)->run()) {
                        $errors = $validation->getErrors();
                        if (isset($errors['contact_no'])) {
                            $errors_msg = $errors['contact_no'];
                        } else {
                            $errors_msg = $errors['email'];
                        }
                        $this->session->setFlashdata('error_message', $errors_msg);
                        return redirect()->to('/' . ADMIN_PATH . '/candidates/add');
                    } else {
                        if ($file !== null) {
                            if ($file->isValid() && !$file->hasMoved()) {
                                $newName = $file->getRandomName();
                                $file->move(WRITEPATH . CANDIDATES_RESUME_DOC_PATH, $newName);
                                $dataArr['resume'] = $file->getName();
                            } else {
                                echo 'Upload failed.';
                            }
                        }
                        $this->LmsModel->simple_insert(CANDIDATES_DETAILS, $dataArr);
                        $this->session->setFlashdata('success_message', 'Candidates details added successfully.');
                        $fSubmit = TRUE;
                    }
                } else {
                    $validation = \Config\Services::validation();
                    $validation->setRules(
                        [
                            'contact_no' => 'required|max_length[10]|min_length[10]|is_unique[candidates_details.contact_no,id,' . $id . ']',
                            'email' => 'required|valid_email|is_unique[candidates_details.email,id,' . $id . ']',
                        ],
                        [   // Errors

                            'contact_no' => [
                                'required' => 'This field is required.',
                            ],
                            'email' => [
                                'required' => 'This field is required.',
                            ],
                        ]
                    );
                    if (!$validation->withRequest($this->request)->run()) {
                        $errors = $validation->getErrors();
                        if (isset($errors['contact_no'])) {
                            $errors_msg = $errors['contact_no'];
                        } else {
                            $errors_msg = $errors['email'];
                        }
                        $this->session->setFlashdata('error_message', $errors_msg);
                        return redirect()->to('/' . ADMIN_PATH . '/candidates/edit/' . $id);
                    } else {
                        if ($file !== null) {
                            if ($file->isValid() && !$file->hasMoved()) {
                                $newName = $file->getRandomName();
                                $file->move(WRITEPATH . CANDIDATES_RESUME_DOC_PATH, $newName);
                                $dataArr['resume'] = $file->getName();
                            } else {
                                echo 'Upload failed.';
                            }
                        }
                        $condition = array('id' => $id);
                        $this->LmsModel->update_details(CANDIDATES_DETAILS, $dataArr, $condition);
                        $this->session->setFlashdata('success_message', 'Candidates details update successfully');
                        $fSubmit = TRUE;
                    }
                }
            } else {
                $this->session->setFlashdata('error_message', 'Form data is missing.');
            }
            if ($fSubmit) {
                $url = ADMIN_PATH . '/candidates/list';
            } else {
                if ($id == '') $url = ADMIN_PATH . '/candidates/add';
                else $url = ADMIN_PATH . '/candidates/edit/' . $id;
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
                $this->LmsModel->update_details(CANDIDATES_DETAILS, $newdata, $condition);
                $returnArr['status'] = '1';
                $returnArr['response'] = 'Candidates Status Changed Successfully';
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
                $this->data['candidatesDetails'] = $this->LmsModel->get_all_details(CANDIDATES_DETAILS, $condition)->getRow();

                $condition2 = ['id' => $this->data['candidatesDetails']->job_opening_id];
                $this->data['job_details'] = $this->LmsModel->get_all_details(JOBS, $condition2)->getRow();

                $condition3 = ['id' => $this->data['candidatesDetails']->application_status_id];
                $this->data['app_details']  = $this->LmsModel->get_all_details(APPLICATION_STATUS, $condition3)->getRow();

                $condition4 = ['id' => $this->data['candidatesDetails']->interview_status_id];
                $this->data['int_sts_details'] =  $this->LmsModel->get_all_details(INTERVIEW_STATUS, $condition4)->getRow();

                $condition5 = ['id' => $this->data['candidatesDetails']->stage_id];
                $this->data['stage_details'] = $this->LmsModel->get_all_details(STAGE, $condition5)->getRow();

                $condition6 = ['id' => $this->data['candidatesDetails']->reason_rejection_id];
                $this->data['rr_details'] =  $this->LmsModel->get_all_details(REASON_REJECTION, $condition6)->getRow();

                if (!empty($this->data['candidatesDetails'])) {
                    $this->data['title'] = 'Candidates  view';
                    echo view(ADMIN_PATH . '/candidates/view', $this->data);
                } else {
                    $this->session->setFlashdata('error_message', 'Couldnot find the Candidates');
                    // $this->setFlashMessage('error', 'Couldn\'t find the subadmin');
                    return redirect()->route(ADMIN_PATH . '/candidates/list');
                }
            } else {
                $this->session->setFlashdata('error_message', 'Couldnot find the Candidates');
                // $this->setFlashMessage('error', 'Couldn\'t find the subadmin');
                return redirect()->route(ADMIN_PATH . '/candidates/list');
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
                    $this->LmsModel->isDelete(CANDIDATES_DETAILS, 'id', TRUE);
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
        $path = WRITEPATH . 'uploads/candidates_cv_doc/';

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
