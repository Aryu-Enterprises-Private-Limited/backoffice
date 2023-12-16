<?php

namespace App\Controllers\Admin;


use App\Controllers\BaseController;

class Job extends BaseController
{

    public function __construct()
    {
        $this->session = session();
        $this->LmsModel = new \App\Models\LmsModel();
    }

    public function index()
    {
        if ($this->checkSession('A') != '') {
            $this->data['title'] = 'Job List';
            echo view(ADMIN_PATH . '/job/list', $this->data);
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
                'jobs_name' => trim($dtSearchKeyVal),
                'job_budget' => trim($dtSearchKeyVal),
            );
        }

        $totCounts = $this->LmsModel->get_all_counts(JOBS, $condition, '', $likeArr);
        $sortArr = array('id' => -1);
        if ($sortField != '') {
            $sortArr = array($sortField => $sortJob);
        }
        $ajaxDataArr = $this->LmsModel->get_all_details(JOBS, $condition, $sortArr, $rowperpage, $row_start, $likeArr);


        $tblData = array();
        $position = 1;
$x =1;
        foreach ($ajaxDataArr->getResult() as $row) {
            $job_type_id = $row->job_type_id;
            $job_type = $this->LmsModel->get_selected_fields(JOB_TYPE, ['id' =>  $job_type_id])->getRow();

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

            $actionTxt = '<a class="btn btn-icon text-info" href="/' . ADMIN_PATH . '/job/view/' . (string)$rowId . '"><i class="fas fa-eye"></i></a>';

            $statusTxt =  '<a data-toggle="tooltip" data-original-title="' . $actTitle . '" class="stsconfirm" href="javascript:void(0);" data-row_id="' . $rowId . '" data-act_url="/' . ADMIN_PATH . '/job/change-status" data-stsmode="' . $mode . '"> <button type="button" class="btn ' . $btnColr . ' btn-sm waves-effect waves-light">' . $disp_status . '</button></a>';

            $actionTxt .= '<a class="btn btn-icon " href="/' . ADMIN_PATH . '/job/edit/' . (string)$rowId . '"><i class="fas fa-edit"></i></a>';


            $actionTxt .= '<a href="javascript:void(0);" class="delconfirm btn btn-icon text-danger" data-row_id="' . $rowId . '" data-act_url="/' . ADMIN_PATH . '/job/delete"><i class="fas fa-trash-alt"></i></a>';


            $tblData[] = array(
                // 'DT_RowId' => (string)$rowId,
                // 'checker_box' => '<input class="checkRows" name="checkbox_id[]" type="checkbox" value="' . $rowId . '">',
                's_no' => $x,
                'jobs_name' => ucfirst($row->jobs_name),
                'job_budget' =>  $row->job_budget,
                'job_type_id' =>  isset($job_type->job_type_name) && $job_type->job_type_name !='' ? ucfirst($job_type->job_type_name) : '-',
                'job_requirement' =>  isset($row->job_requirement) && $row->job_requirement !='' ? ucfirst($row->job_requirement) : '-',
                'created_at' => $row->created_at,
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
            $this->data['job_type_opt'] = $this->LmsModel->get_selected_fields(JOB_TYPE, ['status' => '1', 'is_deleted' => '0'], ['id', 'job_type_name'])->getResult();
            if ($id != '') {
                $condition = array('is_deleted' => '0', 'id' => $id);
                $this->data['job_info'] = $this->LmsModel->get_selected_fields(JOBS, $condition)->getRow();
                if (!empty($this->data['job_info'])) {
                    $this->data['title'] = 'Edit Jobs';
                } else {
                    $this->session->setFlashdata('error_message', 'Couldnot find the Jobs');
                    return redirect()->route(ADMIN_PATH . '/job/list');
                }
            } else {
                $this->data['title'] = 'Add Job';
            }
            echo view(ADMIN_PATH . '/job/add_edit', $this->data);
        } else {
            $this->session->setFlashdata('error_message', 'Please login!!!');
            return redirect()->to('/' . ADMIN_PATH);
        }
    }



    public function insertUpdate()
    {
        if ($this->checkSession('A') != '') {
            $job_name = (string)$this->request->getPostGet('job_name');
            $job_desc = (string)$this->request->getPostGet('job_desc');
            $job_budget = (string)$this->request->getPostGet('job_budget');
            $job_type_id = (string)$this->request->getPostGet('job_type');
            $job_requirement = $this->request->getPostGet('job_req');
            $status = (string)$this->request->getPostGet('status');
            $id = (string)$this->request->getPostGet('id');
            if ($status == '') {
                $status = 'off';
            }
            $fSubmit = FALSE;
            if ($job_name != '' && $job_desc != '' && $job_budget != '' && $job_type_id != '' && $job_requirement != '') {
                if ($status == 'on') {
                    $status = '1';
                } else {
                    $status = '0';
                }
                $req_str = implode(",", $job_requirement);
                $dataArr = array(
                    'jobs_name' => $job_name,
                    'job_desc' => $job_desc,
                    'job_budget' => $job_budget,
                    'job_requirement' => $req_str,
                    'job_type_id' => $job_type_id,
                    'status' => $status,
                    'is_deleted' => '0',
                );

                if ($id == '') {
                    $this->LmsModel->simple_insert(JOBS, $dataArr);
                    $this->session->setFlashdata('success_message', 'Jobs details added successfully.');
                    $fSubmit = TRUE;
                } else {
                    $condition = array('id' => $id);
                    $this->LmsModel->update_details(JOBS, $dataArr, $condition);
                    $this->session->setFlashdata('success_message', 'Jobs details update successfully');
                    $fSubmit = TRUE;
                }
            } else {
                $this->session->setFlashdata('error_message', 'Form data is missing.');
            }
            if ($fSubmit) {
                $url = ADMIN_PATH . '/job/list';
            } else {
                if ($id == '') $url = ADMIN_PATH . '/job/add';
                else $url = ADMIN_PATH . '/job/edit/' . $id;
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
                $this->LmsModel->update_details(JOBS, $newdata, $condition);
                $returnArr['status'] = '1';
                $returnArr['response'] = 'Job Status Changed Successfully';
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
                $this->data['jobDetails'] = $this->LmsModel->get_all_details(JOBS, $condition)->getRow();

                $condition2 = ['id' => $this->data['jobDetails']->job_type_id];
                $this->data['job_type_details'] = $this->LmsModel->get_all_details(JOB_TYPE, $condition2)->getRow();

                if (!empty($this->data['jobDetails'])) {
                    $this->data['title'] = 'Job view';
                    echo view(ADMIN_PATH . '/job/view', $this->data);
                } else {
                    $this->session->setFlashdata('error_message', 'Couldnot find the Job');
                    // $this->setFlashMessage('error', 'Couldn\'t find the subadmin');
                    return redirect()->route(ADMIN_PATH . '/job/list');
                }
            } else {
                $this->session->setFlashdata('error_message', 'Couldnot find the Job');
                // $this->setFlashMessage('error', 'Couldn\'t find the subadmin');
                return redirect()->route(ADMIN_PATH . '/job/list');
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
                    $this->LmsModel->isDelete(JOBS, 'id', TRUE);
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
